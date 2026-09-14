<?php

global $APPLICATION, $REQUEST_METHOD;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserField\Internal\UserFieldHelper;
use Korus\Office\Entity\OfficeFieldSettingsTable;
use Korus\Office\Profile\EmployeeProfileData;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

IncludeModuleLangFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight('korus.office');
if ($POST_RIGHT == 'D') {
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

Loader::includeModule('korus.office');

$allDefaultUserField = [
    /**
     *
     * Исключённые системные поля
     * "ID", "LAST_LOGIN", "TIMESTAMP_X", "LANGUAGE_ID", "IS_ONLINE",
     * "TITLE", "IS_REAL_USER", "IS_ONLINE", "ADMIN_NOTES", "XML_ID",
     * "STORED_HASH", "CHECKWORD_TIME", "EXTERNAL_AUTH_ID",
     * "CONFIRM_CODE", "LOGIN_ATTEMPTS",
     * "PASSWORD", "CHECKWORD", "LID", "AUTO_TIME_ZONE", "TIME_ZONE_OFFSET"
     *
     **/
    "ACTIVE",
    "NAME",
    "LAST_NAME",
    "LOGIN",
    "EMAIL",
    "SECOND_NAME",
    "PERSONAL_BIRTHDAY",
    "DATE_REGISTER",
    "PERSONAL_PROFESSION",
    "PERSONAL_WWW",
    "PERSONAL_ICQ",
    "PERSONAL_GENDER",
    "PERSONAL_PHOTO",
    "PERSONAL_PHONE",
    "PERSONAL_FAX",
    "PERSONAL_MOBILE",
    "PERSONAL_PAGER",
    "PERSONAL_STREET",
    "PERSONAL_MAILBOX",
    "PERSONAL_CITY",
    "PERSONAL_STATE",
    "PERSONAL_ZIP",
    "PERSONAL_COUNTRY",
    "PERSONAL_NOTES",
    "WORK_COMPANY",
    "WORK_DEPARTMENT",
    "WORK_POSITION",
    "WORK_WWW",
    "WORK_PHONE",
    "WORK_FAX",
    "WORK_PAGER",
    "WORK_STREET",
    "WORK_MAILBOX",
    "WORK_CITY",
    "WORK_STATE",
    "WORK_ZIP",
    "WORK_COUNTRY",
    "WORK_PROFILE",
    "WORK_NOTES",
    "LAST_ACTIVITY_DATE",
    "TIME_ZONE"
];

/**
 * @var array $fieldSettingsOverwrite - массив для отключения селекторов настройки для поля
 *
 * Для корректной работы необходимо следить, чтоб поле имело в базе настройки для отключенных селекторов
 */
$fieldSettingsOverwrite = [
    'PERSONAL_PHOTO' => ['VIEW', 'MANAGEMENT_EMPLOYEE', 'VIEW_TYPE', 'VIEW_SORT'],
    'NAME' => ['MANAGEMENT_EMPLOYEE', 'VIEW_TYPE', 'VIEW_SORT'],
    'LAST_NAME' => ['MANAGEMENT_EMPLOYEE', 'VIEW_TYPE', 'VIEW_SORT'],
    'SECOND_NAME' => ['MANAGEMENT_EMPLOYEE', 'VIEW_TYPE', 'VIEW_SORT'],
];

$defaultFieldSettingsSort = EmployeeProfileData::getDefaultSettings();

$userFieldManager = UserFieldHelper::getInstance()->getManager();
$allCustomUserField = $userFieldManager->GetUserFields('USER', 0, LANGUAGE_ID);

unset($allCustomUserField['UF_MOB_PHONE_PER_ACT']);

CJSCore::Init(['jquery']);

$aTabs = [
    [
        'DIV' => 'fields_settings',
        'TAB' => 'Настройка полей личного кабинета',
        'TITLE' => 'Настройка полей личного кабинета'
    ],
    [
        'DIV' => 'fields_list',
        'TAB' => 'Поля для настройки',
        'TITLE' => 'Поля для настройки'
    ]
];
$tabControl = new CAdminTabControl('tabControl', $aTabs);
$ID = intval($_REQUEST['ID']);        // идентификатор редактируемой записи
$message = null;        // сообщение об ошибке
$saveError = false;
$saveAction = false;

// ******************************************************************** //
//                ОБРАБОТКА ИЗМЕНЕНИЙ ФОРМЫ                             //
// ******************************************************************** //
if (
    $REQUEST_METHOD == 'POST' && ($_POST['save'] != '' || $_POST['apply'] != '') &&
    $POST_RIGHT == 'W' && check_bitrix_sessid()
) {
    $optionFieldList = [
        'default' => [],
        'custom' => []
    ];
    foreach ($_POST['OPTION_FIELD_LIST'] as $fieldName => $field) {
        $fieldType = 'custom';
        if (in_array($fieldName, $allDefaultUserField)) {
            $fieldType = 'default';
        }
        $optionFieldList[$fieldType][$fieldName] = [
            'active' => ($field === 'enable'),
            'code' => $fieldName,
        ];
    }

    COption::SetOptionString('korus.office', 'settings_field_list', serialize($optionFieldList));

    if ($defaultFieldSettingsSort) {
        foreach ($fieldSettingsOverwrite as $fieldCode => $settingsName) {
            if (in_array('VIEW_TYPE', $settingsName)) {
                $_POST['VIEW_TYPE'][$fieldCode] = $defaultFieldSettingsSort['VIEW_TYPE'][$fieldCode];
            }
        }
    }

    $fieldSettingsSort = [
        'VIEW_TYPE' => $_POST['VIEW_TYPE'],
        'VIEW_SORT' => $_POST['VIEW_SORT'],
    ];

    CUserOptions::SetOption('korus.office', 'fields_sort', serialize($fieldSettingsSort), true);

    $saveAction = true;
    $fieldSettingsQuery = OfficeFieldSettingsTable::getList([
        'select' => OfficeFieldSettingsTable::getFormSelect()
    ])->fetchAll();

    $fieldSettings = [];
    foreach ($fieldSettingsQuery as $fieldSetting) {
        $fieldSettings[$fieldSetting['FIELD_CODE']] = $fieldSetting;
    }


    foreach ($optionFieldList as $fieldType) {
        foreach ($fieldType as $field) {
            if ($field['active']) {
                $fieldCode = $field['code'];
                $viewValue = $_POST['VIEW'][$fieldCode];
                $editEmployee = $_POST['EDIT_EMPLOYEE'][$fieldCode];
                $managementEmployee = $_POST['MANAGEMENT_EMPLOYEE'][$fieldCode];

                $postSetting = [
                    'FIELD_CODE' => $fieldCode,
                    'VIEW' => $viewValue ??
                        ($fieldSettings[$fieldCode]['VIEW'] ?? OfficeFieldSettingsTable::VIEW['HIDE']),
                    'EDIT_EMPLOYEE' => $editEmployee ??
                        ($fieldSettings[$fieldCode]['EDIT_EMPLOYEE'] ?? OfficeFieldSettingsTable::EDIT_EMPLOYEE['DENY']),
                    'MANAGEMENT_EMPLOYEE' => $managementEmployee ??
                        ($fieldSettings[$fieldCode]['MANAGEMENT_EMPLOYEE'] ?? OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY'])
                ];

                if (isset($fieldSettings[$fieldCode])) {
                    $result = OfficeFieldSettingsTable::update($fieldSettings[$fieldCode]['ID'], [
                        'fields' => $postSetting
                    ]);
                } else {
                    $result = OfficeFieldSettingsTable::add($postSetting);
                }

                if (!$result->isSuccess()) {
                    $saveError = true;
                    $message = new CAdminMessage($result->getErrors()[0]->getMessage());
                    break;
                }
            }
        }
    }

    if (!$saveError) {
        LocalRedirect('/bitrix/admin/korus_office_lk_field_admin.php?success_save=Y');
    }
}

// ******************************************************************** //
//                ВЫБОРКА И ПОДГОТОВКА ДАННЫХ ФОРМЫ                     //
// ******************************************************************** //
$fieldListSettings = COption::GetOptionString('korus.office', 'settings_field_list');
$fieldListSettings = unserialize($fieldListSettings);

$fieldSettingsView = [];
foreach ($fieldListSettings as $fieldSettingType => $fieldList) {
    foreach ($fieldList as $field) {
        if ($field['active']) {
            if ($fieldSettingType === 'custom') {
                $field['name'] = $allCustomUserField[$field['code']]['EDIT_FORM_LABEL'];
            } else {
                $field['name'] = Loc::GetMessage('KORUS_OFFICE_FIELD_' . $field['code']);
            }
            $fieldSettingsView[$field['code']] = $field;
        }
    }
}

$fieldSettingsQuery = OfficeFieldSettingsTable::getList([
    'select' => OfficeFieldSettingsTable::getFormSelect(),
    'filter' => ['FIELD_CODE' => array_keys($fieldSettingsView)]
])->fetchAll();

$fieldSettings = [];
foreach ($fieldSettingsQuery as $fieldSetting) {
    $fieldSettingsView[$fieldSetting['FIELD_CODE']]['settings'] = $fieldSetting;
}
usort($fieldSettingsView, fn($a, $b) => $a['sort'] > $b['sort']);

$fieldSettingsSort = EmployeeProfileData::getFieldSort(0);

if (!$fieldSettingsSort) {
    $fieldSettingsSort = $defaultFieldSettingsSort;
}

if ($fieldSettingsSort) {
    foreach ($fieldSettingsView as &$field) {
        $field['sort'] = $fieldSettingsSort['VIEW_SORT'][$field['code']];
        $field['view_type'] = $fieldSettingsSort['VIEW_TYPE'][$field['code']];
    }
    usort($fieldSettingsView, function ($fieldA, $fieldB) {
        if ($fieldA['view_type'] === $fieldB['view_type']) {
            $typeSort = 0;
        } elseif ($fieldA['view_type'] === 'M') {
            $typeSort = -2;
        } else {
            $typeSort = 2;
        }

        if ($fieldA['sort'] === $fieldB['sort']) {
            $weightSort = 0;
        } elseif ($fieldA['sort'] < $fieldB['sort']) {
            $weightSort = -1;
        } else {
            $weightSort = 1;
        }

        return $typeSort + $weightSort;
    });
}

// ******************************************************************** //
//                ВЫВОД ФОРМЫ                                           //
// ******************************************************************** //

// установим заголовок страницы
$APPLICATION->SetTitle('Настройка полей личного кабинета');

// не забудем разделить подготовку данных и вывод
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

// если есть сообщения об ошибках или об успешном сохранении - выведем их.
if ($_GET['success_save'] === 'Y') {
    CAdminMessage::ShowMessage(['MESSAGE' => 'Настройки сохранены', 'TYPE' => 'OK']);
}

if ($message) {
    echo $message->Show();
}
?>
<form method='POST' class='admin-fields-form' Action='<?= $APPLICATION->GetCurPage() ?>' ENCTYPE='multipart/form-data'
      name='post_form'>
    <?php
    echo bitrix_sessid_post();
    $tabControl->Begin();
    $tabControl->BeginNextTab();
    ?>
    <tr>
        <td colspan="2">
            <div id="default-fields">
                <table border="0" cellspacing="1" cellpadding="3" width="100%" class="internal">
                    <tr>
                        <th>Поле</th>
                        <th>Символьный код</th>
                        <th>Отображение поля</th>
                        <th>Редактирование поля пользователем</th>
                        <th>Разрешить пользователю настраивать видимость поля</th>
                        <th>Сортировка (В личном кабинете пользователя)</th>
                        <th>Вариант отображения</th>
                    </tr>
                    <?php

                    $selectors = [
                        'VIEW' => OfficeFieldSettingsTable::VIEW['ALLOW'],
                        'EDIT_EMPLOYEE' => OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                        'MANAGEMENT_EMPLOYEE' => OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['DENY']
                    ];

                    foreach ($fieldSettingsView as $field) {
                        $fieldCode = $field['code'];
                        $savedSettings = $field['settings'];
                        ?>
                        <tr>
                            <td class='admin-fields-form__title'><?= $field['name'] ?></td>
                            <td><?= $fieldCode ?></td>
                            <?php
                            foreach ($selectors as $selector => $defaultValue) { ?>
                                <td>
                                    <label>
                                        <select name='<?= $selector ?>[<?= $fieldCode ?>]' <?= (in_array(
                                            $selector,
                                            $fieldSettingsOverwrite[$fieldCode] ?? []
                                        )) ? 'disabled' : '' ?>>
                                            <?php
                                            foreach (
                                                constant(
                                                    'Korus\Office\Entity\OfficeFieldSettingsTable::' . $selector
                                                ) as $viewOptionCode => $viewOption
                                            ) {
                                                ?>
                                                <?= Loc::getMessage(
                                                    'KORUS_OFFICE_ADMIN_OPTION_' . $selector . '_' . $viewOptionCode
                                                ) ?>
                                                <option
                                                    value='<?= $viewOption ?>' <?= ($savedSettings[$selector] ? $savedSettings[$selector] == $viewOption : $defaultValue == $viewOption) ? 'selected' : '' ?>>
                                                    <?= Loc::getMessage(
                                                        'KORUS_OFFICE_ADMIN_OPTION_' . $selector . '_' . $viewOptionCode
                                                    ) ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </label>
                                </td>
                                <?php
                            } ?>

                            <td>
                                <label>
                                    <input type="number" name="VIEW_SORT[<?= $fieldCode ?>]"
                                           value="<?= $fieldSettingsSort['VIEW_SORT'][$fieldCode] ?? 0 ?>"
                                        <?= (in_array(
                                            $selector,
                                            $fieldSettingsOverwrite[$fieldCode] ?? []
                                        )) ? 'disabled' : '' ?>
                                    >
                                </label>
                            </td>
                            <td>
                                <label>
                                    <select type="number" name="VIEW_TYPE[<?= $fieldCode ?>]"
                                        <?= (in_array(
                                            'VIEW_TYPE',
                                            $fieldSettingsOverwrite[$fieldCode] ?? []
                                        )) ? 'disabled' : '' ?>>
                                        <option
                                            value="A" <?= ($fieldSettingsSort['VIEW_TYPE'][$fieldCode] === 'A') ? "selected" : '' ?>>
                                            Дополнительная информация
                                        </option>
                                        <option
                                            value="M" <?= ($fieldSettingsSort['VIEW_TYPE'][$fieldCode] === 'M') ? "selected" : '' ?>>
                                            Основная информация
                                        </option>
                                    </select>
                                </label>
                            </td>
                        </tr>
                        <?php
                    } ?>
                </table>

                <?php
                $tabControl->BeginNextTab();
                ?>

                <tr>
                    <td colspan="2">
                        <div id="field_list">
                            <table border="0" cellspacing="1" cellpadding="3" width="100%" class="internal">
                                <tr>
                                    <th>Поле</th>
                                    <th>Символьный код</th>
                                    <th>Настройка поля</th>
                                </tr>
                                <tr class="heading">
                                    <td colspan="4"><b>Стандартные поля</b></td>
                                </tr>
                                <?php
                                echo BeginNote();
                                echo Loc::getMessage('KORUS_OFFICE_ADMIN_FIELD_LIST_NOTE');
                                echo EndNote();
                                foreach ($allDefaultUserField as $defaultUserField) {
                                    showListField(
                                        Loc::GetMessage('KORUS_OFFICE_FIELD_' . $defaultUserField),
                                        $defaultUserField,
                                        $fieldListSettings['default'][$defaultUserField]['active']
                                    );
                                }
                                ?>
                                <tr class="heading">
                                    <td colspan="4"><b>Пользовательские поля</b></td>
                                </tr>
                                <?php
                                foreach ($allCustomUserField as $customUserFieldCode => $customUserField) {
                                    showListField(
                                        $customUserField['EDIT_FORM_LABEL'],
                                        $customUserFieldCode,
                                        $fieldListSettings['custom'][$customUserFieldCode]['active']
                                    );
                                }
                                ?>
                            </table>
                        </div>
                    </td>
                </tr>

                <?php

                /**
                 * @param string|null $fieldName
                 * @param string $fieldCode
                 * @param bool|null $listValue
                 * @param string|null $sortValue
                 * @return void
                 */
                function showListField(
                    ?string $fieldName,
                    string $fieldCode,
                    ?bool $listValue,
                ): void {
                    $fieldName = $fieldName ?? '';
                    $listValue = $listValue ?? 0;
                    ?>
                    <tr>
                        <td>
                            <?= $fieldName ?>
                        </td>
                        <td class="icon-new">
                            <?= $fieldCode ?>
                        </td>
                        <td>
                            <select name="OPTION_FIELD_LIST[<?= $fieldCode ?>]">
                                <option value="enable" <?= ($listValue) ? 'selected' : '' ?>>Включить настройку</option>
                                <option value="disable" <?= (!$listValue) ? 'selected' : '' ?>>Выключить настройку
                                </option>
                            </select>
                        </td>
                    </tr>
                    <?
                }

                // завершение формы - вывод кнопок сохранения изменений
                $tabControl->Buttons(
                    [
                        'disabled' => ($POST_RIGHT < 'W'),
                        'back_url' => 'korus_menu_admin.php?lang=' . LANG,

                    ]
                );
                ?>
                <?php
                // завершаем интерфейс закладок
                $tabControl->End();
                // дополнительное уведомление об ошибках - вывод иконки около поля, в котором возникла ошибка
                $tabControl->ShowWarnings('post_form', $message);
                ?>
</form>
<style>
    .admin-fields-form th {
        border-bottom: 1px solid #d0d7d8;
        color: #3f4b54;
        font-size: 14px;
        height: 15px;
        text-shadow: 0 1px #fff;
        padding: 12px 10px 12px 10px;
        text-align: left;
    }
</style>

<?php
// завершение страницы
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
?>
