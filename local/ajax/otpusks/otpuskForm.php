<?php

define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('DisableEventsCheck', true);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

header('Content-Type: application/json; charset=UTF-8');

function jsonResponse(
    bool $success,
    string $message = '',
    array $additionalData = [],
    int $statusCode = 200
): void {
    http_response_code($statusCode);

    echo json_encode(
        array_merge(
            [
                'success' => $success,
                'message' => $message,
            ],
            $additionalData
        ),
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Разрешен только POST-запрос.', [], 405);
}

global $USER;

if (!$USER->IsAuthorized()) {
    jsonResponse(false, 'Для отправки формы необходимо авторизоваться.', [], 401);
}

if (!check_bitrix_sessid()) {
    jsonResponse(
        false,
        'Сессия формы истекла. Обновите страницу и повторите отправку.',
        [],
        403
    );
}

if (!CModule::IncludeModule('iblock')) {
    jsonResponse(false, 'Не удалось подключить модуль инфоблоков.', [], 500);
}

$getPostString = static function ($value): string {
    return is_scalar($value) ? trim((string)$value) : '';
};

$fio = $getPostString($_POST['FIO'] ?? '');
$email = $getPostString($_POST['EMAIL'] ?? '');
$dateFrom = $getPostString($_POST['DATE_FROM'] ?? '');
$dateTo = $getPostString($_POST['DATE_TO'] ?? '');
$departmentId = (int)($_POST['UF_DEPARTMENT'] ?? 0);

$errors = [];

if ($fio === '') {
    $errors[] = 'Укажите ФИО.';
}

if (!check_email($email)) {
    $errors[] = 'Укажите корректный email.';
}

$isValidDate = static function (string $date): bool {
    $dateObject = DateTime::createFromFormat('!Y-m-d', $date);
    $dateErrors = DateTime::getLastErrors();

    if ($dateObject === false) {
        return false;
    }

    if (
        is_array($dateErrors)
        && (
            $dateErrors['warning_count'] > 0
            || $dateErrors['error_count'] > 0
        )
    ) {
        return false;
    }

    return $dateObject->format('Y-m-d') === $date;
};

if (!$isValidDate($dateFrom)) {
    $errors[] = 'Укажите корректную дату начала.';
}

if (!$isValidDate($dateTo)) {
    $errors[] = 'Укажите корректную дату окончания.';
}

if (
    $isValidDate($dateFrom)
    && $isValidDate($dateTo)
    && $dateFrom > $dateTo
) {
    $errors[] = 'Дата окончания не может быть раньше даты начала.';
}

if (!$departmentId) {
    $errors[] = 'Выберите департамент.';
}

if ($errors) {
    jsonResponse(
        false,
        implode(' ', $errors),
        [
            'errors' => $errors,
        ],
        422
    );
}

/**
 * Проверяем департамент и получаем его название и руководителя
 */
$departmentsIblock = CIBlock::GetList(
    [],
    [
        '=CODE' => 'departments',
        'ACTIVE' => 'Y',
    ]
)->Fetch();

$departmentsIblockId = (int)($departmentsIblock['ID'] ?? 0);

if (!$departmentsIblockId) {
    jsonResponse(false, 'Инфоблок departments не найден.', [], 500);
}

$department = CIBlockSection::GetList(
    [],
    [
        'IBLOCK_ID' => $departmentsIblockId,
        'ID' => $departmentId,
        'ACTIVE' => 'Y',
    ],
    false,
    [
        'ID',
        'NAME',
        'UF_HEAD',
    ]
)->Fetch();

if (!$department) {
    jsonResponse(false, 'Выбранный департамент не найден.', [], 422);
}

$headId = $department['UF_HEAD'] ?? 0;

if (is_array($headId)) {
    $headId = reset($headId);
}

$managerName = '';

if ((int)$headId > 0) {
    $headUser = CUser::GetByID((int)$headId)->Fetch();

    if ($headUser) {
        $managerName = trim(
            implode(
                ' ',
                array_filter([
                    $headUser['LAST_NAME'] ?? '',
                    $headUser['NAME'] ?? '',
                    $headUser['SECOND_NAME'] ?? '',
                ])
            )
        );

        if ($managerName === '') {
            $managerName = (string)($headUser['LOGIN'] ?? '');
        }
    }
}

/**
 * Ищем инфоблок для заявок
 */
$planIblock = CIBlock::GetList(
    [],
    [
        '=CODE' => 'otpusks_plan',
        'ACTIVE' => 'Y',
    ]
)->Fetch();

$planIblockId = (int)($planIblock['ID'] ?? 0);

if (!$planIblockId) {
    jsonResponse(false, 'Инфоблок otpusks_plan не найден.', [], 500);
}

$dateFromObject = DateTime::createFromFormat('!Y-m-d', $dateFrom);
$dateToObject = DateTime::createFromFormat('!Y-m-d', $dateTo);

/**
 * Для свойств типа "Дата" Bitrix обычно принимает формат d.m.Y
 */
$bitrixDateFrom = $dateFromObject->format('d.m.Y');
$bitrixDateTo = $dateToObject->format('d.m.Y');

$element = new CIBlockElement();

$elementFields = [
    'IBLOCK_ID' => $planIblockId,
    'ACTIVE' => 'Y',
    'NAME' => $fio . ' — ' . $bitrixDateFrom . ' — ' . $bitrixDateTo,
    'CREATED_BY' => (int)$USER->GetID(),

    /*
     * Коды свойств должны совпадать с кодами свойств
     * инфоблока otpusks_plan.
     */
    'PROPERTY_VALUES' => [
        'FIO' => $fio,
        'EMAIL' => $email,
        'DATE_FROM' => $bitrixDateFrom,
        'DATE_TO' => $bitrixDateTo,
        'DEPARTMENT' => $departmentId,
        'DEPARTMENT_NAME' => $department['NAME'],
        'DEPARTMENT_HEAD' => $managerName,
    ],
];

$elementId = $element->Add($elementFields);

if (!$elementId) {
    jsonResponse(
        false,
        'Не удалось создать элемент инфоблока: ' . $element->LAST_ERROR,
        [],
        500
    );
}

jsonResponse(
    true,
    'Форма успешно отправлена.',
    [
        'elementId' => (int)$elementId,
        'departmentName' => (string)$department['NAME'],
        'departmentHead' => $managerName,
    ]
);
