<?php

namespace Sprint\Migration;


class Version20231012094249 extends Version
{
    protected $description = "Добавляет поле для сохранения emoji статуса";

    protected $moduleVersion = "4.3.2";

    /**
     * @return bool|void
     * @throws Exceptions\HelperException
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->UserTypeEntity()->saveUserTypeEntity([
            'ENTITY_ID' => 'USER',
            'FIELD_NAME' => 'UF_EMOJI',
            'USER_TYPE_ID' => 'integer',
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
                    'MIN_VALUE' => 0,
                    'MAX_VALUE' => 0,
                    'DEFAULT_VALUE' => NULL,
                ],
            'EDIT_FORM_LABEL' =>
                [
                    'en' => 'Emoji',
                    'ru' => 'Эмодзи',
                ],
            'LIST_COLUMN_LABEL' =>
                [
                    'en' => 'Emoji',
                    'ru' => 'Эмодзи',
                ],
            'LIST_FILTER_LABEL' =>
                [
                    'en' => 'Emoji',
                    'ru' => 'Эмодзи',
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

    /**
     * @return void
     * @throws Exceptions\HelperException
     */
    public function down(): void
    {
        $helper = $this->getHelperManager();
        $helper->UserTypeEntity()->deleteUserTypeEntity('USER','UF_EMOJI');
    }
}
