<?php

namespace Lab\EventsHandlers;

use Lab\Helpers\IblockHelpers as IblockHelpers;
use Lab\Helpers\IblockHelpers as IH;
use Lab\Helpers\RecalculateScores as RS;
use Bitrix\Iblock\PropertyEnumerationTable;
use Lab\Helpers\UsersHelpers as UH;

class IblockEventsHandlers
{

    /**
     *  логирование Проверить какие данные идут из /upload/1c_intranet
     *  IB График отсутствий id=1 CODE = "absence"
     * 2. Обработчик события добавления элемента (OnAfterIBlockElementAdd)
     * Вызывается сразу после того, как новый элемент (отсутствие) был сохранен в БД.
     */
    public static function OnAfterAbsenceAddHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"]==1 && intval($arFields["ID"]) > 0) {
            $log = date('d.m.Y H:i:s') . ' Добавление в ИБ График отсутствия  ' . print_r($arFields, true) . PHP_EOL;
            //file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/addAbsence.txt", $log, FILE_APPEND);
        } else {
            // Если произошла ошибка, логируем текст ошибки из RESULT_MESSAGE
            $errorText = $arFields["RESULT_MESSAGE"] ?? "Неизвестная ошибка";
            $log = date('d.m.Y H:i:s') . "ДОБАВЛЕНИЕ (ОШИБКА): ID={$arFields["ID"]}. Текст ошибки: " . $errorText;
            //file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/addAbsence.txt", $log, FILE_APPEND);
        }
        /*$log = date('Y-m-d H:i:s') . ' onStatusChange' . print_r($propsNotZero, true);
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
        Bitrix\Main\Diag\Debug::dumpToFile($log, '$event onStatusChange' . date('d-m-Y; H:i:s'));*/

    }

    /**
     * логирование Проверить какие данные идут из /upload/1c_intranet
     * IB График отсутствий id=1 CODE = "absence" Обработчик события обновления элемента (OnAfterIBlockElementUpdate)
     * Вызывается после попытки изменения элемента.
     * ВАЖНО: Срабатывает даже при ошибке, поэтому проверяем флаг RESULT [citation:2].
     */
    public static function OnAfterAbsenceUpdateHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"]==1 && intval($arFields["ID"]) > 0) {
            $log = date('d.m.Y H:i:s') . ' Изменение в ИБ График отсутствия  ' . print_r($arFields, true) . PHP_EOL;
           // file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/addAbsence.txt", $log, FILE_APPEND);
        } else {
            // Если произошла ошибка, логируем текст ошибки из RESULT_MESSAGE
            $errorText = $arFields["RESULT_MESSAGE"] ?? "Неизвестная ошибка";
            $log = date('d.m.Y H:i:s') . "ИЗМЕНЕНИЕ (ОШИБКА): ID={$arFields["ID"]}. Текст ошибки: " . $errorText;
           // file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/addAbsence.txt", $log, FILE_APPEND);
        }
    }

    /**
     * суммируются все значения свойств ИБ в поле Итого CODE COLUMN33
     * @param $arFields
     * @return void
     */
    public static function onAfterIBlockElementUpdateHandler(&$arFields)
    {
        $iblockCode = IblockHelpers::getIBlockCodeById($arFields['IBLOCK_ID']);
        $propertyId = IblockHelpers::getPropertyIdByCode('sotrudniki', 'COLUMN33');
        $propertyIdColumn34 = IblockHelpers::getPropertyIdByCode('sotrudniki', 'COLUMN34');
        $userEmail=$arFields['CODE'];

        if ($iblockCode === 'sotrudniki') {

            $intElementID = $arFields['ID'];
            $iblockID = $arFields['IBLOCK_ID'];

            if (!is_array($arFields['PROPERTY_VALUES']))
                return;

            $res = array_diff_key($arFields['PROPERTY_VALUES'], array($propertyId => true, $propertyIdColumn34 => true));

            array_walk_recursive($res, function ($item, $key) use (&$result) {
                $result[] = $item;
            });
            $summa = array_sum($result);
        }
        \Bitrix\Main\Loader::includeModule("iblock");
        // ID инфоблока (IBLOCK_ID) и ID элемента (ID)
        $iblockId = $arFields['IBLOCK_ID']; // Замените на ваш ID инфоблока
        $elementId = $intElementID; // Замените на ID элемента

        // Новое значение для свойства COLUMN33
        $newValue = $summa;

        $totalPrise=RS::getTotalScores('sotrudniki', $userEmail);
        // Устанавливаем значение свойства
        /*\CIBlockElement::SetPropertyValuesEx(
            $elementId,
            $iblockId,
            array(
                "COLUMN33" => $newValue
            )
        );*/

        $log = date('Y-m-d H:i:s') . ' OnAfterIBlockSotrudnikiElementUpdate ' . print_r($arFields, true);
        file_put_contents(__DIR__ . '/logSotrUpdt.txt', $log . PHP_EOL, FILE_APPEND);

    }

    public static function onAfterIBlockElementAddHandler(&$arFields)
    {
        // todo при добавлении сотрудника в таблицу баллов CODE sotrudniki если раздел muf или komitet
        //todo  регистрация нового пользователя в группу Все покупатели
        // todo отправка писем при добавлении сообщения обратной связи CODE interlabs.feedbackform
        $IBLOCK_ID = $arFields['IBLOCK_ID'];
        $IBLOCK_CODE = IblockHelpers::getIBlockCodeById($IBLOCK_ID);

        if ($IBLOCK_CODE === 'sotrudniki') {

        }
        if ($IBLOCK_CODE === 'interlabs.feedbackform') {

            //$PROPERTY_VALUES = $arFields['PROPERTY_VALUES'];
            //$updatingElementCode = $PROPERTY_VALUES['EMAIL'];
            //$updatingElementId = IblockHelpers::getIblockElementInfo('sotrudniki', $updatingElementCode)['ID'];
            //$updatingElementChangingPropCode = $PROPERTY_VALUES['EVENT_CODE'];
            //$updatingElementChangingPropId = ;
            //$interlabsSignscoresPropsList = IblockHelpers::getPropsListIblock('interlabs.signscores');


           /* $log = date('Y-m-d H:i:s') . ' interlabs.feedbackform ' . print_r($arFields, true);
            file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
            \Bitrix\Main\Diag\Debug::dumpToFile($log, 'interlabs.feedbackform' . date('d-m-Y; H:i:s'));*/

        }

        if ($IBLOCK_CODE === 'interlabs.feedbackform') {

            /* $adminEmail = COption::GetOptionString("main", "email_from");
             $iblockName = CIBlock::GetByID($targetIblockId)->Fetch()['NAME'];

             $subject = "Добавлен новый элемент в инфоблок «{$iblockName}»";

             $message = "
                 <h3>Новый элемент #{$arFields['ID']}</h3>
                 <p><strong>Название:</strong> {$arFields['NAME']}</p>
                 <p><strong>Дата создания:</strong> ".FormatDate('j F Y H:i')."</p>
             ";

             if (!empty($arFields['PREVIEW_TEXT'])) {
                 $message .= "<p><strong>Описание:</strong> {$arFields['PREVIEW_TEXT']}</p>";
             }

             $message .= "
                 <p>
                     <a href='/bitrix/admin/iblock_element_edit.php?IBLOCK_ID={$targetIblockId}&type=content&ID={$arFields['ID']}'>
                         Редактировать элемент
                     </a>
                 </p>
             ";

             CEvent::SendImmediate(
                 "IBLOCK_NEW_ELEMENT",
                 SITE_ID,
                 array(
                     "EMAIL_TO" => $adminEmail,
                     "SUBJECT" => $subject,
                     "BODY" => $message,
                 )
             );*/

        }


    }

    /**
     * отправляет письмо после добавления элемента в иб interlabs.feedbackform Написать администратору
     * @param $arFields
     * @return void
     */
    public static function onAfterIBlockElementAddHandlerSendMail(&$arFields)
    {
        // todo отправка писем при добавлении сообщения обратной связи CODE interlabs.feedbackform
        $IBLOCK_ID = $arFields['IBLOCK_ID'];
        $IBLOCK_CODE = IH::getIBlockCodeById($IBLOCK_ID);
        $elID = $arFields['ID'];


        $server = \Bitrix\Main\Context::getCurrent()->getServer();
        $domain = $server->getServerName();

        if ($IBLOCK_CODE === 'interlabs.feedbackform') { // Из формы Написать администратору

            $to = $adminEmail = 'cavjob@ya.ru,sobolevaya3@mos.ru';

            $hrefToEditionElementInIB = "https://corp-portal.welcome.moscow/bitrix/admin/iblock_element_edit.php?IBLOCK_ID={$IBLOCK_ID}&type=feedbackmsgs&lang=ru&ID={$elID}&find_section_section=0&WF=Y";


            $iblockName = \CIBlock::GetByID($IBLOCK_ID)->Fetch()['NAME'];

            $subject = "=?UTF-8?B?" . base64_encode("Магазин бонусов форма Написать администратору") . "?=";

            $message = <<<HTML

Письмо от {$arFields['NAME']} 
Телефон: {$arFields ['PROPERTY_VALUES']['PHONE']} 
EMAIL: {$arFields['PROPERTY_VALUES']['EMAIL']} 
Сообщение: {$arFields['PROPERTY_VALUES']['MESSAGE']} 
{$hrefToEditionElementInIB}"
    

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

        }
        if ($IBLOCK_CODE === 'interlabs.signscores') { // Из формы Написать администратору


            $to = $adminEmail = 'cavjob@ya.ru,sobolevaya3@mos.ru';

            $hrefToEditionElementInIB = "https://corp-portal.welcome.moscow/bitrix/admin/iblock_element_edit.php?IBLOCK_ID={$IBLOCK_ID}&type=feedbackmsgs&lang=ru&ID={$elID}&find_section_section=0&WF=Y";


            $iblockName = \CIBlock::GetByID($IBLOCK_ID)->Fetch()['NAME'];

            $subject = "=?UTF-8?B?" . base64_encode("Магазин бонусов форма Запись М-баллов") . "?=";
            /* [EVENT_CODE] => COLUMN12
             [EVENT_NAME] => Проведение семинара/вебинара/экскурсии/мастер-классадля сотрудников (детей сотрудников) 20 б
             [SCORES_QTT] => 10*/
            $message = <<<HTML

Письмо от {$arFields['NAME']} 
Телефон: {$arFields ['PROPERTY_VALUES']['PHONE']} 
EMAIL: {$arFields['PROPERTY_VALUES']['EMAIL']} 
Количество М-баллов: {$arFields['PROPERTY_VALUES']['SCORES_QTT']} 
Название мероприятия: {$arFields['PROPERTY_VALUES']['EVENT_NAME']} 
Код мероприятия: {$arFields['PROPERTY_VALUES']['EVENT_CODE']} 
{$hrefToEditionElementInIB}

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

        }
    }

    /**
     * событие добавления элемента в ib  История состояний IBLOCK_CODE => 'state_history'
     *  FIRED => Уволен ACCEPTED => Принят
     * @param $arFields
     * @return void
     * 25 FIRED => Уволен   23 ACCEPTED => Принят
     * [PROPERTY_VALUES] => Array
     * (
     * [USER] => 689
     * [DEPARTMENT] => Array
     * (
     * [0] => 151
     * )
     *
     * [POST] =>
     * [STATE] => 23
     * )
     */
    public static function onAfterIBlockElementAddHandlerStateHistoryIB(&$arFields)
    {
        $IBLOCK_CODE_state_history = IblockHelpers::getIBlockCodeById($arFields['IBLOCK_ID']);

        if ($IBLOCK_CODE_state_history == 'state_history') {

            $userId = $arFields['PROPERTY_VALUES']['USER'];
            $arUserInfo = UH::getUserInfoById($userId);


            if ($arUserInfo['UF_DEPT'] != '') {
                $strXML_IDUserFieldEnum = UH::getUserXML_IDById($arUserInfo['UF_DEPT']);
            } else {
                $strXML_IDUserFieldEnum = 'ano';
            }


            $intSectionId = IblockHelpers::getGroupIdByCode('sotrudniki', $strXML_IDUserFieldEnum, 's2');

            $userEmail = $arUserInfo['EMAIL'];
            $FIO = $arUserInfo['LAST_NAME'] . ' ' . $arUserInfo['NAME'] . ' ' . $arUserInfo['SECOND_NAME'];

            $IBLOCK_ID = IblockHelpers::getIblockIdByCode('sotrudniki');
            $IBLOCK_CODE = IblockHelpers::getIBlockCodeById($IBLOCK_ID);

            $strPropertyIdStateVal = $arFields['PROPERTY_VALUES']['STATE'];
            $strPropertyCodeStateVal = IblockHelpers::getXML_IDValPropertyById($strPropertyIdStateVal);
            $blFromAnoCheck = $arFields['PROPERTY_VALUES']['DEPARTMENT'][0] != '' ? 1 : 0;

            $arLog = [
                'IBLOCK_ID' => $IBLOCK_ID,
                'IBLOCK_CODE' => $IBLOCK_CODE,
                'USER_ID' => $userId,
                'USER_EMAIL' => $userEmail,
                'USER_FIO' => $FIO,
                'SECTION_ID' => $intSectionId,
                'STATE' => $strPropertyCodeStateVal,
                'DEPARTMENT' => $strPropertyIdStateVal,
                '$IBLOCK_CODE_state_history' => $IBLOCK_CODE_state_history,
                '$strXML_IDUserFieldEnum' => $strXML_IDUserFieldEnum,
                $arUserInfo['$arUserInfo'] => $arUserInfo,

            ];


            if ($strPropertyCodeStateVal == 'ACCEPTED') {

                $userNewId = IblockHelpers::addElsToIblock('sotrudniki', $userId, $FIO, $userEmail, $strXML_IDUserFieldEnum, 's2', 'Y');
            }

        }

    }
}