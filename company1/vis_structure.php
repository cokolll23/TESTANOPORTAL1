<?php

declare(strict_types=1);

/** @noinspection PhpIncludeInspection */
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

global $APPLICATION;
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/intranet/public/company/vis_structure.php');
$APPLICATION->SetTitle(GetMessage('COMPANY_TITLE'));
$APPLICATION->AddChainItem(GetMessage('COMPANY_TITLE'), 'vis_structure.php');
$APPLICATION->IncludeComponent(
    'korus:employees.company.structure',
    '',
    [
        'ELEMENTS_PER_PAGE' => '20',
        'USER_FIELDS' => [
            'WORK_PHONE' => 'Рабочий телефон',
            'WORK_POSITION' => 'Должность в  компании',
            'PERSONAL_MOBILE' => 'Мобильный',
            'UF_DEPARTMENT' => 'Подразделения',
        ],
        'EDITOR_GROUPS' => [
            0 => '1',
        ],
        'POSITION_PAGE' => 'Y',
    ]
);

/** @noinspection PhpIncludeInspection */
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
