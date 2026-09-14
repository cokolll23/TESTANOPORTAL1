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

foreach ($arResult['FormFields'] as &$field) {
    if ($field['name'] === 'TIME_ZONE') {
//        $field['showAlways'] = true;
        unset($field['visibilityPolicy']);
        break;
    }
}
unset($field);

// Департамент, цепочка
$arResult['DEPARTMENT_CHAIN'] = '';
if (!empty($arResult['FormData']['UF_DEPARTMENT'])) {
    \Bitrix\Main\Loader::includeModule('korus.office');
    $mDepartments = new Korus\Office\Manager\Departments();
    foreach ($arResult['FormData']['UF_DEPARTMENT'] as $depId) {
        $str = '';
        foreach ($mDepartments->getChainParents($depId) as $dep) {
            $str .= ' > <a href="' . $dep['href'] . '" target="_top">' . $dep['name'] . '</a>';
        }
    }
    $arResult['DEPARTMENT_CHAIN'] = substr($str, 3);
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

