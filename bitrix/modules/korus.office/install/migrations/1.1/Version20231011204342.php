<?php
/** @bxnolanginspection */

namespace Sprint\Migration;


use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\UI\Filter\Options;
use Bitrix\Main\UserTable;
use CAdminFormSettings;
use CUserTypeEntity;

class Version20231011204342 extends Version
{
    protected $description = '';

    protected $moduleVersion = '4.6.2';

    public function up(): void
    {
        foreach ($this->getUserFields() as $field) {
            $exist = (bool)$this->loadUserField($field);
            if ($exist) {
                return;
            }
            (new CUserTypeEntity())->Add($field);
        }

        Base::destroy(UserTable::getEntity());

        $tabs = CAdminFormSettings::getTabsArray('user_edit');
        if ($tabs && !(isset($tabs['user_fields_tab']['FIELDS']['UF_COMPETENCE'])
                && isset($tabs['user_fields_tab']['FIELDS']['UF_MESSENGERS']))) {
            /** @noinspection SqlDialectInspection */
            Application::getConnection()->queryExecute(
                'delete from b_user_option where CATEGORY="form" and NAME="user_edit"'
            );

            $tabs['user_fields_tab']['FIELDS']['UF_COMPETENCE'] = 'Компетенции';
            $tabs['user_fields_tab']['FIELDS']['UF_MESSENGERS'] = 'Мессенджеры';
            CAdminFormSettings::setTabsArray('user_edit', $tabs, true);
        }

        global $USER;

        $options = new Options('INTRANET_USER_LIST_s1');
        foreach ([Options::DEFAULT_FILTER, Options::TMP_FILTER] as $type) {
            $filter = $options->getFilterSettings($type);
            if (empty($filter)) {
                $filter = static::getDefaultOptions($type);
            }

            $rows = $filter['filter_rows'];
            $rows = explode(',', $rows);
            if (!in_array('UF_COMPETENCE', $rows)) {
                $rows[] = 'UF_COMPETENCE';
            }

            $data = [
                'for_all' => 'true',
                'rows' => $rows,
            ];

            $options->setFilterSettings($type, $data);
        }

        $optionBackup = Option::get('main', 'user_device_history');
        $needLogout = false;
        if (!$USER->IsAuthorized()) {
            Option::set('main', 'user_device_history', 'N');
            $USER->Authorize(1);
            $needLogout = true;
        }

        $options->saveForAll();

        if ($needLogout) {
            $USER->Logout();
            if ($optionBackup) {
                Option::set('main', 'user_device_history', $optionBackup);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->getUserFields() as $field) {
            $arField = $this->loadUserField($field);
            if ($arField) {
                (new CUserTypeEntity())->Delete($arField['ID']);
            }
        }

        Base::destroy(UserTable::getEntity());
    }

    private static function getDefaultOptions(string $type): array
    {
        $default = [
            'tmp_filter' => [
                'filter_rows' => '',
            ],
            'default_filter' => [
                'fields' => [
                ],
                'filter_rows' => 'LAST_NAME,DEPARTMENT,TAGS',
            ],
        ];

        return $default[$type];
    }

    protected function loadUserField(array $data): ?array
    {
        $db = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => $data['ENTITY_ID'],
                'FIELD_NAME' => $data['FIELD_NAME'],
            ]
        );
        if ($result = $db->Fetch()) {
            return $result;
        }

        return null;
    }

    protected function getUserFields(): array
    {
        return [
            [
                'FIELD_NAME' => 'UF_WORKPLACE',
                'USER_TYPE_ID' => 'integer',
                'ENTITY_ID' => 'USER',
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'Рабочее место',
                    'en' => 'Workplace',
                ],
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Рабочее место',
                    'en' => 'Workplace',
                ],
                'LIST_FILTER_LABEL' => [
                    'ru' => 'Рабочее место',
                    'en' => 'Workplace',
                ],
                'SHOW_FILTER' => 'I',
                'SETTINGS' => [],
            ],
            [
                'FIELD_NAME' => 'UF_MESSENGERS',
                'USER_TYPE_ID' => 'messengers',
                'ENTITY_ID' => 'USER',
                'MULTIPLE' => 'Y',
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'Мессенджеры',
                    'en' => 'Messengers',
                ],
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Мессенджеры',
                    'en' => 'Messengers',
                ],
                'LIST_FILTER_LABEL' => [
                    'ru' => 'Мессенджеры',
                    'en' => 'Messengers',
                ],
                'SHOW_FILTER' => 'S',
                'SETTINGS' => [
                    [
                        'code' => 'telegram',
                        'template' => 'https://t.me/#login#',
                        'value' => 'Telegram',
                    ],
                    [
                        'code' => 'whatsapp',
                        'template' => 'https://wa.me/#login#',
                        'value' => 'WhatsApp',
                    ],
                    [
                        'code' => 'instagram',
                        'template' => 'https://www.instagram.com/#login#',
                        'value' => 'Instagram',
                    ],
                    [
                        'code' => 'vk',
                        'template' => 'https://vk.me/#login#',
                        'value' => 'ВКонтакте',
                    ],
                    [
                        'code' => 'ok',
                        'template' => 'https://ok.ru/profile/#login#',
                        'value' => 'Одноклассники',
                    ],
                ],
            ],
            [
                'FIELD_NAME' => 'UF_HIDE_PERSONAL_PHONE',
                'USER_TYPE_ID' => 'boolean',
                'ENTITY_ID' => 'USER',
                'MULTIPLE' => 'N',
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'Скрыть личный телефон',
                    'en' => 'Hide personal phone',
                ],
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Скрыть личный телефон',
                    'en' => 'Hide personal phone',
                ],
                'LIST_FILTER_LABEL' => [
                    'ru' => 'Скрыть личный телефон',
                    'en' => 'Hide personal phone',
                ],
                'SHOW_FILTER' => 'S',
                'SETTINGS' => [
                    'LABEL' => ['Показать', 'Скрыть'],
                    'DEFAULT_VALUE' => 0,
                    'DISPLAY' => 'CHECKBOX',
                    'LABEL_CHECKBOX' => 'Скрыть',
                ],
            ],
            [
                'FIELD_NAME' => 'UF_EMPLOYEE_ID',
                'USER_TYPE_ID' => 'string',
                'ENTITY_ID' => 'USER',
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'Табельный номер',
                    'en' => 'Employee ID',
                ],
                'EDIT_FORM_LABEL' => [
                    'ru' => 'Табельный номер',
                    'en' => 'Employee ID',
                ],
                'LIST_FILTER_LABEL' => [
                    'ru' => 'Табельный номер',
                    'en' => 'Employee ID',
                ],
                'SHOW_FILTER' => 'I',
                'SETTINGS' => [],
            ],
            [
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_COMPETENCE',
                'USER_TYPE_ID' => 'competence',
                'XML_ID' => 'UF_COMPETENCE',
                'SORT' => 500,
                'MULTIPLE' => 'Y',
                'MANDATORY' => 'N',
                'SHOW_FILTER' => 'S',
                'SHOW_IN_LIST' => 'Y',
                'EDIT_IN_LIST' => 'Y',
                'IS_SEARCHABLE' => 'Y',
                'EDIT_FORM_LABEL' => ['ru' => 'Компетенции'],
                'LIST_COLUMN_LABEL' => ['ru' => 'Компетенции'],
                'LIST_FILTER_LABEL' => ['ru' => 'Компетенции'],
            ],
        ];
    }
}
