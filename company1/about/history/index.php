<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Korus\Main\Orm\Iblock\Model;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

/** @global CMain $APPLICATION */


$request = Application::getInstance()->getContext()->getRequest();
$stateId = intval($request->get('stateId'));
if (!$stateId) {
    LocalRedirect('/company/about/');
}

$aboutCompanyIblock = Model::getByCode('COMPANY_HISTORY', 'ABOUT_COMPANY');

$APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "",
    array(
        "IBLOCK_TYPE" => $aboutCompanyIblock->getType(),
        "IBLOCK_ID" => $aboutCompanyIblock->getId(),
        "ELEMENT_ID" => $stateId,
        "USE_SHARE" => "N",
        "AJAX_MODE" => "N",
        "CHECK_DATES" => "N",
        "SET_TITLE" => "Y",
        "SET_BROWSER_TITLE" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_GROUPS" => "Y",
    )
);
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
