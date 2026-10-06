<?php

namespace Lab\EventsHandlers;


use Bitrix\Sale;
use Bitrix\Catalog;
use Bitrix\Main\Diag\Debug;
use Lab\Helpers\IblockHelpers as IH;
use Lab\Helpers\RecalculateScores as RS;

class SaleEventsHandlers
{
    // todo  уменьшение количества товара при оформлении заказа битрикс
    public static function onSaleOrderSavedHandler(\Bitrix\Main\Event $event)
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
    }

    public static function onBeforeOrderAdd(&$arFields)
    {
        $orderPrice = $arFields['PRICE'];
        $USER_EMAIL = $arFields['USER_EMAIL'];
        $iblockId = IH::getIblockIdByCode('sotrudniki');
        $orderPrice = $arFields['PRICE'];
        $elementCode = $arFields['USER_EMAIL'];

        \Bitrix\Main\Loader::includeModule("iblock");

        $res = \CIBlockElement::GetList(
            array(),
            array(
                'IBLOCK_ID' => $iblockId,
                'CODE' => $elementCode,
                'ACTIVE' => 'Y'
            ),
            false,
            false,
            array('ID', 'NAME', 'PROPERTY_COLUMN33')
        );

        $column33Value = '';
        if ($element = $res->Fetch()) {
            $column33Value = $element['PROPERTY_COLUMN33_VALUE'];

        } else {
            $column33Value = 0;
        }
        if ($column33Value >= $orderPrice) {
            $diffRes = 1;
        } else {
            $diffRes = 0;
        }

        if ($diffRes != 1) {
            global $APPLICATION;
            $APPLICATION->ThrowException('Не можете заказать на эту сумму, Уменьшите количество товаров в блоке выше со списком товаров .  Стоимость заказа - ' . $orderPrice . ' М-баллов , у Вас в наличии - ' . $column33Value . ' М-баллов');
            return false;
        }

        /*$log = date('Y-m-d H:i:s') . ' OnAfterIBlockElementUpdateHandler ' . print_r($arFields, true);
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);*/

    }

    public static function onStatusChange(Bitrix\Main\Event $event)
    {
        /*$log = date('Y-m-d H:i:s') . ' onStatusChange' . print_r($event, true);
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
        Bitrix\Main\Diag\Debug::dumpToFile($log, '$event onStatusChange' . date('d-m-Y; H:i:s'));*/

    }

    // todo  при отмене заказа возврат покупателю баллов и товарам из заказа кол-ва
    public static function OnSaleOrderSavedHandler1(\Bitrix\Main\Event $event)
    {
        $order = $event->getParameter("ENTITY");
        $STATUS_ID = $order->getField("STATUS_ID");// N- Принят F- Выполнен

        if ($STATUS_ID == 'N' || $STATUS_ID == 'F') {

            // добавление заказа и емеил  покупателю
            $orderId = $order->getId();
            $price = $order->getPrice();
            $currency = $order->getCurrency();

            $properties = $order->getPropertyCollection();

            $nameProperty = $properties->getPayerName();
            $emailProperty = $properties->getUserEmail();
            $phoneProperty = $properties->getPhone();

            $buyer = [
                'USER_ID' => $order->getUserId(),
                'NAME' => $nameProperty ? $nameProperty->getValue() : null,
                'EMAIL' => $emailProperty ? $emailProperty->getValue() : null,
                'PHONE' => $phoneProperty ? $phoneProperty->getValue() : null,
                '$orderId' => $orderId ? $orderId : null,
                '$price' => $price ? $price : null,
                '$currency' => $currency ? $currency : null,
            ];

            $subject = "=?UTF-8?B?" . base64_encode("Магазин бонусов форма Запись М-баллов") . "?=";

            $to = $buyer['EMAIL'];

            if ($STATUS_ID == 'N') {
                $strAction = 'принят';
                $orderLink = 'Перейти к заказам https://corp-portal.welcome.moscow/shop-bonus/personal/orders/';
            }
            if ($STATUS_ID == 'F') {
                $strAction = 'выполнен';
                $orderLink = '';
            }
            $message = <<<HTML

Письмо из магазина бонусов
Здравствуйте, {$buyer['NAME']} 
Ваш заказ № {$buyer['$orderId']}  {$strAction}.
Сумма заказа: {$buyer['$price']} 
Спасибо за покупку!

HTML;

            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=utf-8',
                'From: Магазин бонусов <ya@example.com>',
                'Reply-To: ответ@example.com',
                'X-Mailer: PHP/' . phpversion()
            ];
            if (mail($to, $subject, $message)) {
                echo "<h2 style='color: green;'>Письмо отправлено администратору</h2>";
            } else {
                echo "Ошибка отправки";
            }

            //$log = date('Y-m-d H:i:s') . ' OnAfterIBlockElementUpdateHandler ' . print_r($buyer, true);
            $log = date('Y-m-d H:i:s') . ' OnAfterIBlockElementUpdateHandler ' . $message;
            file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/log.txt', $log . PHP_EOL, FILE_APPEND);
        }

        if ($order->isCanceled() && $order->getField("STATUS_ID") != "D") {
            $order->setField("STATUS_ID", "D");

            if ($order) {
                $basket = $order->getBasket();
                $price = $basket->getPrice();
                // Получаем коллекцию свойств заказа
                $propertyCollection = $order->getPropertyCollection();
                $userId = $order->getUserId();

                $customerProperties = [];

                // Получаем email
                $emailProperty = $propertyCollection->getUserEmail();
                $orderPrice = $order->getPrice();

                $customerProperties['EMAIL'] = $emailProperty->getValue();
                $customerProperties['PRICE'] = $orderPrice;


                $iblockId = IH::getIblockIdByCode('sotrudniki');
                $propertyId = IH::getPropertyIdByCode('sotrudniki', 'COLUMN33');
                $elementCode = $customerProperties['EMAIL'];
                $propertyCode = 'COLUMN33';

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

                $COLUMN33_Value = $COLUMN33_Result['PROPERTY_' . $propertyCode . '_VALUE'] ?? null;
                $elementId = $COLUMN33_Result['ID'];

                $COLUMN33_ValueNew = (int)$COLUMN33_Value + (int)$customerProperties['PRICE'];

                $arPrices = [$COLUMN33_Value, $customerProperties['PRICE'], $COLUMN33_ValueNew];

                RS::getTotalScores('sotrudniki', $customerProperties['EMAIL']);
                // Устанавливаем значение свойства
                /* \CIBlockElement::SetPropertyValuesEx(
                     $elementId,
                     $iblockId,
                     array(
                         "COLUMN33" => $COLUMN33_ValueNew
                     )
                 );*/

                foreach ($basket as $i => $basketItem) {
                    $productName = $basketItem->getField('NAME');
                    $productId = $basketItem->getField('PRODUCT_ID');
                    $quantity = $basketItem->getField('QUANTITY');
                    $price = $basketItem->getPrice();

                    $arBasketInfo [$i]["NAME"] = $productName;
                    $arBasketInfo [$i]["productId "] = $basketItem->getField('PRODUCT_ID');
                    $arBasketInfo [$i]["quantity"] = $basketItem->getField('QUANTITY');
                    $arBasketInfo [$i]["price"] = $basketItem->getPrice();
                    $arBasketInfo [$i]["priceTotal"] = $basketItem->getPrice() * $basketItem->getField('QUANTITY');
                }
            } else {
                echo "Заказ не найден";
            }
// добавляем количество товара при отмене заказа
            foreach ($basket as $basketItem) {
                $productId = $basketItem->getProductId();
                $quantity = $basketItem->getQuantity();
                if (\Bitrix\Main\Loader::includeModule('catalog')) {
                    // Получаем текущие остатки
                    $productData = \CCatalogProduct::GetByID($productId);

                    if ($productData) {
                        $newQuantity = $productData['QUANTITY'] + $quantity;

                        // Обновляем общее количество
                        \CCatalogProduct::Update($productId, [
                            'QUANTITY' => $newQuantity
                        ]);
                    }
                }

            }

        }

    }


}