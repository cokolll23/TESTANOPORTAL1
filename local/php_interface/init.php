<?php
AddEventHandler("main", 'OnPageStart', 'setApplication');
function setApplication()
{
    include_once 'styles.php';
}
AddEventHandler("main", 'OnPageStart', 'addCss');
function addCss()
{
    Bitrix\Main\Page\Asset::getInstance()->addCss( '/bitrix/js/lab/ui/fonts/ony/ui.font.ony.css');
}
if (file_exists(__DIR__ . '/includes/pretty_print.php')) {
    require_once __DIR__ . '/includes/pretty_print.php';
}

if (file_exists(__DIR__ . '/src/autoloader.php')) {
    require_once __DIR__ . '/src/autoloader.php';
}
if (file_exists(__DIR__ . '/includes/pretty-print/pretty_print.php')) {
    require_once __DIR__ . '/includes/pretty-print/pretty_print.php';
}
use Lab\EventsHandlers\IblockEventsHandlers as EH;
use Lab\EventsHandlers\UserEventsHandlers as ueh;
use Lab\Helpers\IblockHelpers as IH;
use \Lab\Helpers\UsersHelpers as UH;
use Lab\Helpers\RecalculateScores as RS;

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;

//\Bitrix\Main\UI\Extension::load('lab.mainjs'); , BaryshevaAD1@mos.ru, StarenkoOG@mos.ru, PORT-communications@mos.ru
//CUtil::InitJSCore(array('jquery3', 'popup', 'ajax', 'date'));


\Bitrix\Main\UI\Extension::load('lab.functions');
\Bitrix\Main\UI\Extension::load('lab.idea');

$eventManager = \Bitrix\Main\EventManager::getInstance();

if (!CModule::IncludeModule("sale")) {
    return;
}
if (!CModule::IncludeModule("iblock")) {
    return;
}


/*AddEventHandler(
    'iblock',
    'OnBeforeIBlockElementUpdate',
    array('Lab\EventsHandlers\EmployeesPointsNotify', 'onBeforeUpdate')
);

AddEventHandler(
    'iblock',
    'OnAfterIBlockElementUpdate',
    array('Lab\EventsHandlers\EmployeesPointsNotify', 'onAfterUpdate')
);*/

// todo  уменьшение количества товара и итого баллов у юзера при оформлении заказа битрикс
// в SaleEventsHandlers не работает много переадресации
//$eventManager->addEventHandler('sale', 'OnSaleOrderSaved',['Lab\EventsHandlers\SaleEventsHandlers','onSaleOrderSavedHandler']);
$eventManager->addEventHandler('sale', 'OnSaleOrderSaved', 'OnSaleOrderSavedHandler');
function OnSaleOrderSavedHandler(\Bitrix\Main\Event $event)
{
    $order = $event->getParameter("ENTITY");
    $isNew = $event->getParameter("IS_NEW");
    if (!$isNew) {
        return;
    }
    $basket = $order->getBasket();

    foreach ($basket as $basketItem) {
        $productId = $basketItem->getProductId();
        $quantity = $basketItem->getQuantity();
        if (\Bitrix\Main\Loader::includeModule('catalog')) {
            // Получаем текущие остатки
            $productData = CCatalogProduct::GetByID($productId);

            if ($productData) {
                $newQuantity = $productData['QUANTITY'] - $quantity;

                // Обновляем общее количество
                CCatalogProduct::Update($productId, [
                    'QUANTITY' => $newQuantity
                ]);
            }
        }

    }

    if (in_array($order->getField('STATUS_ID'), array('N'))) {

        $ORDER = \Bitrix\Sale\Order::load($order->getId());

        if (!$ORDER) {
            return;
        }

        // Получаем коллекцию свойств заказа
        $propertyCollection = $ORDER->getPropertyCollection();
        $userId = $ORDER->getUserId();

        $customerProperties = [];

        // Получаем email
        $emailProperty = $propertyCollection->getUserEmail();
        $orderPrice = $ORDER->getPrice();

        $customerProperties['EMAIL'] = $emailProperty->getValue();
        $customerProperties['PRICE'] = $orderPrice;
        $elementCode = $customerProperties['EMAIL'];
        $propertyCode = 'COLUMN33';

        $iblockId = IH::getIblockIdByCode('sotrudniki');
        $propertyId = IH::getPropertyIdByCode('sotrudniki', 'COLUMN33');
        $elementId = IH::getIblockElementInfo('sotrudniki', $elementCode)['ID'];

        $COLUMN33_Result = \CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $iblockId,
                'CODE' => $elementCode,
                'ACTIVE' => 'Y'
            ],
            false,
            false,
            [
                'ID',
                'NAME',
                'PROPERTY_' . $propertyCode
            ]
        )->GetNext();

        $res = \CIBlockElement::GetProperty($iblockId, $elementId, "sort", "asc", array());
        while ($ob = $res->GetNext()) {
            if ($ob['VALUE'] > 0 && $ob['CODE'] != 'COLUMN33') {
                $propsNotZero[] = $ob;
            }
        }

        $COLUMN33_Value = $COLUMN33_Result['PROPERTY_' . $propertyCode . '_VALUE'] ?? null;
        $elementId = $COLUMN33_Result['ID'];

        $COLUMN33_ValueNew = (int)$COLUMN33_Value - (int)$customerProperties['PRICE'];

        $arPrices = [$COLUMN33_Value, $customerProperties['PRICE'], $COLUMN33_ValueNew];

        // Устанавливаем значение свойства
        /*\CIBlockElement::SetPropertyValuesEx(
            $elementId,
            $iblockId,
            array(
                "COLUMN33" => $COLUMN33_ValueNew,
                "COLUMN34" => $elementPropColumn34Val
            )
        );*/
        RS::getTotalScores('sotrudniki',  $elementCode ) ;

        /*$log = date('Y-m-d H:i:s') . ' onStatusChange' . print_r($propsNotZero, true);
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
        Bitrix\Main\Diag\Debug::dumpToFile($log, '$event onStatusChange' . date('d-m-Y; H:i:s'));*/
    }
}
;

// todo  при Отмене заказа из личного кабинета покупателя изменяет статус на D
$eventManager->addEventHandler("sale", "OnSaleOrderSaved", ['Lab\EventsHandlers\SaleEventsHandlers','OnSaleOrderSavedHandler1']);
//todo Отменяем создание заказа до его создания при цена заказа выше определенной цифры https://chat.deepseek.com/a/chat/s/6e829ee6-c90c-46b8-a2f5-dbab70924b95
AddEventHandler("sale", "OnBeforeOrderAdd", ['Lab\EventsHandlers\SaleEventsHandlers', 'onBeforeOrderAdd']);

// todo регистрация пользователя не из АНО после добавления в иб ТАБЛИЦА БОНУСОВ в группу Все покупатели [ 24 CRM_SHOP_BUYER]
// todo удаление пользователя не из АНО после добавления в иб ТАБЛИЦА БОНУСОВ в группу Все покупатели [ 24 CRM_SHOP_BUYER]
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", ['Lab\EventsHandlers\IblockEventsHandlers', 'onAfterIBlockElementAddHandler']);
// todo если из АНО onAfterIBlockElementAddHandlerStateHistoryIB
//$eventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", ['Lab\EventsHandlers\IblockEventsHandlers', 'onAfterIBlockElementAddHandlerStateHistoryIB']);

// todo сделать хендлер при изменении элемента складывать значения свойств
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", ['Lab\EventsHandlers\IblockEventsHandlers', 'onAfterIBlockElementUpdateHandler']);
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", ['Lab\EventsHandlers\IblockEventsHandlers', 'onAfterIBlockElementAddHandlerSendMail']);

//$eventManager->addEventHandler("main", "OnAfterUserAdd", ['Lab\EventsHandlers\UserEventsHandlers', 'onAfterUserAddLogs']);


/*addEventHandler("main", "OnAfterUserAdd", ['Lab\EventsHandlers\UserEventsHandlers', 'onAfterUserAddLogs']);
addEventHandler("main", "OnAfterUserUpdate", ['Lab\EventsHandlers\UserEventsHandlers', 'onAfterUserUpdateLogs']);*/

// todo логирование Проверить какие данные идут из /upload/1c_intranet
//     * IB График отсутствий id=1 CODE = "absence" Обработчик события добавления элемента (OnAfterIBlockElementUpdate)
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", ['Lab\EventsHandlers\IblockEventsHandlers', 'OnAfterAbsenceAddHandler']);
// todo логирование Проверить какие данные идут из /upload/1c_intranet  IB График отсутствий id=1 CODE = "absence" Обработчик события обновления элемента (OnAfterIBlockElementUpdate)
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", ['Lab\EventsHandlers\IblockEventsHandlers', 'OnAfterAbsenceUpdateHandler']);


// отправка емеил пользователям при начислении  М баллов
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", ["Lab\EventsHandlers\SotrudnikiUpdateHandler", "onBeforeUpdate"]);
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", ["Lab\EventsHandlers\SotrudnikiUpdateHandler", "onAfterUpdate"]);
