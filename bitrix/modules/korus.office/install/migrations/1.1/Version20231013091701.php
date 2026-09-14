<?php

namespace Sprint\Migration;


class Version20231013091701 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.3.2";

    /**
     * @return bool|void
     * @throws Exceptions\HelperException
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $hlblockId = $helper->Hlblock()->saveHlblock([
            'NAME' => 'UsersBusinessBadges',
            'TABLE_NAME' => 'korus_users_business_badges',
            'LANG' =>
                [
                    'ru' =>
                        [
                            'NAME' => 'Бизнес-значки пользователей',
                        ],
                    'en' =>
                        [
                            'NAME' => 'Users business signs',
                        ],
                ],
        ]);
        $helper->Hlblock()->saveField($hlblockId, [
            'FIELD_NAME' => 'UF_USER',
            'USER_TYPE_ID' => 'employee',
            'XML_ID' => '',
            'SORT' => '100',
            'MULTIPLE' => 'N',
            'MANDATORY' => 'Y',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' =>
                [
                ],
            'EDIT_FORM_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Сотрудник',
                ],
            'LIST_COLUMN_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Сотрудник',
                ],
            'LIST_FILTER_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Сотрудник',
                ],
            'ERROR_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => '',
                ],
            'HELP_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => '',
                ],
        ]);
        $helper->Hlblock()->saveField($hlblockId, [
            'FIELD_NAME' => 'UF_BUSINESS_SIGNS',
            'USER_TYPE_ID' => 'hlblock',
            'XML_ID' => '',
            'SORT' => '100',
            'MULTIPLE' => 'N',
            'MANDATORY' => 'Y',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' =>
                [
                    'DISPLAY' => 'LIST',
                    'LIST_HEIGHT' => 1,
                    'HLBLOCK_ID' => 'BusinessBadges',
                    'HLFIELD_ID' => 'UF_NAME',
                    'DEFAULT_VALUE' => 0,
                ],
            'EDIT_FORM_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Бизнес значок',
                ],
            'LIST_COLUMN_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Бизнес значок',
                ],
            'LIST_FILTER_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Бизнес значок',
                ],
            'ERROR_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => '',
                ],
            'HELP_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => '',
                ],
        ]);
        $helper->Hlblock()->saveField($hlblockId, [
            'FIELD_NAME' => 'UF_DATE',
            'USER_TYPE_ID' => 'date',
            'XML_ID' => '',
            'SORT' => '100',
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' =>
                [
                    'DEFAULT_VALUE' =>
                        [
                            'TYPE' => 'NOW',
                            'VALUE' => '',
                        ],
                ],
            'EDIT_FORM_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Дата',
                ],
            'LIST_COLUMN_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Дата',
                ],
            'LIST_FILTER_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Дата',
                ],
            'ERROR_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => '',
                ],
            'HELP_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => '',
                ],
        ]);
    }

    public function down()
    {
        //your code ...
    }
}
