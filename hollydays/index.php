<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Отсутствия");
?>
<?php

use \Lab\Helpers\IblockHelpers;
use Bitrix\Main\UserTable;

$xmlFile = file_get_contents('company___58a189e5-409d-49c1-a8fc-bf24bfe60b90.xml');
$xml = simplexml_load_string($xmlFile);
$jsonData = json_encode($xml);
$arXml = json_decode($jsonData, true);
pretty_print($arXml);
foreach ($arXml['ОрганизационнаяСтруктура']['Работники']['Работник'] as $key => $value) {
    $arEmploees[$value['Ид']] = $value;
}

foreach ($arXml['ГрафикОтсутствий']['ЗаписиГрафика']['ЗаписьГрафика'] as $key => $value) {
    $arAbsances[$value['Работник']][] = $value;
}
foreach ($arEmploees as $key => $value) {
    foreach ($value['Контакты']['Контакт'] as $k => $v) {

        if ($v['Тип'] === 'Почта') {
            $arEmploees[$key]['email'] = $v['Значение'];

            $user = UserTable::getList([
                'select' => ['ID', 'EMAIL'],
                'filter' => [
                    '=EMAIL' => $v['Значение'],
                ],
                'limit' => 1,
            ])->fetch();

            $userId = $user ? (int)$user['ID'] : null;
            $arEmploees[$key]['user_id'] = (int)$user['ID'];
            $arEmploees[$key]['absences'] = $arAbsances[$key];

        }


    }


}

pretty_print($arEmploees, '$arEmploees');

pretty_print($arAbsances, '$arAbsances');
/*$strIblockCode = 'absence';
echo $intIblockId = IblockHelpers::getIblockIdByCode($strIblockCode);
*/
?>


<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>