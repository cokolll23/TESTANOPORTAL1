<?php

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Config\Option;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\SystemException;
use Bitrix\Main\UI\Filter\Options;
use Bitrix\Main\UrlRewriter;
use Bitrix\Main\UserTable;
use Korus\Main\Helpers\IO;
use Korus\Menu\Helpers\CustomMenu;

Loc::loadMessages(__FILE__);

class korus_office extends CModule
{
    public $MODULE_ID = "korus.office";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_CSS;
    public $errors;
    public $MODULE_GROUP_RIGHTS = 'Y';

    public function __construct()
    {
        $arModuleVersion = [];

        include('version.php');

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        } else {
            $this->MODULE_VERSION = "1.0.0";
            $this->MODULE_VERSION_DATE = "2022-07-25 10:00:00";
        }

        $this->MODULE_NAME = Loc::getMessage("KORUS_OFFICE_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("KORUS_OFFICE_MODULE_DESC");

        $this->PARTNER_NAME = Loc::getMessage('KORUS_OFFICE_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('KORUS_OFFICE_PARTNER_URI');
    }

    /**
     * Установка модуля.
     *
     * @return bool
     * @throws SqlQueryException
     */
    public function doInstall(): bool
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $connection = Application::getConnection();
        $connection->startTransaction();
        try {
            Loader::includeModule($this->MODULE_ID);

            $this->InstallDB();
            $this->installFiles();
            $this->installRouting();

            $connection->commitTransaction();

            return true;
        } catch (Exception $e) {
            $connection->rollbackTransaction();

            ModuleManager::unRegisterModule($this->MODULE_ID);
            global $APPLICATION;
            $APPLICATION->ThrowException($e->getMessage());

            throw $e;
        }
    }

    public function doUnInstall()
    {
        Loader::includeModule($this->MODULE_ID);

        $connection = Application::getConnection();
        $connection->startTransaction();
        try {
            $this->unInstallRouting();
            $this->uninstallFiles();
            $this->unInstallDB();

            ModuleManager::unRegisterModule($this->MODULE_ID);

            if (Loader::includeModule('korus.menu')) {
                CustomMenu::update();
            }

            $connection->commitTransaction();

            return true;
        } catch (Exception $e) {
            $connection->rollbackTransaction();

            ShowError($e->getMessage());

            return false;
        }
    }

    public function InstallDB()
    {
        Loader::requireModule('sprint.migration');

        $migrationsDir = __DIR__ . '/migrations';
        $directory = new DirectoryIterator($migrationsDir);

        $versions = [];
        /** @var DirectoryIterator $item */
        foreach ($directory as $item) {
            if ($item->isDot() || $item->getFilename() == 'dev') {
                continue;
            }

            if ($item->isDir()) {
                $versions[] = $item->getFilename();
            }
        }

        sort($versions, SORT_NUMERIC);

        $versions[] = 'dev';

        try {
            foreach ($versions as $version) {
                (new Sprint\Migration\Installer(
                    [
                        'migration_table' => 'korus_office_migrations',
                        'migration_dir' => $migrationsDir . '/' . $version,
                        'migration_dir_absolute' => true,
                    ]
                ))->up();
            }
        } catch (Exception $e) {
            global $APPLICATION;
            $APPLICATION->ThrowException($e->getMessage());
            return false;
        }
    }

    public function unInstallDB()
    {
        $arIblock = IblockTable::getList([
            'filter' => ['CODE' => ['KORUS_BADGES', 'KORUS_BADGES_USERS']],
            'select' => ['ID'],
        ]);
        foreach ($arIblock as $iblock) {
            CIBlock::Delete($iblock['ID']);
        }
        CIBlockType::Delete('badges');
        $this->unInstallUserFields();
        $this->unInstallEvents();
    }

    public function installFiles()
    {
        $root = Application::getDocumentRoot();

        CopyDirFiles(__DIR__ . '/public', $root, true, true);
        CopyDirFiles(__DIR__ . '/bitrix', $root . '/bitrix', true, true);
        CopyDirFiles(__DIR__ . '/k-team', $root . KTEAM_REPOSITORY, true, true);
    }

    public function unInstallFiles()
    {
        $root = Application::getDocumentRoot();

        static::deleteDirFiles(__DIR__ . '/k-team', $root . KTEAM_REPOSITORY);
        static::deleteDirFiles(__DIR__ . '/bitrix', $root . '/bitrix');
        static::deleteDirFiles(__DIR__ . '/public', $root);
    }

    protected static function deleteDirFiles(string $fromDir, string $toDir)
    {
        if (!is_dir($fromDir)) {
            return;
        }

        $d = dir($fromDir);
        while ($entry = $d->read()) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            if (is_dir($fromDir . '/' . $entry)) {
                static::deleteDirFiles($fromDir . '/' . $entry, $toDir . '/' . $entry);
            } else {
                @unlink($toDir . '/' . $entry);
            }
        }
        $d->close();
        $destEntries = IO::getDirEntries($toDir);
        if (empty($destEntries)) {
            @rmdir($toDir);
        }
    }

    public function installRouting()
    {
        $config = Configuration::getInstance();
        $routing = $config->get('routing');

        $config->add('routing', [
            'config' => array_merge(
                (array)$routing['config'],
                static::getDirFiles(__DIR__ . '/bitrix/routes')
            ),
        ]);
        $config->saveConfiguration();

        foreach (static::getRewriteRules() as $rule) {
            UrlRewriter::add(SITE_ID, $rule);
        }
    }

    public function unInstallRouting()
    {
        $config = Configuration::getInstance();
        $routing = $config->get('routing');

        $config->add('routing', [
            'config' => array_diff(
                (array)$routing['config'],
                static::getDirFiles(__DIR__ . '/bitrix/routes')
            ),
        ]);
        $config->saveConfiguration();

        foreach (static::getRewriteRules() as $rule) {
            UrlRewriter::delete(SITE_ID, ['CONDITION' => $rule['CONDITION']]);
        }
    }

    public static function getDirFiles(string $dir): array
    {
        return (array)array_diff(scandir($dir), ['.', '..']);
    }

    public static function getRewriteRules(): array
    {
        return [

        ];
    }

    /**
     * Регистрирует события module2module.
     *
     * @return void
     */
    function installEvents()
    {
        foreach ($this->getEvents() as $event) {
            RegisterModuleDependences(...$event);
        }
    }

    /**
     * Удаляет события module2module.
     *
     * @return void
     */
    public function unInstallEvents()
    {
        foreach ($this->getEvents() as $event) {
            UnRegisterModuleDependences(...$event);
        }
    }

    /**
     * Возвращает список событий module2module.
     *
     * @return array
     */
    private function getEvents(): array
    {
        return [
            // генерация пунктов меню
            [
                "korus.menu",
                "OnGenericMenu",
                $this->MODULE_ID,
                Korus\Office\EventHandlers\OnGenericMenu::class,
                "getGenericMenu",
                200,
            ],
        ];
    }

    /**
     * Устанавливает пользовательские поля.
     *
     * @return void
     * @throws ArgumentException
     * @throws SystemException
     */
    public function installUserFields()
    {
        foreach (static::getUserFields() as $field) {
            $exist = (bool)static::loadUserField($field);
            if ($exist) {
                continue;
            }

            (new CUserTypeEntity())->Add($field);
        }

        Base::destroy(UserTable::getEntity());

        $tabs = CAdminFormSettings::getTabsArray('user_edit');
        if ($tabs && !(isset($tabs['user_fields_tab']['FIELDS']['UF_COMPETENCE']) && isset($tabs['user_fields_tab']['FIELDS']['UF_MESSENGERS']))) {
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

    /**
     * Удаляет пользвоательские поля.
     *
     * @return void
     * @throws ArgumentException
     * @throws SystemException
     */
    public function unInstallUserFields()
    {
        foreach (static::getUserFields() as $field) {
            $arField = static::loadUserField($field);
            if ($arField) {
                (new CUserTypeEntity())->Delete($arField['ID']);
            }
        }

        Base::destroy(UserTable::getEntity());
    }

    /**
     * Возвращает список пользовательских полей.
     *
     * @return array[]
     */
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

    /**
     * Загружает пользовательское поле из БД.
     *
     * @param array $data
     * @return array|null
     */
    protected static function loadUserField(array $data): ?array
    {
        $db = CUserTypeEntity::GetList(
            [],
            [
                "ENTITY_ID" => $data["ENTITY_ID"],
                "FIELD_NAME" => $data["FIELD_NAME"],
            ]
        );
        if ($result = $db->Fetch()) {
            return $result;
        }

        return null;
    }

    public function activate(): void
    {
        $this->InstallFiles();
    }

    public function unActivate(): void
    {
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}
