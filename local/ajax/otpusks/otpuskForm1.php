<?php

define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_CHECK', true);

require $_SERVER['DOCUMENT_ROOT']
    . '/bitrix/modules/main/include/prolog_before.php';

require_once $_SERVER['DOCUMENT_ROOT']
    . '/local/php_interface/include/otpusk_helpers.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

function otpuskJson(array $data, int $status = 200): void
{
    http_response_code($status);

    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
    );

    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');

    otpuskJson([
        'success' => false,
        'message' => 'Разрешены только POST-запросы.',
    ], 405);
}

if (!$USER->IsAuthorized()) {
    otpuskJson([
        'success' => false,
        'message' => 'Необходимо авторизоваться.',
    ], 401);
}

if (!check_bitrix_sessid()) {
    otpuskJson([
        'success' => false,
        'message' => 'Сессия истекла. Обновите страницу.',
    ], 403);
}

try {
    // Массив вместо строкового параметра тоже считаем ошибкой.
    foreach (['DEPARTMENT', 'DATE_FROM', 'DATE_TO'] as $field) {
        if (
            !isset($_POST[$field])
            || !is_string($_POST[$field])
        ) {
            throw new InvalidArgumentException(
                'Не заполнены обязательные поля.'
            );
        }
    }

    $departmentRaw = trim($_POST['DEPARTMENT']);

    if (!preg_match('/^[1-9][0-9]*$/D', $departmentRaw)) {
        throw new InvalidArgumentException(
            'Указан некорректный департамент.'
        );
    }

    $departmentId = (int)$departmentRaw;
    $dateFrom = otpuskParseDate(trim($_POST['DATE_FROM']));
    $dateTo = otpuskParseDate(trim($_POST['DATE_TO']));

    if ($dateTo < $dateFrom) {
        throw new InvalidArgumentException(
            'Дата окончания не может быть раньше даты начала.'
        );
    }

    // ID пользователя никогда не берём из POST.
    $currentUserId = (int)$USER->GetID();

    // Повторно получаем актуальные данные на сервере.
    $context = otpuskContext($currentUserId);

    if (!isset($context['departments'][$departmentId])) {
        throw new InvalidArgumentException(
            'Выбранный департамент не найден или недоступен.'
        );
    }

    $department = $context['departments'][$departmentId];

    // Руководителя тоже не берём из POST.
    $headId = (int)$department['headId'];

    if ($headId <= 0) {
        throw new InvalidArgumentException(
            'У выбранного департамента не назначен руководитель.'
        );
    }

    /*
     * Если выбирать можно ТОЛЬКО собственные департаменты,
     * раскомментируйте эту проверку:
     *
     * if (!in_array(
     *     $departmentId,
     *     $context['userDepartmentIds'],
     *     true
     * )) {
     *     throw new InvalidArgumentException(
     *         'Можно выбрать только департамент из вашего профиля.'
     *     );
     * }
     */

    $planIblockId = otpuskIblockId('otpusks_plan');

    // CIBlockElement::Add сам по себе не заменяет проверку прав.
    if (!CIBlockElementRights::UserHasRightTo(
        $planIblockId,
        0,
        'element_add'
    )) {
        otpuskJson([
            'success' => false,
            'message' => 'Нет прав на добавление заявки.',
        ], 403);
    }

    $fio = $context['user']['fio'];

    $name = sprintf(
        'Отпуск: %s, %s — %s',
        $fio,
        $dateFrom->format('d.m.Y'),
        $dateTo->format('d.m.Y')
    );

    $element = new CIBlockElement();

    $elementId = $element->Add([
        'IBLOCK_ID' => $planIblockId,
        'NAME' => mb_substr($name, 0, 255),
        'ACTIVE' => 'Y',
        'CREATED_BY' => $currentUserId,
        'MODIFIED_BY' => $currentUserId,

        'PROPERTY_VALUES' => [
            'FIO' => $currentUserId,
            'DEPARTMENT' => $departmentId,
            'DEPARTMENT_HEAD' => $headId,

            // Значения в формате даты текущего сайта Битрикс.
            'DATE_FROM' => ConvertTimeStamp(
                $dateFrom->getTimestamp(),
                'SHORT'
            ),
            'DATE_TO' => ConvertTimeStamp(
                $dateTo->getTimestamp(),
                'SHORT'
            ),
        ],
    ]);

    if (!$elementId) {
        // Подробности сохраняем в журнал, а не отдаём браузеру.
        AddMessage2Log(
            'Ошибка добавления отпуска: ' . $element->LAST_ERROR,
            'otpusk_form'
        );

        otpuskJson([
            'success' => false,
            'message' => 'Не удалось сохранить заявку. Обратитесь к администратору.',
        ], 500);
    }

    otpuskJson([
        'success' => true,
        'id' => (int)$elementId,
        'message' => 'Заявка успешно сохранена.',
    ]);

} catch (InvalidArgumentException $e) {
    otpuskJson([
        'success' => false,
        'message' => $e->getMessage(),
    ], 422);

} catch (Throwable $e) {
    AddMessage2Log(
        $e->getMessage(),
        'otpusk_form'
    );

    otpuskJson([
        'success' => false,
        'message' => 'Ошибка обработки заявки. Обратитесь к администратору.',
    ], 500);
}