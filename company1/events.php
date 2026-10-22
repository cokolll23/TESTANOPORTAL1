<?php

global $APPLICATION;

use Bitrix\Main\Application;
use Korus\Main\Helpers\Layout;
use Korus\Vult\Helpers\IBlock;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/intranet/public/index.php');
$APPLICATION->SetTitle('Новые сотрудники');
$APPLICATION->AddChainItem('Новые сотрудники', '/company/events.php');

Layout::setTransparentWorkarea();

CModule::IncludeModule('korus.vult');

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

$APPLICATION->IncludeComponent(
    'korus:employees.structure.new',
    '',
    [
        'AJAX_MODE' => 'Y',
        'AJAX_OPTION_HISTORY' => 'N',
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_STYLE' => 'Y',
        'CACHE_TIME' => '3600',
        'CACHE_TYPE' => 'N',
        'DATE_FORMAT' => 'd.m.Y',
        'DATE_TIME_FORMAT' => 'd.m.Y H:i:s',
        'DEPARTMENT' => $request->getQuery('DEPARTMENT'),
        'NAME_TEMPLATE' => '#NOBR##LAST_NAME# #NAME##/NOBR#',
        'NUM_USERS' => '1000',
        'PATH_TO_CONPANY_DEPARTMENT' => '/company/structure.php?set_filter_structure=Y&structure_UF_DEPARTMENT=#ID#',
        'PM_URL' => '/company/personal/messages/chat/#USER_ID#/',
        'PATH_TO_VIDEO_CALL' => '/company/personal/video/#USER_ID#/',
        'PERSONAL_URL' => '/company/personal/user/#USER_ID#/',
        'SHOW_LOGIN' => 'Y',
        'SHOW_YEAR' => 'Y',
        'SELECT_DAYS' => 14,
        'USER_PROPERTY' => [
            0 => 'PERSONAL_PHONE',
            2 => 'UF_PHONE_INNER',
            3 => 'EMAIL',
            4 => 'PERSONAL_PHOTO',
        ],
    ]
);
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
