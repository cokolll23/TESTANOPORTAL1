<?php

/**
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$pageId = "user";
include("util_menu.php");

$url = $APPLICATION->GetCurPage() . $USER->GetID() . '/';
LocalRedirect($url);
