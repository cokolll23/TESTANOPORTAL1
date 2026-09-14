<?php

namespace Sprint\Migration;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;
use COption;
use Exception;
use Korus\Office\Entity\OfficeFieldSettingsTable;

class Version20240117130641 extends Version
{
    protected $description = "Правки в конфиге для менеджмента поля LAST_ACTIVITY_DATE";

    protected $moduleVersion = "4.6.2";

    /**
     * @return void
     * @throws LoaderException
     */
    public function up(): void
    {
        Loader::includeModule('korus.office');

        // Имеет смысл стартовать транзакцию, пробовать установить, и при успешном удалении, дополнять Option?
        try {
            OfficeFieldSettingsTable::add([
                "FIELD_CODE" => "LAST_ACTIVITY_DATE",
                "VIEW" => OfficeFieldSettingsTable::VIEW['HIDE'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['DENY'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ]);

            $settingsFieldList = unserialize(COption::GetOptionString('korus.office', 'settings_field_list'));
            $settingsFieldList['default']['LAST_ACTIVITY_DATE'] = ["active" => true, "code" => "LAST_ACTIVITY_DATE", "sort" => "92"];

            COption::SetOptionString('korus.office', 'settings_field_list', serialize($settingsFieldList));
        } catch (ArgumentException|SystemException) {
        }
    }

    /**
     * @return void
     * @throws LoaderException
     * @throws Exception
     */
    public function down(): void
    {
        Loader::includeModule('korus.office');

        // Имеет смысл стартовать транзакцию, пробовать удалить, и при успешном удалении, чистить Option?
        try {
            $settingsField = OfficeFieldSettingsTable::query()
                ->setSelect(['ID','FIELD_CODE'])
                ->where('FIELD_CODE', 'LAST_ACTIVITY_DATE')
                ->fetch();

            if ($settingsField['ID']) {
                OfficeFieldSettingsTable::delete($settingsField['ID']);
            }

            $settingsFieldList = unserialize(COption::GetOptionString('korus.office', 'settings_field_list'));
            $settingsFieldList['default']['LAST_ACTIVITY_DATE'] = ["active" => false, "code" => "LAST_ACTIVITY_DATE", "sort" => "0"];

            COption::SetOptionString('korus.office', 'settings_field_list', serialize($settingsFieldList));
        } catch (ArgumentException|SystemException) {
        }
    }
}
