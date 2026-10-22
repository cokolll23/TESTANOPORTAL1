<?php

global $APPLICATION;

use Korus\Main\Helpers\Layout;
use Korus\Main\Orm\Iblock\Model;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/intranet/public/services/faq/index.php");
$APPLICATION->SetTitle('Вопрос-ответ');

Layout::setTransparentWorkarea();

$APPLICATION->IncludeComponent(
    "korus:employees.faq",
    "",
    [
        "IBLOCK_TYPE" => "ABOUT_COMPANY",    // Типы инфоблоков
        "IBLOCK_ID" => Model::getByCode('KORUS_FAQ', 'ABOUT_COMPANY')->getId(),    // Список инфоблоков
        "EXPAND_LIST" => "Y",
        "SEF_MODE" => "Y",    // Включить поддержку ЧПУ
        "SEF_FOLDER" => "/company/faq/",    // Каталог ЧПУ (относительно корня сайта)
        "AJAX_MODE" => "N",    // Включить режим AJAX
        "AJAX_OPTION_SHADOW" => "Y",
        "AJAX_OPTION_JUMP" => "N",    // Включить прокрутку к началу компонента
        "AJAX_OPTION_STYLE" => "Y",    // Включить подгрузку стилей
        "AJAX_OPTION_HISTORY" => "N",    // Включить эмуляцию навигации браузера
        "CACHE_TYPE" => "A",    // Тип кеширования
        "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
        "SHOW_RATING" => "",    // Включить рейтинг
        "RATING_TYPE" => "like_short",    // Вид кнопок рейтинга
        "PATH_TO_USER" => "/company/personal/user/#user_id#/",    // Шаблон пути к странице пользователя
        "SEF_URL_TEMPLATES" => [
            "faq" => "",
            "section" => "#SECTION_ID#/",
            "detail" => "#SECTION_ID#/#ELEMENT_ID#",
        ]
    ],
    false
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
