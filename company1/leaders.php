<?php

global $APPLICATION;

use Korus\Main\Helpers\Layout;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/intranet/public/company/leaders.php");
$APPLICATION->SetTitle(GetMessage("COMPANY_TITLE"));
$APPLICATION->AddChainItem(GetMessage("COMPANY_TITLE"), "/company/leaders.php");

Layout::setTransparentWorkarea();

$APPLICATION->IncludeComponent("korus:employees.structure.leaders", ".default", [
		"STRUCTURE_PAGE" => "/company/structure.php",
		"PM_URL" => "/company/personal/messages/chat/#USER_ID#/",
		"PATH_TO_VIDEO_CALL" => "/company/personal/video/#USER_ID#/",
		"PATH_TO_CONPANY_DEPARTMENT" => "/company/structure.php?set_filter_structure=Y&structure_UF_DEPARTMENT=#ID#",
		"STRUCTURE_FILTER" => "structure",
		"NUM_USERS" => "25",
		"USER_PROPERTY" => [
			0 => "PERSONAL_PHONE",
			1 => "UF_DEPARTMENT",
			2 => "UF_PHONE_INNER",
			3 => "UF_SKYPE",
			4 => "PERSONAL_PHOTO",
		]
	]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
