<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Результаты поиска");
?>

    <div class="container">
        <? $APPLICATION->IncludeComponent(
            "bitrix:search.page",
            "shop",
            array(
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "CACHE_TIME" => "3600",
                "CACHE_TYPE" => "A",
                "CHECK_DATES" => "N",
                "DEFAULT_SORT" => "rank",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "DISPLAY_TOP_PAGER" => "Y",
                "FILTER_NAME" => "",
                "NO_WORD_LOGIC" => "N",
                "PAGER_SHOW_ALWAYS" => "Y",
                "PAGER_TEMPLATE" => "",
                "PAGER_TITLE" => "Результаты поиска",
                "PAGE_RESULT_COUNT" => "50",
                "PATH_TO_USER_PROFILE" => "",
                "RATING_TYPE" => "",
                "RESTART" => "N",
                "SHOW_RATING" => "",
                "SHOW_WHEN" => "N",
                "SHOW_WHERE" => "Y",
                "USE_LANGUAGE_GUESS" => "Y",
                "USE_SUGGEST" => "N",
                "USE_TITLE_RANK" => "N",
                "arrFILTER" => array("iblock_catalog", "iblock_users"),
                "arrFILTER_iblock_catalog" => array("all"),
                "arrFILTER_iblock_users" => array("all"),
                "arrWHERE" => array("iblock_catalog", "iblock_users")
            )
        ); ?>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>