<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/intranet/public/company/.left.menu.php");

$aMenuLinks = [
    [
        'Об организации ',
        "/wcp/index.php#about",
        [],
        [],
        ""
    ],
    [
        'Наши проекты',
        "/wcp/index.php#projects",
        [],
        [],
        ""
    ],
    [
        'Руководители',
        "/wcp/index.php#leaders",
        [],
        [],
        ""
    ],
    [
        'Адреса офисов',
        '/wcp/index.php#adresses',
        [],
        [],
        ""
    ],
    [
        'Наши правила',
        '/wcp/index.php#orders',
        [],
        [],
        ""
    ],[
        'Полезные ссылки',
        '/wcp/index.php#links',
        [],
        [],
        ""
    ],
];
