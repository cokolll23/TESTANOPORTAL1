<?php

declare(strict_types=1);

/** @global CMain $APPLICATION */

use Korus\Main\Helpers\Layout;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

Layout::setTransparentWorkarea();

$APPLICATION->SetTitle("Наши проекты");

$APPLICATION->IncludeComponent(
    "korus:employees.company.branch",
    "",
    [
        'SEF_FOLDER' => SITE_DIR.'company/branches/',
    ]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
