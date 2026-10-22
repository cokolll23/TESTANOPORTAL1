<?php

use Korus\Main\Helpers\Layout;
use Korus\Main\Orm\Iblock\Model;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("О компании");
$aboutCompanyIblock = Model::getByCode('ABOUT_COMPANY_IB', 'ABOUT_COMPANY');

Layout::setTransparentWorkarea(false);

$APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "about_company",
    [
        "IBLOCK_TYPE" => $aboutCompanyIblock->getType(),
        "IBLOCK_ID" => $aboutCompanyIblock->getId(),
        "ELEMENT_CODE" => "ABOUT_COMPANY_PAGE",
        "PROPERTY_CODE" => ['ceo', 'management', 'events', 'mission', 'purposes', 'requisite', 'requisite_text', 'contacts'],
        "USE_SHARE" => "N",
        "AJAX_MODE" => "N",
        "CHECK_DATES" => "N",
        "SET_TITLE" => "Y",
        "SET_BROWSER_TITLE" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_GROUPS" => "Y",
    ]
);
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
