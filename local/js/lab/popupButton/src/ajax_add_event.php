<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;




// Получаем данные

/*$message = trim($_POST['message'] ?? '');
$eventId = trim($_POST['eventId'] ?? '');
$userId = trim($_POST['userId'] ?? '');*/

if ($_POST['eventId']!=''){
    $eventId =$_POST['eventId'];
}

$arResults = array(
    //'MESSAGE' => $message,
    'EVENT_ID' => $eventId,
    //'USER_ID' => $userId
);

/*try {
    // Подключаем модуль инфоблоков
    if (!Loader::includeModule('iblock')) {
        throw new Exception('Модуль инфоблоков не установлен');
    }

    // Получаем ID инфоблока по символьному коду
    $iblockId = 0;
    $iblockRes = \Bitrix\Iblock\Iblock::getList([], ['CODE' => 'calendar_event_dscr']);
    if ($iblock = $iblockRes->fetch()) {
        $iblockId = $iblock['ID'];
    }

    if (!$iblockId) {
        throw new Exception('Инфоблок не найден');
    }

    // Подготавливаем поля
    $fields = [
        'IBLOCK_ID' => $iblockId,
        'NAME' => htmlspecialchars($name),
        'ACTIVE' => 'Y',
        'DATE_ACTIVE_FROM' => $date,
        'PREVIEW_TEXT' => htmlspecialchars($description),
        'PREVIEW_TEXT_TYPE' => 'text',
    ];

    // Создаем элемент
    $element = new \CIBlockElement();
    $elementId = $element->Add($fields);

    if ($elementId) {
        $response['success'] = true;
        $response['message'] = 'Событие успешно добавлено';
        $response['element_id'] = $elementId;

        // Дополнительно можно вернуть данные для обновления списка
        $response['data'] = [
            'id' => $elementId,
            'name' => $name,
            'date' => $date,
            'description' => $description
        ];
    } else {
        throw new Exception($element->LAST_ERROR);
    }

} catch (Exception $e) {
    $response['message'] = 'Ошибка: ' . $e->getMessage();
}*/

echo json_encode($arResults);
//echo 'ok';
die();


