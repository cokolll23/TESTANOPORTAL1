<?php

namespace Sprint\Migration;


use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use COption;
use Korus\Office\Entity\OfficeFieldSettingsTable;

class Version20231211005050 extends Version
{
    protected $description = "Стандартные конфиги для менеджмента полей";

    protected $moduleVersion = "4.3.2";

    public function up()
    {
        Loader::includeModule('korus.office');
        COption::SetOptionString('korus.office', 'settings_field_list', serialize($this->getDefaultFieldList()));

        Application::getConnection()->truncateTable(OfficeFieldSettingsTable::getTableName());
        OfficeFieldSettingsTable::addMulti(
            $this->getDefaultFieldSettings()
        );
    }

    public function down()
    {
        Loader::includeModule('korus.office');
        COption::SetOptionString('korus.office', 'settings_field_list', '');
        Application::getConnection()->truncateTable(OfficeFieldSettingsTable::getTableName());
    }

    private function getDefaultFieldList(): array
    {
        return [
            "default" => [
                "SECOND_NAME" => ["active" => true, "code" => "SECOND_NAME", "sort" => "100"],
                "EMAIL" => ["active" => true, "code" => "EMAIL", "sort" => "101"],
                "PERSONAL_PHOTO" => ["active" => true, "code" => "PERSONAL_PHOTO", "sort" => "102"],
                "PERSONAL_BIRTHDAY" => ["active" => true, "code" => "PERSONAL_BIRTHDAY", "sort" => "103"],
                "PERSONAL_PHONE" => ["active" => true, "code" => "PERSONAL_PHONE", "sort" => "104"],
                "PERSONAL_MOBILE" => ["active" => true, "code" => "PERSONAL_MOBILE", "sort" => "105"],
                "WORK_PHONE" => ["active" => true, "code" => "WORK_PHONE", "sort" => "106"],
                "PERSONAL_WWW" => ["active" => true, "code" => "PERSONAL_WWW", "sort" => "107"],
                "PERSONAL_GENDER" => ["active" => true, "code" => "PERSONAL_GENDER", "sort" => "108"],
                "PERSONAL_STREET" => ["active" => true, "code" => "PERSONAL_STREET", "sort" => "109"],
                "PERSONAL_CITY" => ["active" => true, "code" => "PERSONAL_CITY", "sort" => "110"],
                "PERSONAL_STATE" => ["active" => true, "code" => "PERSONAL_STATE", "sort" => "111"],
                "PERSONAL_ZIP" => ["active" => true, "code" => "PERSONAL_ZIP", "sort" => "112"],
                "PERSONAL_COUNTRY" => ["active" => true, "code" => "PERSONAL_COUNTRY", "sort" => "113"],
                "WORK_COUNTRY" => ["active" => true, "code" => "WORK_COUNTRY", "sort" => "114"],

                "ID" => ["active" => false, "code" => "ID", "sort" => "0"],
                "ACTIVE" => ["active" => false, "code" => "ACTIVE", "sort" => "0"],
                "LAST_LOGIN" => ["active" => false, "code" => "LAST_LOGIN", "sort" => "0"],
                "LOGIN" => ["active" => false, "code" => "LOGIN", "sort" => "0"],
                "NAME" => ["active" => false, "code" => "NAME", "sort" => "0"],
                "LAST_NAME" => ["active" => false, "code" => "LAST_NAME", "sort" => "0"],
                "TIMESTAMP_X" => ["active" => false, "code" => "TIMESTAMP_X", "sort" => "0"],
                "IS_ONLINE" => ["active" => false, "code" => "IS_ONLINE", "sort" => "0"],
                "IS_REAL_USER" => ["active" => false, "code" => "IS_REAL_USER", "sort" => "0"],
                "DATE_REGISTER" => ["active" => false, "code" => "DATE_REGISTER", "sort" => "0"],
                "PERSONAL_PROFESSION" => ["active" => false, "code" => "PERSONAL_PROFESSION", "sort" => "0"],
                "PERSONAL_ICQ" => ["active" => false, "code" => "PERSONAL_ICQ", "sort" => "0"],
                "PERSONAL_FAX" => ["active" => false, "code" => "PERSONAL_FAX", "sort" => "0"],
                "PERSONAL_PAGER" => ["active" => false, "code" => "PERSONAL_PAGER", "sort" => "0"],
                "PERSONAL_MAILBOX" => ["active" => false, "code" => "PERSONAL_MAILBOX", "sort" => "0"],
                "PERSONAL_NOTES" => ["active" => false, "code" => "PERSONAL_NOTES", "sort" => "0"],
                "WORK_COMPANY" => ["active" => false, "code" => "WORK_COMPANY", "sort" => "0"],
                "WORK_DEPARTMENT" => ["active" => false, "code" => "WORK_DEPARTMENT", "sort" => "0"],
                "WORK_POSITION" => ["active" => false, "code" => "WORK_POSITION", "sort" => "0"],
                "WORK_WWW" => ["active" => false, "code" => "WORK_WWW", "sort" => "0"],
                "WORK_FAX" => ["active" => false, "code" => "WORK_FAX", "sort" => "0"],
                "WORK_PAGER" => ["active" => false, "code" => "WORK_PAGER", "sort" => "0"],
                "WORK_STREET" => ["active" => false, "code" => "WORK_STREET", "sort" => "0"],
                "WORK_MAILBOX" => ["active" => false, "code" => "WORK_MAILBOX", "sort" => "0"],
                "WORK_CITY" => ["active" => false, "code" => "WORK_CITY", "sort" => "0"],
                "WORK_STATE" => ["active" => false, "code" => "WORK_STATE", "sort" => "0"],
                "WORK_ZIP" => ["active" => false, "code" => "WORK_ZIP", "sort" => "0"],
                "WORK_PROFILE" => ["active" => false, "code" => "WORK_PROFILE", "sort" => "0"],
                "WORK_NOTES" => ["active" => false, "code" => "WORK_NOTES", "sort" => "0"],
                "ADMIN_NOTES" => ["active" => false, "code" => "ADMIN_NOTES", "sort" => "0"],
                "XML_ID" => ["active" => false, "code" => "XML_ID", "sort" => "0"],
                "STORED_HASH" => ["active" => false, "code" => "STORED_HASH", "sort" => "0"],
                "CHECKWORD_TIME" => ["active" => false, "code" => "CHECKWORD_TIME", "sort" => "0"],
                "EXTERNAL_AUTH_ID" => ["active" => false, "code" => "EXTERNAL_AUTH_ID", "sort" => "0"],
                "CONFIRM_CODE" => ["active" => false, "code" => "CONFIRM_CODE", "sort" => "0"],
                "LOGIN_ATTEMPTS" => ["active" => false, "code" => "LOGIN_ATTEMPTS", "sort" => "0"],
                "LAST_ACTIVITY_DATE" => ["active" => false, "code" => "LAST_ACTIVITY_DATE", "sort" => "0"],
                "AUTO_TIME_ZONE" => ["active" => false, "code" => "AUTO_TIME_ZONE", "sort" => "0"],
                "TIME_ZONE" => ["active" => false, "code" => "TIME_ZONE", "sort" => "0"],
                "TIME_ZONE_OFFSET" => ["active" => false, "code" => "TIME_ZONE_OFFSET", "sort" => "0"],
                "PASSWORD" => ["active" => false, "code" => "PASSWORD", "sort" => "0"],
                "CHECKWORD" => ["active" => false, "code" => "CHECKWORD", "sort" => "0"],
                "LID" => ["active" => false, "code" => "LID", "sort" => "0"],
            ],
            "custom" => [
                "UF_WORKPLACE" => ["active" => true, "code" => "UF_WORKPLACE", "sort" => "201"],
                "UF_PHONE_INNER" => ["active" => true, "code" => "UF_PHONE_INNER", "sort" => "202"],
                "UF_MOB_PHONE_PER" => ["active" => true, "code" => "UF_MOB_PHONE_PER", "sort" => "203"],
                "UF_MESSENGERS" => ["active" => true, "code" => "UF_MESSENGERS", "sort" => "204"],
                "UF_SKYPE" => ["active" => true, "code" => "UF_SKYPE", "sort" => "205"],
                "UF_ZOOM" => ["active" => true, "code" => "UF_ZOOM", "sort" => "206"],
                "UF_TWITTER" => ["active" => true, "code" => "UF_TWITTER", "sort" => "207"],
                "UF_FACEBOOK" => ["active" => true, "code" => "UF_FACEBOOK", "sort" => "208"],
                "UF_LINKEDIN" => ["active" => true, "code" => "UF_LINKEDIN", "sort" => "209"],
                "UF_WEB_SITES" => ["active" => true, "code" => "UF_WEB_SITES", "sort" => "210"],
                "UF_EMPLOYMENT_DATE" => ["active" => true, "code" => "UF_EMPLOYMENT_DATE", "sort" => "211"],
                "UF_SKILLS" => ["active" => true, "code" => "UF_SKILLS", "sort" => "212"],
                "UF_EMPLOYEE_ID" => ["active" => true, "code" => "UF_EMPLOYEE_ID", "sort" => "213"],
                "UF_ASSISTANT" => ["active" => true, "code" => "UF_ASSISTANT", "sort" => "214"],

                "UF_INTERESTS" => ["active" => false, "code" => "UF_INTERESTS", "sort" => "0"],
                "UF_DEPARTMENT" => ["active" => false, "code" => "UF_DEPARTMENT", "sort" => "0"],
                "UF_USER_CRM_ENTITY" => ["active" => false, "code" => "UF_USER_CRM_ENTITY", "sort" => "0"],
                "UF_IM_SEARCH" => ["active" => false, "code" => "UF_IM_SEARCH", "sort" => "0"],
                "UF_CONNECTOR_MD5" => ["active" => false, "code" => "UF_CONNECTOR_MD5", "sort" => "0"],
                "UF_1C" => ["active" => false, "code" => "UF_1C", "sort" => "0"],
                "UF_INN" => ["active" => false, "code" => "UF_INN", "sort" => "0"],
                "UF_DISTRICT" => ["active" => false, "code" => "UF_DISTRICT", "sort" => "0"],
                "UF_SKYPE_LINK" => ["active" => false, "code" => "UF_SKYPE_LINK", "sort" => "0"],
                "UF_XING" => ["active" => false, "code" => "UF_XING", "sort" => "0"],
                "UF_WORK_BINDING" => ["active" => false, "code" => "UF_WORK_BINDING", "sort" => "0"],
                "UF_BXDAVEX_CALSYNC" => ["active" => false, "code" => "UF_BXDAVEX_CALSYNC", "sort" => "0"],
                "UF_HIDE_PERSONAL_PHONE" => ["active" => false, "code" => "UF_HIDE_PERSONAL_PHONE", "sort" => "0"],
                "UF_EMOJI" => ["active" => false, "code" => "UF_EMOJI", "sort" => "0"],
                "UF_SHOW_I_AM_HERE" => ["active" => false, "code" => "UF_SHOW_I_AM_HERE", "sort" => "0"],
                "UF_AVAILABILITY" => ["active" => false, "code" => "UF_AVAILABILITY", "sort" => "0"],
                "UF_EXPERTISE" => ["active" => false, "code" => "UF_EXPERTISE", "sort" => "0"],
                "UF_CONTACT_PERSONS" => ["active" => false, "code" => "UF_CONTACT_PERSONS", "sort" => "0"],
                "UF_MOB_PHONE_PER_ACT" => ["active" => false, "code" => "UF_MOB_PHONE_PER_ACT", "sort" => "0"],
                "UF_VACATION_DAYS" => ["active" => false, "code" => "UF_VACATION_DAYS", "sort" => "0"],
                "UF_SIMPLE_APPROVE" => ["active" => false, "code" => "UF_SIMPLE_APPROVE", "sort" => "0"],
                "UF_DISABLE_RULE" => ["active" => false, "code" => "UF_DISABLE_RULE", "sort" => "0"],
                "UF_COMPETENCE" => ["active" => false, "code" => "UF_COMPETENCE", "sort" => "0"],
                "UF_TIMEMAN" => ["active" => false, "code" => "UF_TIMEMAN", "sort" => "0"],
                "UF_TM_MAX_START" => ["active" => false, "code" => "UF_TM_MAX_START", "sort" => "0"],
                "UF_TM_MIN_FINISH" => ["active" => false, "code" => "UF_TM_MIN_FINISH", "sort" => "0"],
                "UF_TM_MIN_DURATION" => ["active" => false, "code" => "UF_TM_MIN_DURATION", "sort" => "0"],
                "UF_TM_REPORT_REQ" => ["active" => false, "code" => "UF_TM_REPORT_REQ", "sort" => "0"],
                "UF_TM_REPORT_TPL" => ["active" => false, "code" => "UF_TM_REPORT_TPL", "sort" => "0"],
                "UF_TM_FREE" => ["active" => false, "code" => "UF_TM_FREE", "sort" => "0"],
                "UF_TM_TIME" => ["active" => false, "code" => "UF_TM_TIME", "sort" => "0"],
                "UF_TM_DAY" => ["active" => false, "code" => "UF_TM_DAY", "sort" => "0"],
                "UF_TM_REPORT_DATE" => ["active" => false, "code" => "UF_TM_REPORT_DATE", "sort" => "0"],
                "UF_REPORT_PERIOD" => ["active" => false, "code" => "UF_REPORT_PERIOD", "sort" => "0"],
                "UF_DELAY_TIME" => ["active" => false, "code" => "UF_DELAY_TIME", "sort" => "0"],
                "UF_LAST_REPORT_DATE" => ["active" => false, "code" => "UF_LAST_REPORT_DATE", "sort" => "0"],
                "UF_SETTING_DATE" => ["active" => false, "code" => "UF_SETTING_DATE", "sort" => "0"],
                "UF_TM_ALLOWED_DELTA" => ["active" => false, "code" => "UF_TM_ALLOWED_DELTA", "sort" => "0"]
            ]
        ];
    }

    function getDefaultFieldSettings(): array
    {
        return [
            [
                "FIELD_CODE" => "SECOND_NAME",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "EMAIL",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "PERSONAL_PHOTO",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "PERSONAL_BIRTHDAY",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "PERSONAL_PHONE",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "PERSONAL_MOBILE",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "WORK_PHONE",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "PERSONAL_WWW",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "PERSONAL_GENDER",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "PERSONAL_STREET",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "PERSONAL_CITY",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "PERSONAL_STATE",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "PERSONAL_ZIP",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "PERSONAL_COUNTRY",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "WORK_COUNTRY",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_PHONE_INNER",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_SKYPE",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_ZOOM",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_TWITTER",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_FACEBOOK",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_LINKEDIN",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_WEB_SITES",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_SKILLS",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_EMPLOYMENT_DATE",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_WORKPLACE",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_MESSENGERS",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_EMPLOYEE_ID",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_MOB_PHONE_PER",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ],
            [
                "FIELD_CODE" => "UF_ASSISTANT",
                "VIEW" => OfficeFieldSettingsTable::VIEW['SHOW'],
                "EDIT_EMPLOYEE" => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                "MANAGEMENT_EMPLOYEE" => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
            ]
        ];
    }
}
