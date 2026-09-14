<?php

declare(strict_types=1);

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

global $APPLICATION;
$APPLICATION->IncludeComponent(
    'korus.main:options',
    '',
    [
        'MODULE_ID' => 'korus.office',
        'OPTIONS' => [
            [
                'DIV' => 'config',
                'TAB' => Loc::getMessage('KORUS_OFFICE_OPTION_SETTINGS'),
                'TITLE' => Loc::getMessage('KORUS_OFFICE_OPTION_SETTINGS'),
                'OPTIONS' => [
                    [
                        'disable_emoji_status',
                        Loc::getMessage('KORUS_OFFICE_DISABLE_EMOJI_STATUS'),
                        null,
                        ['checkbox']
                    ],
                ],
            ],
        ],
    ]
);
