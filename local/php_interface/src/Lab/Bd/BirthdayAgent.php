<?php

namespace Lab\Bd;

use Lab\BirthdayAgent\Date;
use Lab\BirthdayAgent\UserTable;

class BirthdayAgent
{
    private const IBLOCK_CODE = 'sotrudniki';

    public static function updatePropsEls()
    {

        $arElements = self::getElsIBSotrudnikiByUserEmail();// получить элементы из ИБ sotrudniki (таблица баллов) по Символьному коду

        if (!empty($arElements)) {
            $iblockId = self::getIblockId(self::IBLOCK_CODE);

            foreach ($arElements as $i => $element) {
                $COLUMN37New = $element["COLUMN37"] + 5;
                $COLUMN33New = $element["COLUMN33"] + 5;
                $propertyValues = array(
                    "COLUMN37" => $COLUMN37New,
                    "COLUMN33" => $COLUMN33New,
                );

                \CIBlockElement::SetPropertyValuesEx($element["ID"], $iblockId, $propertyValues);// установить новые значения св-ств
                // с кодами COLUMN33 (баллы за ДР) COLUMN37 (Итого)

                $arPropsValues=self::getPropsValues($iblockId, $element["ID"],$element["COLUMN37"],$element["COLUMN33"]);
                // получить новые значения св-ств
                // с кодами COLUMN33 (баллы за ДР) COLUMN37 (Итого)

                $log = date('Y-m-d H:i:s') . ' OnAfterIBlockElementUpdateHandler ' . print_r($arPropsValues, true);
                file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/logBirthDays.txt', $log . PHP_EOL, FILE_APPEND);

                // \Bitrix\Main\Diag\Debug::dumpToFile($log, '$event onStatusChange' . date('d-m-Y; H:i:s'));

              // pretty_print( self::getPropsValues($iblockId, $element["ID"]));

            }
        }
        return "\Lab\Bd\BirthdayAgent::updatePropsEls();";// обязательно возвращать для Агента
    }
    /**
     * Получает элементы иб sotrudniki соответствующие CODE иб  -> EMAIL USER
     */
    public static function getElsIBSotrudnikiByUserEmail()
    {
        $birthDaysUsers = self::getBDUsers();
        // Подключаем модуль инфоблоков
        \Bitrix\Main\Loader::includeModule('iblock');
        $iblockId = self::getIblockId('sotrudniki');


        foreach ($birthDaysUsers as $elementCode) {
            // Параметры фильтрации
            $arFilter = [
                'IBLOCK_ID' => $iblockId,
                '=CODE' => $elementCode, // Ищем по символьному коду
                'ACTIVE' => 'Y', // Только активные элементы (опционально)
            ];

            // Параметры сортировки
            $arOrder = ['SORT' => 'ASC'];

            // Какие поля выбрать
            $arSelect = ['ID', 'NAME', 'CODE', 'PROPERTY_COLUMN33', 'PROPERTY_COLUMN37']; // '*' для всех свойств

            // Выполняем запрос
            $res = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

            // Получаем результат
            if ($arElement = $res->GetNext()) {
                // Элемент найден
                $arElements[$arElement['CODE']]['COLUMN37'] = $arElement['PROPERTY_COLUMN37_VALUE'];
                $arElements[$arElement['CODE']]['COLUMN33'] = $arElement['PROPERTY_COLUMN33_VALUE'];
                $arElements[$arElement['CODE']]['ID'] = $arElement['ID'];

            } else {
                echo 'Элемент с кодом "' . $elementCode . '" в Таблице баллов не найден.';
            }
        }


        return $arElements;
    }

    public static function getBDUsers()
    {
        // Получаем текущую дату в формате "месяц-день"
        $today = date('m-d');

        $arFilter = [
            "PERSONAL_BIRTHDAY_DATE" => $today,
            "ACTIVE" => 'Y',
            //"=EMAIL" => 'Sokolovav26@mos.ru',
        ];

        $rsUsers = \CUser::GetList(
            ($by = "id"),
            ($order = "desc"),
            $arFilter
        );

        $birthdayUsers = [];
        while ($arUser = $rsUsers->Fetch()) {
            $birthdayUsers[$arUser['EMAIL']] = $arUser['EMAIL'];
        }
        return $birthdayUsers;
    }

    /**
     * Получает ID инфоблока по коду
     */
    public static function getIblockId($iblockCode)
    {
        $iblock = \CIBlock::GetList([], ['CODE' => $iblockCode])->Fetch();
        return $iblock ? $iblock['ID'] : false;
    }


    /**
     * Получает новые  значения  свойств баллы за ДР
     */
    public static function getPropsValues($iblockId, $elementID, $COLUMN37Old, $COLUMN33Old)
    {
        // Параметры фильтрации
        $arFilter = [
            'ID' => $elementID,
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y', // Только активные элементы (опционально)
        ];

        // Параметры сортировки
        $arOrder = ['SORT' => 'ASC'];

        // Какие поля выбрать
        $arSelect = ['ID', 'NAME', 'CODE', 'PROPERTY_COLUMN33', 'PROPERTY_COLUMN37']; // '*' для всех свойств

        // Выполняем запрос
        $res = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        // Получаем результат
        if ($arElement = $res->GetNext()) {
            // Элемент найден
            $arElements[$arElement['ID']]['COLUMN37'] = $arElement['PROPERTY_COLUMN37_VALUE'];
            $arElements[$arElement['ID']]['COLUMN33'] = $arElement['PROPERTY_COLUMN33_VALUE'];
            $arElements[$arElement['ID']]['COLUMN37Old'] = $COLUMN37Old;
            $arElements[$arElement['ID']]['COLUMN33Old'] = $COLUMN33Old;
            $arElements[$arElement['CODE']]['ID'] = $elementID;

        } else {
            echo 'Элемент с кодом "' . $elementID . '" в Таблице баллов не найден.';
        }
        return $arElements;
    }


}