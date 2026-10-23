<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var array $arResult
 * @var array $arParams
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Application;
use Korus\Main\Exceptions\LicenseException;
use Korus\Main\Loader;
use Korus\Personalarea\Orm\CompetenceTable;

global $USER;
if (CUser::IsOnLine($USER->GetID())) {
    // Пользователь в сети
    echo "Пользователь " . $USER->GetFullName() . " в сети.";
} else {
    // Пользователь офлайн
    echo "Пользователь " . $USER->GetFullName() . " не в сети.";
}

foreach ($arResult['FormFields'] as &$field) {
    if ($field['name'] === 'TIME_ZONE') {
//        $field['showAlways'] = true;
        unset($field['visibilityPolicy']);
        break;
    }
}
unset($field);

//Организационная структура, цепочка
$arResult['DEPARTMENT_CHAINS'] = [];

if (!empty($arResult['FormData']['UF_DEPARTMENT'])) {
    \Bitrix\Main\Loader::includeModule('korus.office');
    $mDepartments = new Korus\Office\Manager\Departments();

    foreach ($arResult['FormData']['UF_DEPARTMENT'] as $depId) {
        $chain = $mDepartments->getChainParents($depId);
        if (!empty($chain)) {
            $arResult['DEPARTMENT_CHAINS'][] = array_values($chain);
        }
    }
}

// Исключаем табельный номер. Показывается в "Кадровая информация"
if (!empty($arResult['FormData']['UF_SAP_ID'])) {
    unset($arResult['FormData']['UF_SAP_ID']);
}

if (empty($arResult['FormData']['UF_MOB_PHONE_PER_ACT']['VALUE']) && !($arResult['IsOwnProfile']
        || $arResult['Permissions']['edit'])) {
    foreach ($arResult['FormFields'] as $key => $fields) {
        if (strcasecmp($fields['name'], 'UF_MOB_PHONE_PER') == 0) {
            unset($arResult['FormFields'][$key]);
        }
    }
}

if ($arResult['IsOwnProfile'] || $arResult['Permissions']['edit']) {
    $objRequest = Application::getInstance()->getContext()->getRequest();

    if ($objRequest->isPost()) {
        $action = $objRequest->getPost('action');

        if (!empty($action) && strcasecmp($action, 'changePhoneVisibility') == 0) {
            $GLOBALS['APPLICATION']->restartBuffer();
            $visibility = $objRequest->getPost('visibility');
            $user = new CUser();
            $user->update($arResult['User']['ID'], ['UF_MOB_PHONE_PER_ACT' => $visibility]);
            die();
        }
    }
}


if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UserTable;
use Bitrix\Main\Data\Cache;

$departmentId = (int)($arResult['User']['UF_DEPARTMENT'][0] ?? 0);

if ($departmentId > 0) {
    // 1. Инициализируем управляемый кеш
    $cache = Cache::createInstance();
    $cacheTtl = 3600; // Время жизни кеша (1 час)
    $cacheId = 'dept_users_' . $departmentId;
    $cachePath = '/department_users/';

    if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
        // Данные берутся из кеша
        $vars = $cache->getVars();
        $arDepartmentUsers = $vars['USERS'];
        $arResult['DepartmentHead'] = $vars['HEAD'];
    } elseif ($cache->startDataCache()) {
        // 2. Данные не в кеше — выполняем запросы

        // --- Получаем руководителя раздела инфоблока departments ---
        $arResult['DepartmentHead'] = [];

        // Внимание: укажите реальный IBLOCK_ID вашего инфоблока departments
        $iblockId = 3; // <-- ЗАМЕНИТЕ НА ВАШ ID

        $rsSection = CIBlockSection::GetList(
            [],
            ['ID' => $departmentId, 'IBLOCK_ID' => $iblockId],
            false,
            ['ID', 'NAME', 'UF_HEAD']
        );

        if ($arSection = $rsSection->Fetch()) {
            $headId = (int)$arSection['UF_HEAD'];
            if ($headId > 0) {
                // Получаем данные руководителя (ФИО, фото и т.д.)
                $rsHead = UserTable::getList([
                    'select' => ['ID', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'EMAIL', 'PERSONAL_PHOTO'],
                    'filter' => ['=ID' => $headId, '=ACTIVE' => 'Y'],
                ]);
                if ($arHead = $rsHead->fetch()) {
                    if (!empty($arHead['PERSONAL_PHOTO'])) {
                        $arHead['PERSONAL_PHOTO'] = CFile::GetPath($arHead['PERSONAL_PHOTO']);
                    }
                    $arResult['DepartmentHead'] = $arSection;
                }
            }
        }

        // --- Получаем сотрудников отдела (ваш исходный код) ---
        $arDepartmentUsers = [];
        $result = UserTable::getList([
            'select' => ['ID', 'NAME', 'LAST_NAME','PERSONAL_PROFESSION', 'EMAIL', 'PERSONAL_PHOTO', 'UF_DEPARTMENT'],
            'filter' => [
                '=ACTIVE' => 'Y',
                '=UF_DEPARTMENT' => $departmentId,
            ],
            'order' => ['ID' => 'ASC']
        ]);

        while ($user = $result->fetch()) {
            if (!empty($user['PERSONAL_PHOTO'])) {
                $user['PERSONAL_PHOTO'] = CFile::GetPath($user['PERSONAL_PHOTO']);
            }
            // Исключаем текущего пользователя из списка
            if ($user['ID'] != $arResult['User']['ID']) {
                $arDepartmentUsers[] = $user;
            }
        }

        // 3. Сохраняем результат в кеш
        $cache->endDataCache([
            'USERS' => $arDepartmentUsers,
            'HEAD'  => $arResult['DepartmentHead'],
        ]);
    }

    $arResult['DepartmentUsers'] = $arDepartmentUsers;
}



$competenceList = $arResult['FormData']['UF_COMPETENCE']['VALUE'];

try {
    if ($competenceList) {
        Loader::includeModule('korus.personalarea');
        $arResult['COMPETENCES'] = CompetenceTable::getList([
            'filter' => ['ID' => $competenceList],
        ])->fetchAll();
    }
} catch (LicenseException) {
}

