<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arTransferPeriods = [];
foreach ($arResult["PERIOD"] as $period) {
    if (
        empty($transferDayStart)
        && !empty($period['transfer'])
        && preg_match('/([0-9]{2}\.[0-9]{2}\.[0-9]{4})/', $period['transfer'], $matches)
    ) {
        $transferDays = intval($period["PERIOD"] / 86400);
        if ($transferDays > 0) {
            for ($p = 0; $p < $transferDays; $p++) {
                $datetime = new DateTime($matches[0]);
                $datetime->modify('+' . $p . ' day');
                $date = $datetime->format('dmY');
                $arTransferPeriods[$period["PROPERTY_USER_VALUE"] . $date] = (!empty($period["CODE"])) ? mb_strtolower($period["CODE"]) : 'other';
            }
        }
    }
}

$arResult["TRANSFER_USERS_DAYS"] = $arTransferPeriods;