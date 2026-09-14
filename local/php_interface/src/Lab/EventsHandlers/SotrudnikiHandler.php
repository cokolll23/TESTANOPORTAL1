<?php

namespace Lab\EventsHandlers;

class SotrudnikiHandler
{
    // Флаг для предотвращения зацикливания
    protected static $handlerDisallow = false;

    public static function OnUpdate(&$arFields)
    {
        // Защита от рекурсии [citation:8]
        if (self::$handlerDisallow) {
            return;
        }

        // Проверяем успешность обновления [citation:1]
        if (!$arFields["RESULT"]) {
            return;
        }

        // Проверяем, что это нужный инфоблок
        $iblockCode = self::getIBlockCode($arFields["IBLOCK_ID"]);
        if ($iblockCode !== "sotrudniki") {
            return;
        }

        self::$handlerDisallow = true;

        CModule::IncludeModule("iblock");

        // Получаем новый email из свойства элемента (символьный код элемента используется как email)
        $elementCode = $arFields["CODE"];

        // Получаем список свойств инфоблока
        $rsProps = CIBlockProperty::GetList(
            array("SORT" => "ASC"),
            array("IBLOCK_ID" => $arFields["IBLOCK_ID"], "ACTIVE" => "Y")
        );

        $changedProps = array();

        while ($arProp = $rsProps->Fetch()) {
            $propCode = $arProp["CODE"];

            // Получаем старое значение свойства из базы данных
            $oldValue = self::getOldPropertyValue($arFields["ID"], $propCode);

            // Получаем новое значение из массива arFields
            $newValue = isset($arFields["PROPERTY_VALUES"][$arProp["ID"]])
                ? $arFields["PROPERTY_VALUES"][$arProp["ID"]]
                : null;

            // Сравниваем значения и вычисляем разницу
            if ($newValue !== null && $oldValue !== null) {
                $oldNum = floatval($oldValue);
                $newNum = floatval($newValue);

                if ($newNum != $oldNum) {
                    $diff = $newNum - $oldNum;
                    $changedProps[] = array(
                        "NAME" => $arProp["NAME"],
                        "CODE" => $propCode,
                        "DIFF" => $diff
                    );
                }
            }
        }

        // Если есть изменения, отправляем письмо
        if (!empty($changedProps)) {
            self::sendNotification($elementCode, $changedProps);
        }

        self::$handlerDisallow = false;
    }

    /**
     * Получение кода инфоблока по ID
     */
    protected static function getIBlockCode($iblockId)
    {
        $rsIBlock = CIBlock::GetList(array(), array("ID" => $iblockId));
        if ($arIBlock = $rsIBlock->Fetch()) {
            return $arIBlock["CODE"];
        }
        return "";
    }

    /**
     * Получение старого значения свойства из БД
     */
    protected static function getOldPropertyValue($elementId, $propCode)
    {
        $dbProps = CIBlockElement::GetProperty(
            false,
            $elementId,
            array("sort" => "asc"),
            array("CODE" => $propCode)
        );

        if ($arProp = $dbProps->Fetch()) {
            return $arProp["VALUE"];
        }
        return null;
    }

    /**
     * Отправка уведомления
     */
    protected static function sendNotification($email, $changedProps)
    {
        // Формируем текст письма
        $propsText = "";
        foreach ($changedProps as $prop) {
            $sign = $prop["DIFF"] > 0 ? "+" : "";
            $propsText .= $prop["CODE"] . ": " . $sign . $prop["DIFF"] . "\n";
        }

        $arEventFields = array(
            "EMAIL_TO" => $email,
            "PROPS_LIST" => $propsText,
            "MESSAGE" => "Вам начислили баллы в Магазине баллов"
        );

        // Отправка через старое ядро [citation:2]
        CEvent::Send("SOTRUDNIKI_BALLS", "s1", $arEventFields);

        // Или через D7 [citation:5]
        // \Bitrix\Main\Mail\Event::send(array(
        //     "EVENT_NAME" => "SOTRUDNIKI_BALLS",
        //     "LID" => "s1",
        //     "C_FIELDS" => $arEventFields
        // ));
    }
}