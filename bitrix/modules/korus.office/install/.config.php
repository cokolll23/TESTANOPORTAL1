<?php

use Bitrix\Main\Application;

$root = Application::getDocumentRoot();

return [
    'database' => true,
    'files' => [
        [
            'from' => __DIR__ . '/public',
            'to' => $root,
        ],
        [
            'from' => __DIR__ . '/bitrix',
            'to' => $root . BX_ROOT,
        ],
        [
            'from' => __DIR__ . '/k-team',
            'to' => $root . KTEAM_REPOSITORY,
        ],
    ],
    'routing' => [
        'routes' => __DIR__ . '/bitrix/routes',
    ],
];
