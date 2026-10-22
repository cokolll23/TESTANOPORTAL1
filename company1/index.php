<?php
/**
 * @global  \CMain $APPLICATION
 */

use Korus\Main\Helpers\Layout;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/intranet/public/company/index.php');
$APPLICATION->SetTitle(GetMessage('COMPANY_MENU_TELEPHONES'));

Layout::setTransparentWorkarea();

?><?php
$componentParams = [
    'PATH_TO_DEPARTMENT' => SITE_DIR.'company/structure.php?set_filter_structure=Y&structure_UF_DEPARTMENT=#ID#',
    'LIST_URL' => SITE_DIR.'company/',
    'USER_PROPERTY_LIST' => array_merge([
        'UF_DEPARTMENT',
        'UF_USER_CRM_ENTITY',
        'UF_TIMEMAN',
        'UF_TM_REPORT_REQ',
        'UF_TM_FREE',
        'UF_REPORT_PERIOD',
        'UF_1C',
        'UF_TM_ALLOWED_DELTA',
        'UF_SETTING_DATE',
        'UF_LAST_REPORT_DATE',
        'UF_DELAY_TIME',
        'UF_TM_REPORT_DATE',
        'UF_TM_DAY',
        'UF_TM_TIME',
        'UF_TM_REPORT_TPL',
        'UF_TM_MIN_DURATION',
        'UF_TM_MIN_FINISH',
        'UF_TM_MAX_START',
        'UF_CONNECTOR_MD5',
        'UF_WORK_BINDING',
        'UF_IM_SEARCH',
        'UF_BXDAVEX_CALSYNC',
        'UF_BXDAVEX_MLSYNC',
        'UF_UNREAD_MAIL_COUNT',
        'UF_BXDAVEX_CNTSYNC',
        'UF_BXDAVEX_MAILBOX',
        'UF_VI_PASSWORD',
        'UF_VI_BACKPHONE',
        'UF_VI_PHONE',
        'UF_VI_PHONE_PASSWORD',
        'UF_AVAILABILITY',
        'UF_EXPERTISE',
        'UF_ASSISTANT',
        'UF_CONTACT_PERSONS',
        'UF_MOB_PHONE_PER',
        'UF_COMPETENCE'
    ], \Bitrix\Intranet\Component\UserList::getUserPropertyListDefault())
];

$APPLICATION->IncludeComponent(
    'bitrix:ui.sidepanel.wrapper',
    '',
    array(
        'POPUP_COMPONENT_NAME' => 'korus:intranet.user.list',
        'POPUP_COMPONENT_TEMPLATE_NAME' => '',
        'POPUP_COMPONENT_PARAMS' => $componentParams,
        'USE_UI_TOOLBAR' => 'Y'
    )
);
?><?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
