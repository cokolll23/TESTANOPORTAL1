<?php

global $APPLICATION;
IncludeModuleLangFile(__FILE__);
if ($APPLICATION->GetGroupRight("korus.office") == "D") {
    return false;
}
$aMenu = [
    "parent_menu" => "global_menu_korus",
    "sort" => 100,
    "url" => "korus_office_lk_field_admin.php?lang=" . LANGUAGE_ID,
    "text" => GetMessage("KORUS_OFFICE_LK_FIELD_ADMIN_MENU"),
    "title" => GetMessage("KORUS_OFFICE_LK_FIELD_ADMIN_MENU_TITLE"),
    "icon" => "form_menu_icon",
    "page_icon" => "form_page_icon",
    "items_id" => "korus_menu",
    "items" => [],
];

return $aMenu;

