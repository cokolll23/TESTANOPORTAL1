<?php
/** @var $APPLICATION \CMain */
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

if (CModule::IncludeModule("vote")) {
    // Выведем активные опросы из групп с ID 1 и 2, отсортированные по ID по убыванию
    $rsVote = CVote::GetList("s_id", "desc", array("CHANNEL_ID" => "1|2", "ACTIVE" => "Y"));
    while ($arVote = $rsVote->GetNext()) {
        $arVotes[]=$arVote;
    }
}
//pretty_print($arVotes);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
