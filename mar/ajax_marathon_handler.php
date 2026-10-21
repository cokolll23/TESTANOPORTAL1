<?php
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Application;
use Bitrix\Iblock\IblockTable;

header('Content-Type: application/json; charset=utf-8');

global $USER;

// 1. Проверка авторизации
if (!$USER->IsAuthorized()) {
    echo json_encode(['status' => 'error', 'message' => 'Пользователь не авторизован.']);
    exit;
}

$request = Context::getCurrent()->getRequest();

// 2. Проверка CSRF-токена сессии Битрикса
if (!check_bitrix_sessid()) {
    echo json_encode(['status' => 'error', 'message' => 'Истекла пользовательская сессия. Обновите страницу.']);
    exit;
}

// 3. Получение и валидация входящих данных
$currentUserId = (int)$USER->GetID();
$targetUserId = (int)$request->getPost('target_user_id');
$comment = trim((string)$request->getPost('comment'));

if ($targetUserId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Не выбран сотрудник для отправки звезды.']);
    exit;
}

if ($targetUserId === $currentUserId) {
    echo json_encode(['status' => 'error', 'message' => 'Нельзя отправить звезду самому себе.']);
    exit;
}

if ($comment === '') {
    echo json_encode(['status' => 'error', 'message' => 'Заполните текст комментария.']);
    exit;
}

// 4. Подключение инфоблоков
if (!Loader::includeModule('iblock')) {
    echo json_encode(['status' => 'error', 'message' => 'Модуль инфоблоков не найден.']);
    exit;
}

// Получаем ID инфоблока по коду stars_marathon
$arIblock = IblockTable::getList([
    'filter' => ['=CODE' => 'stars_marathon'],
    'select' => ['ID'],
    'limit' => 1
])->fetch();

if (!$arIblock) {
    echo json_encode(['status' => 'error', 'message' => 'Инфоблок stars_marathon не найден.']);
    exit;
}

$iblockId = (int)$arIblock['ID'];

// 5. Получаем данные текущего пользователя для формирования названия
$userLastName = trim((string)$USER->GetLastName());
if ($userLastName === '') {
    $userLastName = trim((string)$USER->GetFullName());
}
if ($userLastName === '') {
    $userLastName = (string)$USER->GetLogin();
}

$elementName = 'Звезда от: ' . $userLastName;

// 6. Формирование полей и свойств элемента
$el = new CIBlockElement();

$arFields = [
    'IBLOCK_ID'       => $iblockId,
    'NAME'            => $elementName,
    'ACTIVE'          => 'Y',
    'PREVIEW_TEXT'    => $comment,
    'PREVIEW_TEXT_TYPE' => 'text',
    'PROPERTY_VALUES' => [
        'who_send_user'   => $currentUserId,
        'whome_send_user' => $targetUserId,
    ],
];

// 7. Сохранение элемента
// 7. Сохранение элемента
if ($newElementId = $el->Add($arFields)) {

    // Уменьшаем UF_MARATHON текущего пользователя на 1 (не ниже нуля)
    global $USER;
    if ($USER->IsAuthorized()) {
        $userId = (int)$USER->GetID();

        // Получаем текущее значение поля
        $rsUser = CUser::GetByID($userId);
        $arUser = $rsUser->Fetch();

        $currentValue = isset($arUser['UF_MARATHON']) ? (int)$arUser['UF_MARATHON'] : 0;

        // Вычисляем новое значение (не меньше нуля)
        $newValue = max(0, $currentValue - 1);

        // Обновляем поле
        $user = new CUser;
        $fields = ['UF_MARATHON' => $newValue];
        $user->Update($userId, $fields);
    }

    echo json_encode([
        'status'     => 'success',
        'message'    => 'Звезда добавлена',
        'element_id' => $newElementId
    ]);
} else {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Ошибка при сохранении: ' . $el->LAST_ERROR
    ]);
}
exit;