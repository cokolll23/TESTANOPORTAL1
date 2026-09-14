<?php

namespace Sprint\Migration;


class Version20231013091657 extends Version
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
            'NAME' => 'BusinessBadges',
            'TABLE_NAME' => 'korus_business_badges',
            'LANG' =>
                [
                    'ru' =>
                        [
                            'NAME' => 'Бизнес-значки',
                        ],
                    'en' =>
                        [
                            'NAME' => 'Business signs',
                        ],
                ],
        ]);
        $helper->Hlblock()->saveField($hlblockId, [
            'FIELD_NAME' => 'UF_NAME',
            'USER_TYPE_ID' => 'string',
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
                    'SIZE' => 20,
                    'ROWS' => 1,
                    'REGEXP' => '',
                    'MIN_LENGTH' => 0,
                    'MAX_LENGTH' => 255,
                    'DEFAULT_VALUE' => '',
                ],
            'EDIT_FORM_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Название',
                ],
            'LIST_COLUMN_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Название',
                ],
            'LIST_FILTER_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Название',
                ],
            'ERROR_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => 'Название',
                ],
            'HELP_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => '',
                ],
        ]);
        $helper->Hlblock()->saveField($hlblockId, [
            'FIELD_NAME' => 'UF_IMG',
            'USER_TYPE_ID' => 'file',
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
                    'SIZE' => 20,
                    'LIST_WIDTH' => 0,
                    'LIST_HEIGHT' => 0,
                    'MAX_SHOW_SIZE' => 0,
                    'MAX_ALLOWED_SIZE' => 0,
                    'EXTENSIONS' =>
                        [
                        ],
                    'TARGET_BLANK' => 'Y',
                ],
            'EDIT_FORM_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Картинка',
                ],
            'LIST_COLUMN_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Картинка',
                ],
            'LIST_FILTER_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Картинка',
                ],
            'ERROR_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => 'Картинка',
                ],
            'HELP_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => '',
                ],
        ]);
        $helper->Hlblock()->saveField($hlblockId, [
            'FIELD_NAME' => 'UF_DESCRIPTION',
            'USER_TYPE_ID' => 'string',
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
                    'SIZE' => 20,
                    'ROWS' => 1,
                    'REGEXP' => '',
                    'MIN_LENGTH' => 0,
                    'MAX_LENGTH' => 0,
                    'DEFAULT_VALUE' => '',
                ],
            'EDIT_FORM_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Описание',
                ],
            'LIST_COLUMN_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Описание',
                ],
            'LIST_FILTER_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Описание',
                ],
            'ERROR_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => 'Описание',
                ],
            'HELP_MESSAGE' =>
                [
                    'en' => '',
                    'ru' => '',
                ],
        ]);
        $helper->Hlblock()->saveField($hlblockId, [
            'FIELD_NAME' => 'UF_COLOR',
            'USER_TYPE_ID' => 'string',
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
                    'SIZE' => 20,
                    'ROWS' => 1,
                    'REGEXP' => '',
                    'MIN_LENGTH' => 0,
                    'MAX_LENGTH' => 0,
                    'DEFAULT_VALUE' => '',
                ],
            'EDIT_FORM_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Цвет',
                ],
            'LIST_COLUMN_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Цвет',
                ],
            'LIST_FILTER_LABEL' =>
                [
                    'en' => '',
                    'ru' => 'Цвет',
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
