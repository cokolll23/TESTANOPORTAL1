<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?
$APPLICATION->SetTitle('Кадровая информация');

$pageId = "user_personnel";
include("util_menu.php");
include("util_profile.php");

if ($USER->GetID() != $arResult["VARIABLES"]["user_id"]) {
    ShowError('Доступ к разделу закрыт.');
} else {
    $APPLICATION->IncludeComponent(
        "bitrix:ui.sidepanel.wrapper",
        "",
        array(
            'POPUP_COMPONENT_NAME' => 'korus:user.personnel',
            "POPUP_COMPONENT_TEMPLATE_NAME" => "",
            "POPUP_COMPONENT_PARAMS" => array(
                "USER_ID" => $arResult["VARIABLES"]["user_id"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
            ),
            "POPUP_COMPONENT_PARENT" => $component
        )
    );
}
?>