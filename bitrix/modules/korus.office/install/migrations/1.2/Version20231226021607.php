<?php

namespace Sprint\Migration;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use COption;
use Exception;
use Korus\Office\Entity\OfficeFieldSettingsTable;

class Version20231226021607 extends Version
{
    protected $description = "Правки в конфиге для менеджмента полей NAME и LAST_NAME";

    protected $moduleVersion = "4.6.2";

    /**
     * @return void
     * @throws ArgumentException
     * @throws LoaderException
     * @throws SystemException
     */
    public function up(): void
    {
        Loader::includeModule('korus.office');

        $settingsFieldList = unserialize(COption::GetOptionString('korus.office', 'settings_field_list'));
        $settingsFieldList['default']['NAME'] = ["active" => true, "code" => "NAME", "sort" => "90"];
        $settingsFieldList['default']['LAST_NAME'] = ["active" => true, "code" => "LAST_NAME", "sort" => "91"];

        COption::SetOptionString('korus.office', 'settings_field_list', serialize($settingsFieldList));

        OfficeFieldSettingsTable::addMulti([
            [
                "FIELD_CODE" => "NAME",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "LAST_NAME",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
        ]);
    }

    /**
     * @return void
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws Exception
     */
    public function down(): void
    {
        Loader::includeModule('korus.office');

        $settingsFieldList = unserialize(COption::GetOptionString('korus.office', 'settings_field_list'));
        $settingsFieldList['default']['NAME'] = ["active" => false, "code" => "NAME", "sort" => "0"];
        $settingsFieldList['default']['LAST_NAME'] = ["active" => false, "code" => "LAST_NAME", "sort" => "0"];

        COption::SetOptionString('korus.office', 'settings_field_list', serialize($settingsFieldList));

        $settingsField = OfficeFieldSettingsTable::query()
            ->setSelect(['ID','FIELD_CODE'])
            ->whereIn('FIELD_CODE', ['NAME', 'LAST_NAME'])
            ->fetchAll();

        foreach ($settingsField as $field) {
            OfficeFieldSettingsTable::delete($field['ID']);
        }
    }
}
