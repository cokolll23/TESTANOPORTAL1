<?php

namespace Lab\EventsHandlers;

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
class EmployeesPointsNotify
{
    /**
     * Значения свойств до обновления.
     */
    private static $beforeValues = array();

    /**
     * Сохраняем значения до обновления элемента.
     */
    public static function onBeforeUpdate(&$arFields)
    {
        if (!Loader::includeModule('iblock')) {
            return;
        }

        $elementId = isset($arFields['ID']) ? (int)$arFields['ID'] : 0;

        if ($elementId <= 0) {
            return;
        }

        $element = self::getElement($elementId);

        if (!$element || $element['IBLOCK_CODE'] !== 'sotrudniki') {
            return;
        }

        self::$beforeValues[$elementId] = array(
            'IBLOCK_ID' => (int)$element['IBLOCK_ID'],
            'PROPERTIES' => self::getNumericProperties(
                (int)$element['IBLOCK_ID'],
                $elementId
            ),
        );
    }

    /**
     * Сравниваем значения после обновления и отправляем письмо.
     */
    public static function onAfterUpdate(&$arFields)
    {
        if (!Loader::includeModule('iblock')) {
            return;
        }

        $elementId = isset($arFields['ID']) ? (int)$arFields['ID'] : 0;

        if ($elementId <= 0) {
            return;
        }

        if (isset($arFields['RESULT']) && $arFields['RESULT'] === false) {
            unset(self::$beforeValues[$elementId]);
            return;
        }

        if (!isset(self::$beforeValues[$elementId])) {
            return;
        }

        $beforeData = self::$beforeValues[$elementId];
        unset(self::$beforeValues[$elementId]);

        $element = self::getElement($elementId);

        if (!$element || $element['IBLOCK_CODE'] !== 'sotrudniki') {
            return;
        }

        $iblockId = (int)$beforeData['IBLOCK_ID'];

        $oldProperties = $beforeData['PROPERTIES'];
        $newProperties = self::getNumericProperties($iblockId, $elementId);

        $changes = array();

        foreach ($newProperties as $propertyCode => $newProperty) {
            $newValue = (float)$newProperty['VALUE'];

            $oldValue = isset($oldProperties[$propertyCode])
                ? (float)$oldProperties[$propertyCode]['VALUE']
                : 0;

            $difference = round($newValue - $oldValue, 10);

            // В письмо включаются только свойства, значение которых увеличилось.
            if ($difference > 0) {
                $changes[] = array(
                    'CODE' => $propertyCode,
                    'NAME' => $newProperty['NAME'],
                    'DIFFERENCE' => $difference,
                );
            }
        }

        if (empty($changes)) {
            return;
        }

        /*
         * Получаем email:
         * 1. если CODE элемента содержит email, используем его;
         * 2. иначе ищем пользователя Битрикс, у которого LOGIN равен CODE элемента.
         */
        $email = self::getRecipientEmail($element['CODE']);

        if (!$email) {
            AddMessage2Log(
                sprintf(
                    'Не найден email для элемента ID=%d, CODE=%s',
                    $elementId,
                    $element['CODE']
                ),
                'EMPLOYEE_POINTS_EMAIL'
            );

            return;
        }

        $changeLines = array();

        foreach ($changes as $change) {
            $changeLines[] = sprintf(
                'Код свойства: %s; начислено: +%s',
                $change['CODE'],
                self::formatNumber($change['DIFFERENCE'])
            );
        }

        $changesText = implode("\n", $changeLines);

        $siteIds = self::getIblockSiteIds($iblockId);

        $sendResult = CEvent::SendImmediate(
            'EMPLOYEE_POINTS_ACCRUED',
            $siteIds,
            array(
                'EMAIL_TO' => $email,
                'ELEMENT_ID' => $elementId,
                'ELEMENT_NAME' => $element['NAME'],
                'ELEMENT_CODE' => $element['CODE'],
                'CHANGES' => $changesText,
            ),
            'N'
        );

        if (!$sendResult) {
            AddMessage2Log(
                sprintf(
                    'Ошибка отправки письма для элемента ID=%d на адрес %s',
                    $elementId,
                    $email
                ),
                'EMPLOYEE_POINTS_EMAIL'
            );
        }
    }

    /**
     * Получает элемент и символьный код его инфоблока.
     */
    private static function getElement($elementId)
    {
        $elementResult = CIBlockElement::GetList(
            array(),
            array(
                '=ID' => $elementId,
            ),
            false,
            false,
            array(
                'ID',
                'IBLOCK_ID',
                'NAME',
                'CODE',
            )
        );

        $element = $elementResult->Fetch();

        if (!$element) {
            return false;
        }

        $iblock = CIBlock::GetByID((int)$element['IBLOCK_ID'])->Fetch();

        if (!$iblock) {
            return false;
        }

        $element['IBLOCK_CODE'] = (string)$iblock['CODE'];

        return $element;
    }

    /**
     * Получает все числовые свойства элемента.
     *
     * Для множественного числового свойства сравнивается сумма его значений.
     */
    private static function getNumericProperties($iblockId, $elementId)
    {
        $result = array();

        $propertyResult = CIBlockElement::GetProperty(
            $iblockId,
            $elementId,
            array(
                'sort' => 'asc',
                'id' => 'asc',
            ),
            array(
                'ACTIVE' => 'Y',
            )
        );

        while ($property = $propertyResult->Fetch()) {
            $propertyCode = trim((string)$property['CODE']);

            if (
                $propertyCode === ''
                || $property['PROPERTY_TYPE'] !== 'N'
            ) {
                continue;
            }

            if (!isset($result[$propertyCode])) {
                $result[$propertyCode] = array(
                    'NAME' => (string)$property['NAME'],
                    'VALUE' => 0,
                );
            }

            $result[$propertyCode]['VALUE'] += self::normalizeNumber(
                $property['VALUE']
            );
        }

        return $result;
    }

    /**
     * Определяет email по символьному коду элемента.
     */
    private static function getRecipientEmail($elementCode)
    {
        $elementCode = trim((string)$elementCode);

        if ($elementCode === '') {
            return false;
        }

        // Вариант 1: символьный код элемента сам является email.
        if (filter_var($elementCode, FILTER_VALIDATE_EMAIL)) {
            return $elementCode;
        }

        // Вариант 2: символьный код совпадает с логином пользователя.
        $user = UserTable::getRow(array(
            'filter' => array(
                '=LOGIN' => $elementCode,
                '=ACTIVE' => 'Y',
            ),
            'select' => array(
                'ID',
                'EMAIL',
            ),
        ));

        if (
            $user
            && filter_var($user['EMAIL'], FILTER_VALIDATE_EMAIL)
        ) {
            return $user['EMAIL'];
        }

        return false;
    }

    /**
     * Получает сайты, к которым привязан инфоблок.
     */
    private static function getIblockSiteIds($iblockId)
    {
        $siteIds = array();

        $siteResult = CIBlock::GetSite($iblockId);

        while ($site = $siteResult->Fetch()) {
            if (!empty($site['SITE_ID'])) {
                $siteIds[] = $site['SITE_ID'];
            }
        }

        if (empty($siteIds)) {
            $siteIds[] = defined('SITE_ID') ? SITE_ID : 's1';
        }

        return $siteIds;
    }

    /**
     * Приводит значение свойства к числу.
     */
    private static function normalizeNumber($value)
    {
        if ($value === false || $value === null || $value === '') {
            return 0;
        }

        $value = str_replace(
            array(' ', "\xC2\xA0", ','),
            array('', '', '.'),
            trim((string)$value)
        );

        return is_numeric($value) ? (float)$value : 0;
    }

    /**
     * Форматирование числа для письма.
     */
    private static function formatNumber($value)
    {
        $formatted = number_format((float)$value, 4, '.', '');

        return rtrim(rtrim($formatted, '0'), '.');
    }
}