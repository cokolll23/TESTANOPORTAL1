<?php

use Korus\Vult\Helpers\UserGroup;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$aMenuLinks = [
    [
        "Вопрос-ответ",
        SITE_DIR . "company/faq/",
        [],
        [],
        ""
    ],
    [
        "Список вопросов",
        SITE_DIR . "company/faq/list",
        [],
        [],
        'CSite::InGroup(array(1,' . UserGroup::getGroupByStringId('ADMIN_FAQ')['ID'] . '));'
    ],
];
