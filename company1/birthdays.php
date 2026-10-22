<?php

global $APPLICATION;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Korus\Main\Helpers\Layout;
use Korus\Vult\Helpers\IBlock;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/intranet/public/company/birthdays.php");
$APPLICATION->SetTitle(GetMessage("COMPANY_TITLE"));

Layout::setTransparentWorkarea();

$APPLICATION->IncludeComponent(
    'bitrix:catalog.section.list',
    'select',
    [
        'ADD_SECTIONS_CHAIN' => 'Y',
        'CACHE_FILTER' => 'N',
        'CACHE_GROUPS' => 'Y',
        'CACHE_TIME' => '36000000',
        'CACHE_TYPE' => 'N',
        'COUNT_ELEMENTS' => 'N',
        'COUNT_ELEMENTS_FILTER' => 'CNT_ACTIVE',
        'FILTER_NAME' => 'sectionsFilter',
        'IBLOCK_ID' => IBlock::getIblockIdByCode('departments'),
        'IBLOCK_TYPE' => 'structure',
        'SECTION_CODE' => '',
        'SECTION_FIELDS' => ['', ''],
        'SECTION_ID' => '',
        'SECTION_URL' => '',
        'SECTION_USER_FIELDS' => ['', ''],
        'SHOW_PARENT_NAME' => 'Y',
        'TOP_DEPTH' => '5',
        'VIEW_MODE' => 'LINE'
    ]
);

$request = Application::getInstance()->getContext()->getRequest();

$APPLICATION->IncludeComponent("korus:employees.birthday.list", ".default", [
        "STRUCTURE_PAGE" => "structure.php",
        "PM_URL" => "/company/personal/messages/chat/#USER_ID#/",
        "PATH_TO_CONPANY_DEPARTMENT" => "/company/structure.php?set_filter_structure=Y&structure_UF_DEPARTMENT=#ID#",
        "PATH_TO_VIDEO_CALL" => "/company/personal/video/#USER_ID#/",
        "STRUCTURE_FILTER" => "structure",
        "NUM_USERS" => "25",
        "DATE_FORMAT" => "d.m.Y",
        "DATE_FORMAT_NO_YEAR" => "d.m",
        "DATE_TIME_FORMAT" => "d.m.Y H:i:s",
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_SHADOW" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "SHOW_YEAR" => Option::get("intranet", "user_profile_show_year") ?: 'M',
        "USER_PROPERTY" => [
            0 => "PERSONAL_PHONE",
            1 => "UF_DEPARTMENT",
            2 => "UF_PHONE_INNER",
            3 => "UF_SKYPE",
            4 => "PERSONAL_PHOTO",
        ],
        "SHOW_FILTER" => "Y",
        "DEPARTMENT" => $request->getQuery("DEPARTMENT"),
    ]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
