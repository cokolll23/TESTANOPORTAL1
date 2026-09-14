<?php

namespace Lab\EventsHandlers;

use Bitrix\Main\Loader;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Iblock\IblockTable;
use Lab\Helpers\IblockHelpers;

class SotrudnikiUpdateHandler
{
    // ID инфоблока "Сотрудники" (замените на реальный ID)
    // Укажите ваш ID инфоблока

    // Символьный код инфоблока (если ID может меняться)
    const IBLOCK_CODE = 'sotrudniki';

    // Массив для хранения старых значений
    private static $oldValues = [];
    private static $newValues = [];

    // Флаг для предотвращения зацикливания (если внутри будет вызов Update)
    private static $isProcessing = false;

    /**
     * Шаг 1: Сохраняем старые значения свойств до обновления
     */
    public static function onBeforeUpdate(&$arFields)
    {
        $iblock_id = IblockHelpers::getIblockIdByCode('sotrudniki');
        // Проверяем, что обновляется нужный инфоблок
        if ($arFields['IBLOCK_ID'] !=  $iblock_id) {
            return;
        }

        // Если это не реальное обновление (сбой), выходим
        if (empty($arFields['ID'])) {
            return;
        }

        // Загружаем модуль инфоблоков
        if (!\CModule::IncludeModule('iblock')) {
            return;
        }



        // Получаем текущие (старые) значения свойств из БД
        $res = \CIBlockElement::GetProperty(
            $iblock_id,
            $arFields['ID'],
            [],
            []
        );

        while ($prop = $res->Fetch()) {
            // Сохраняем значение в статический массив по коду свойства
            // Используем VALUE (для строк) или VALUE_ENUM (для списков)
            self::$oldValues[$prop['CODE']] = $prop['VALUE'];
        }

        // Логирование для отладки (опционально)
        //AddMessage2Log('Sotrudniki: Сохранены старые значения для ID ' . $arFields['ID']);
        $log = date('Y-m-d H:i:s') . ' OnAfterIBlockElementUpdateHandler ' . print_r(self::$oldValues, true);
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/logOldVals.txt', $log . PHP_EOL, FILE_APPEND);

        return self::$oldValues;

    }

    /**
     * Шаг 2: Сравниваем и отправляем письмо после обновления
     */
    public static function onAfterUpdate(&$arFields)
    {
        // Защита от зацикливания
        if (self::$isProcessing) {
            return;
        }
        $iblock_id = IblockHelpers::getIblockIdByCode('sotrudniki');
        // Проверяем, что обновление прошло успешно и это нужный инфоблок
        if ($arFields['RESULT'] !== true || $arFields['IBLOCK_ID'] != $iblock_id) {
            return;
        }

        // Проверяем, что у нас есть старые данные для сравнения
        if (empty(self::$oldValues)) {
            return;
        }

        // Указываем код свойства, которое нужно отслеживать
        // ЗАМЕНИТЕ 'KOLICHESTVO' на реальный символьный код свойства
        $targetPropertyCode = 'KOLICHESTVO';
        $newValue = null;
        $oldValue = null;

        // Получаем новое значение свойства
        if (!empty($arFields['PROPERTY_VALUES'])) {


            // Загружаем модуль инфоблоков
            if (!\CModule::IncludeModule('iblock')) {
                return;
            }

            // Получаем текущие (старые) значения свойств из БД
            $res = \CIBlockElement::GetProperty(
                $iblock_id,
                $arFields['ID'],
                [],
                []
            );

            while ($prop = $res->Fetch()) {
                // Сохраняем значение в статический массив по коду свойства
                // Используем VALUE (для строк) или VALUE_ENUM (для списков)
                $newValues[$prop['CODE']] = $prop['VALUE'];
            }
            foreach (self::$oldValues as $propCode => $propValue) {

                if ($propValue < $newValues[$propCode]) {
                    $arBoolMore = array();
                    $arBoolMore[] = 1;

                    Loader::includeModule('iblock');

                    $property = PropertyTable::getList([
                        'select' => ['ID', 'NAME'],
                        'filter' => [
                            '=IBLOCK_ID' => $arFields['IBLOCK_ID'],
                            '=CODE' => $propCode,
                        ],
                        'limit' => 1,
                    ])->fetch();
                    $propertyName = $property['NAME'] ?? null;
                    $arDiff[$propCode] = [
                        'element_code' => $arFields['CODE'],
                        'name' => $propertyName,
                        'old' => $propValue,
                        'new' => $newValues[$propCode]
                    ];

                    $message .= $propertyName . ':' . PHP_EOL . 'Старое значение ' . '-' . $propValue . PHP_EOL
                        . 'Новое значение -' . $newValues[$propCode] . PHP_EOL . PHP_EOL;

                }
            }

            // Заголовки для корректной кодировки
            /* $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
             $headers .= "Reply-To: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
             $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
             $headers .= "MIME-Version: 1.0\r\n";*/
            if (!empty($arBoolMore)) {

                //$email = $arFields['CODE'];
                $email = 'cavjob@ya.ru';
                $subject = 'Уведомление об изменении показателя';
                $log1 = date('Y-m-d H:i:s') . ' OnAfterIBlockElementUpdateHandler ' . print_r($arBoolMore, true);
                $log = date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL . ' Вам начислено в Магазине бонусов баллы : ' . PHP_EOL . PHP_EOL . $message;
                file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/logDiff.txt', $log . PHP_EOL, FILE_APPEND);

                mail($email, $subject, $log);
            }
        }
    }

}