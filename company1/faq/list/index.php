<?php

use Korus\Main\Orm\Iblock\Model;

/** @var CMain $APPLICATION */

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$APPLICATION->SetTitle('Список вопросов');

$APPLICATION->IncludeComponent(
    "korus:employees.faq.grid",
    "",
    [
        "IBLOCK_TYPE" => "ABOUT_COMPANY",    // Типы инфоблоков
        "IBLOCK_ID" => Model::getByCode('KORUS_FAQ', 'ABOUT_COMPANY')->getId(),    // Список инфоблоков
    ],
    false
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');