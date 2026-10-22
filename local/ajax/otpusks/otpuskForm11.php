<?php
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('BX_NO_ACCELERATOR_RESET', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;

header('Content-Type: application/json; charset=utf-8');

function sendJson(bool $success, string $message, array $extra = [], int $httpCode = 200): void
{
    http_response_code($httpCode);
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message,
    ], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson(false, 'Метод не поддерживается.', [], 405);
}

global $USER;

if (!$USER->IsAuthorized()) {
    sendJson(false, 'Требуется авторизация.', [], 401);
}

if (!check_bitrix_sessid()) {
    sendJson(false, 'Срок действия сессии истек. Обновите страницу.', [], 403);
}

if (!Loader::includeModule('iblock')) {
    sendJson(false, 'Модуль инфоблоков недоступен.', [], 500);
}

$currentUserId = (int)$USER->GetID();

$departmentId = isset($_POST['DEPARTMENT']) ? (int)$_POST['DEPARTMENT'] : 0;
$dateFromRaw  = isset($_POST['DATE_FROM']) ? trim((string)$_POST['DATE_FROM']) : '';
$dateToRaw    = isset($_POST['DATE_TO']) ? trim((string)$_POST['DATE_TO']) : '';

if ($departmentId <= 0) {
    sendJson(false, 'Не выбран департамент.', [], 422);
}

$dateFromObj = DateTime::createFromFormat('Y-m-d', $dateFromRaw);
$dateToObj   = DateTime::createFromFormat('Y-m-d', $dateToRaw);

if (!$dateFromObj || !$dateToObj || $dateFromObj->format('Y-m-d') !== $dateFromRaw || $dateToObj->format('Y-m-d') !== $dateToRaw) {
    sendJson(false, 'Укажите корректный диапазон дат.', [], 422);
}

if ($dateFromRaw > $dateToRaw) {
    sendJson(false, 'Дата окончания не может быть раньше даты начала.', [], 422);
}

// 1. Проверяем существование раздела в инфоблоке departments и получаем руководителя (UF_HEAD)
$depIblock = CIBlock::GetList([], ['=CODE' => 'departments', 'ACTIVE' => 'Y'])->Fetch();
if (!$depIblock) {
    sendJson(false, 'Инфоблок департаментов не найден.', [], 500);
}

$section = CIBlockSection::GetList(
    [],
    [
        'IBLOCK_ID' => (int)$depIblock['ID'],
        '=ID' => $departmentId,
        'ACTIVE' => 'Y',
    ],
    false,
    ['ID', 'NAME', 'UF_HEAD']
)->Fetch();

if (!$section) {
    sendJson(false, 'Выбранный департамент не найден.', [], 422);
}

$headId = $section['UF_HEAD'] ?? 0;
if (is_array($headId)) {
    $headId = reset($headId);
}
$headId = (int)$headId;

if ($headId <= 0) {
    sendJson(false, 'У выбранного департамента не назначен руководитель.', [], 422);
}

// 2. Ищем целевой инфоблок otpusk_plan (или otpusks_plan)
$targetIblock = CIBlock::GetList([], ['=CODE' => 'otpusks_plan', 'ACTIVE' => 'Y'])->Fetch();
if (!$targetIblock) {
    // Резервный поиск, если код с буквой s
    $targetIblock = CIBlock::GetList([], ['=CODE' => 'otpusks_plan', 'ACTIVE' => 'Y'])->Fetch();
}

if (!$targetIblock) {
    sendJson(false, 'Инфоблок заявок на отпуск (otpusk_plan) не найден.', [], 500);
}

$targetIblockId = (int)$targetIblock['ID'];

// Получаем ФИО текущего пользователя для названия элемента
$userRes = CUser::GetByID($currentUserId)->Fetch();
$fio = trim(($userRes['LAST_NAME'] ?? '') . ' ' . ($userRes['NAME'] ?? '') . ' ' . ($userRes['SECOND_NAME'] ?? ''));
if ($fio === '') {
    $fio = $userRes['LOGIN'] ?? ('User #' . $currentUserId);
}

$elementName = sprintf(
    'Заявка: %s (%s — %s)',
    $fio,
    $dateFromObj->format('d.m.Y'),
    $dateToObj->format('d.m.Y')
);

// 3. Создаем элемент
$el = new CIBlockElement();

$propertyValues = [
    'FIO'             => $currentUserId, // Привязка к пользователю
    'DEPARTMENT'      => $departmentId,  // Привязка к разделу departments
    'DEPARTMENT_HEAD' => $headId,        // Привязка к пользователю (руководитель)
    'DATE_FROM'       => ConvertTimeStamp($dateFromObj->getTimestamp(), 'SHORT'),
    'DATE_TO'         => ConvertTimeStamp($dateToObj->getTimestamp(), 'SHORT'),
];

$elementId = $el->Add([
    'IBLOCK_ID'       => $targetIblockId,
    'NAME'            => $elementName,
    'ACTIVE'          => 'Y',
    'CREATED_BY'      => $currentUserId,
    'MODIFIED_BY'     => $currentUserId,
    'PROPERTY_VALUES' => $propertyValues,
]);

if (!$elementId) {
    sendJson(false, 'Ошибка при сохранении заявки: ' . $el->LAST_ERROR, [], 500);
}

sendJson(true, 'Заявка на отпуск успешно создана!', ['id' => (int)$elementId]);