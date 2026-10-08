<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = [
    "NAME" => "Производственный календарь",
    "DESCRIPTION" => "Интерактивный календарь рабочих, выходных и праздничных дней",
    "PATH" => [
        "ID" => "vendor",
        "NAME" => "Кастомные компоненты",
    ],
];