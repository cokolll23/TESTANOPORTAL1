<?php

if (is_file($_SERVER["DOCUMENT_ROOT"] . "/local/modules/korus.office/admin/korus_office_lk_field_admin.php")) {
    require($_SERVER["DOCUMENT_ROOT"] . "/local/modules/korus.office/admin/korus_office_lk_field_admin.php");
} elseif (is_file($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/korus.office/admin/korus_office_lk_field_admin.php")) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/korus.office/admin/korus_office_lk_field_admin.php");
}
