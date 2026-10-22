<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/intranet/public/company/.left.menu.php");
$aMenuLinks = [
    [
        "О компании",
        "/company/about/",
        [],
        [
            'menu_item_id' => 'menu_arrow-about_company'
        ],
        ''
    ],
    [
        "Наши проекты",
        "/company/branches/",
        [],
        [
            'menu_item_id' => 'menu_arrow-branch'
        ],
        ''
    ]
];
