<?php

namespace Lab\BirthdayAgent;

// birthday_agent.php
// Поместите этот файл в /local/php_interface/include/ или /bitrix/php_interface/include/

use Bitrix\Main\Loader;
use Bitrix\Main\Type\Date;
use Bitrix\Main\UserTable;
use Lab\Helpers\IblockHelpers as ih;

class BirthdayAgent
{
    /**
     * Проверяет дни рождения сотрудников и выполняет действия
     */
    public static function checkBirthdays()
    {
        // Загружаем модуль главного интерфейса
        if (!Loader::includeModule('intranet')) {
            return "\Lab\BirthdayAgent\BirthdayAgent::checkBirthdays();";
        }

        $today = new Date();
        $todayMonth = $today->format('m');
        $todayDay = $today->format('d');

        // Получаем всех активных пользователей
        $users = UserTable::getList([
            'select' => ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_BIRTHDAY', 'EMAIL', 'WORK_POSITION'],
            'filter' => [
                '=ACTIVE' => 'Y',
                '!=PERSONAL_BIRTHDAY' => false,
                'GROUPS.GROUP_ID' => 12,
            ]
        ]);

        $birthdayUsers = [];

        while ($user = $users->fetch()) {
            if ($user['PERSONAL_BIRTHDAY']) {
                $birthday = new Date($user['PERSONAL_BIRTHDAY']);
                $birthMonth = $birthday->format('m');
                $birthDay = $birthday->format('d');

                // Проверяем совпадение дня и месяца
                if ($birthMonth == $todayMonth && $birthDay == $todayDay) {
                    $birthdayUsers[] = $user;
                }
            }
        }

        // Если есть именинники - выполняем действия
        if (!empty($birthdayUsers)) {
            self::processBirthdayUsers($birthdayUsers);
        }

        // Возвращаем строку для повторного запуска агента
        return "\Lab\BirthdayAgent\BirthdayAgent::checkBirthdays();";
    }

    /**
     * Обрабатывает именинников
     */
    private static function processBirthdayUsers($users)
    {

        foreach ($users as $user) {

            $userIds[] = $user['ID'];
            $userCodes[] = $user['EMAIL'];


            // 4. Записываем событие в лог
            self::logBirthday($user);

            // 4. Записываем баллы в иб
            //self::setBally($userID);
        }
        //self::logBirthdayAfter($userIds);
        self::setColumn37($userCodes);
    }

    /**
     * Добавляет баллы у кого день рождения сегодня
     */

    /* private static function setBally($userID){
         $log = date('Y-m-d H:i:s') . ' OnAfterIBlockElementUpdateHandler ' . print_r($arFields, true);
         file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/log.txt', $log . PHP_EOL, FILE_APPEND);
     }*/
    private static function setColumn37($userCodes)
    {

        // 1. Подключаем необходимые модули
        if (!\Bitrix\Main\Loader::includeModule('iblock')) {
            die('Модуль инфоблоков не подключен');
        }
        /*$elementId = 8919; // ID элемента в инфоблоке sotrudniki 211295
        $elementIds = [8919, 8151, 8152, 8154, 8155, 8169, 8227]; // ID элемента в инфоблоке sotrudniki 211295*/

        $iblockId= ih::getIblockIdByCode('sotrudniki');
        //$iblockId = 44;// 42
        $propertyCode37 = "COLUMN37";
        $propertyCode33 = "COLUMN33";
        $newValue = 5;

        foreach ($userCodes as $elementCode) {

            // 2. Получаем текущее значение свойства (без префикса "PROPERTY_")
            $res = CIBlockElement::GetList(
                [],
                ["CODE" => $elementCode],
                false,
                false,
                ["ID", "PROPERTY_" . $propertyCode37, "PROPERTY_" . $propertyCode33]
            );
            if ($arElement = $res->GetNext()) {
                $elementId = $arElement["ID"];
                $currentValue37 = $arElement["PROPERTY_" . $propertyCode37 . "_VALUE"]; // Текущее значение
                $currentValue33 = $arElement["PROPERTY_" . $propertyCode33 . "_VALUE"]; // Текущее значение
            }
            // 3. Формируем новое значение, добавляя новую строку к старой
            $newValueForSave37 = $currentValue37 + $newValue / 2;

            $newValueForSave33 = $currentValue33 + $newValue / 2;

            // 4. Сохраняем новое значение свойством SetPropertyValuesEx
            $result = CIBlockElement::SetPropertyValuesEx(
                $elementId,
                $iblockId,
                [$propertyCode37 => $newValueForSave37, $propertyCode33 => $newValueForSave33] // Код свойства без "PROPERTY_"
            );
            self::logBirthdayAfter1($elementCode);

        }
        self::logBirthdayAfter($userCodes);
    }

    /**
     * Логирует день рождения
     */
    private static function logBirthday($user)
    {
        $logMessage = date('Y-m-d H:i:s') . " - День рождения: {$user['NAME']} {$user['LAST_NAME']} (EMAIL: {$user['EMAIL']})(ID: {$user['ID']})";
        $log = date('Y-m-d H:i:s') . print_r($logMessage, true);
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/logDR.txt', $log . PHP_EOL, FILE_APPEND);
        //\Bitrix\Main\Diag\Debug::dumpToFile($log, ' ДР ' . date('d-m-Y; H:i:s'));
    }
private static function logBirthdayAfter1($user)
    {
        $logMessage = date('Y-m-d H:i:s') . " - День рождения: {$user} ";
        $log = date('Y-m-d H:i:s') . print_r($logMessage, true);
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/logDRAfter1 10.6.157.129.txt', $log . PHP_EOL, FILE_APPEND);
        //\Bitrix\Main\Diag\Debug::dumpToFile($log, ' ДР ' . date('d-m-Y; H:i:s'));
    }

    private static function logBirthdayAfter($users)
    {
       // $logMessage = date('Y-m-d H:i:s') . " - День рождения: {$user['NAME']} {$user['LAST_NAME']} (ID: {$user['ID']})";
        $log = date('Y-m-d H:i:s') . print_r($users, true);
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/logDRAfter.txt', $log . PHP_EOL, FILE_APPEND);
        //\Bitrix\Main\Diag\Debug::dumpToFile($log, ' ДР ' . date('d-m-Y; H:i:s'));
    }

}


?>
