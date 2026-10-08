<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
?>
<style>
    .department-picker {
        position: relative;
    }

    .department-picker-button {
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-align: left;
        background: #fff;
    }

    .department-picker-button:hover {
        background: #f8f9fa;
    }

    .department-picker-menu {
        position: absolute;
        z-index: 1060;
        top: calc(100% + 4px);
        left: 0;
        width: 100%;
        max-height: 360px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
    }

    .department-tree-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .department-tree-list .department-tree-list {
        margin-left: 22px;
        padding-top: 4px;
    }

    .department-tree-item {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        margin-bottom: 3px;
    }

    .department-tree-toggle,
    .department-tree-spacer {
        flex: 0 0 24px;
        width: 24px;
        height: 30px;
    }

    .department-tree-toggle {
        padding: 0;
        border: 0;
        border-radius: 4px;
        background: transparent;
        color: #6c757d;
        cursor: pointer;
        font-size: 18px;
        line-height: 30px;
    }

    .department-tree-toggle:hover {
        background: #e9ecef;
    }

    .department-tree-node {
        flex: 1;
        min-height: 30px;
        padding: 5px 8px;
        border: 0;
        border-radius: 5px;
        background: transparent;
        color: #212529;
        cursor: pointer;
        text-align: left;
    }

    .department-tree-node:hover,
    .department-tree-node.is-selected {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .department-tree-children {
        flex: 1 1 100%;
    }
</style>

<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

use Bitrix\Main\Loader;

global $USER;

if (!Loader::includeModule('iblock')) {
    ShowError('Не удалось подключить модуль инфоблоков.');
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
    return;
}

if (!$USER->IsAuthorized()) {
    ShowError('Для заполнения формы необходимо авторизоваться.');
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
    return;
}

/**
 * Экранирование значений
 */
$e = static function ($value): string {
    return htmlspecialcharsbx((string)$value);
};

/**
 * Получение ФИО пользователя
 */
$getUserName = static function (array $user): string {
    $name = trim(implode(' ', array_filter([
            $user['LAST_NAME'] ?? '',
            $user['NAME'] ?? '',
            $user['SECOND_NAME'] ?? '',
    ])));

    return $name ?: ($user['LOGIN'] ?? '');
};

/**
 * Текущий пользователь
 */
$currentUserId = (int)$USER->GetID();
$currentUser = CUser::GetByID($currentUserId)->Fetch() ?: [];

$defaultFio = $getUserName($currentUser);
$defaultEmail = (string)($currentUser['EMAIL'] ?? '');

$userDepartmentIds = $currentUser['UF_DEPARTMENT'] ?? [];

if (!is_array($userDepartmentIds)) {
    $userDepartmentIds = [$userDepartmentIds];
}

$userDepartmentIds = array_values(array_filter(array_map(
        'intval',
        $userDepartmentIds
)));

$defaultDepartmentId = (int)($userDepartmentIds[0] ?? 0);

/**
 * Ищем инфоблок по символьному коду departments
 */
$iblock = CIBlock::GetList(
        [],
        [
                '=CODE' => 'departments',
                'ACTIVE' => 'Y',
        ]
)->Fetch();

$iblockId = (int)($iblock['ID'] ?? 0);

if (!$iblockId) {
    ShowError('Инфоблок с кодом departments не найден.');
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
    return;
}

/**
 * Загружаем разделы и поле UF_HEAD
 */
$sectionsById = [];

$sectionResult = CIBlockSection::GetList(
        [
                'LEFT_MARGIN' => 'ASC',
        ],
        [
                'IBLOCK_ID' => $iblockId,
                'ACTIVE' => 'Y',
        ],
        false,
        [
                'ID',
                'IBLOCK_SECTION_ID',
                'NAME',
                'DEPTH_LEVEL',
                'UF_HEAD',
        ]
);

while ($section = $sectionResult->Fetch()) {
    $sectionId = (int)$section['ID'];

    $headValue = $section['UF_HEAD'] ?? '';

    if (is_array($headValue)) {
        $headValue = reset($headValue);
    }

    $sectionsById[$sectionId] = [
            'ID' => $sectionId,
            'PARENT_ID' => (int)$section['IBLOCK_SECTION_ID'],
            'NAME' => (string)$section['NAME'],
            'DEPTH_LEVEL' => (int)$section['DEPTH_LEVEL'],
            'HEAD_ID' => (int)$headValue,
            'MANAGER_NAME' => '',
            'CHILDREN' => [],
    ];
}

/**
 * Получаем ФИО руководителей
 */
$headIds = [];

foreach ($sectionsById as $section) {
    if ((int)$section['HEAD_ID'] > 0) {
        $headIds[] = (int)$section['HEAD_ID'];
    }
}

$headIds = array_unique($headIds);
$headUsers = [];

foreach ($headIds as $headId) {
    $headUser = CUser::GetByID($headId)->Fetch();

    if ($headUser) {
        $headUsers[$headId] = $getUserName($headUser);
    }
}

foreach ($sectionsById as &$section) {
    $headId = (int)$section['HEAD_ID'];
    $section['MANAGER_NAME'] = $headUsers[$headId] ?? '';
}
unset($section);

/**
 * Строим дерево разделов
 */
$tree = [];

foreach ($sectionsById as $sectionId => &$section) {
    $parentId = (int)$section['PARENT_ID'];

    if ($parentId && isset($sectionsById[$parentId])) {
        $sectionsById[$parentId]['CHILDREN'][] = &$section;
    } else {
        $tree[] = &$section;
    }
}
unset($section);

/**
 * Значения формы
 */
$fioValue = $defaultFio;
$emailValue = $defaultEmail;
$dateFromValue = '';
$dateToValue = '';
$selectedDepartmentId = $defaultDepartmentId;

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fioValue = trim((string)($_POST['FIO'] ?? ''));
    $emailValue = trim((string)($_POST['EMAIL'] ?? ''));
    $dateFromValue = trim((string)($_POST['DATE_FROM'] ?? ''));
    $dateToValue = trim((string)($_POST['DATE_TO'] ?? ''));
    $selectedDepartmentId = (int)($_POST['UF_DEPARTMENT'] ?? 0);

    if (!check_bitrix_sessid()) {
        $errors[] = 'Сессия формы истекла. Обновите страницу и повторите отправку.';
    }

    if ($fioValue === '') {
        $errors[] = 'Укажите ФИО.';
    }

    if (!check_email($emailValue)) {
        $errors[] = 'Укажите корректный email.';
    }

    $isValidDate = static function (string $date): bool {
        $dateObject = DateTime::createFromFormat('Y-m-d', $date);

        return $dateObject
                && $dateObject->format('Y-m-d') === $date;
    };

    if (!$isValidDate($dateFromValue)) {
        $errors[] = 'Укажите корректную дату начала.';
    }

    if (!$isValidDate($dateToValue)) {
        $errors[] = 'Укажите корректную дату окончания.';
    }

    if (
            $isValidDate($dateFromValue)
            && $isValidDate($dateToValue)
            && $dateFromValue > $dateToValue
    ) {
        $errors[] = 'Дата окончания не может быть раньше даты начала.';
    }

    if (!$selectedDepartmentId || !isset($sectionsById[$selectedDepartmentId])) {
        $errors[] = 'Выберите департамент.';
    }

    if (!$errors) {
        /*
         * Здесь можно сохранить данные в инфоблок,
         * HL-блок или собственную таблицу.

         Например:

         $element = new CIBlockElement();

         $element->Add([
             'IBLOCK_ID' => 123,
             'NAME' => $fioValue . ' — ' . $dateFromValue,
             'PROPERTY_VALUES' => [
                 'FIO' => $fioValue,
                 'EMAIL' => $emailValue,
                 'DATE_FROM' => $dateFromValue,
                 'DATE_TO' => $dateToValue,
                 'DEPARTMENT' => $selectedDepartmentId,
             ],
         ]);
        */

        $successMessage = 'Форма успешно заполнена.';
    }
}

$selectedDepartment = $sectionsById[$selectedDepartmentId] ?? null;
$selectedDepartmentName = $selectedDepartment['NAME'] ?? '';
$selectedManagerName = $selectedDepartment['MANAGER_NAME'] ?? '';

/**
 * Рекурсивный вывод дерева разделов
 */
$renderTree = function (array $nodes) use (&$renderTree, $e, $selectedDepartmentId): void {
    foreach ($nodes as $node) {
        $hasChildren = !empty($node['CHILDREN']);
        $isSelected = (int)$node['ID'] === (int)$selectedDepartmentId;

        echo '<li class="department-tree-item">';

        echo '<div class="department-tree-row">';

        if ($hasChildren) {
            echo '<button
                    type="button"
                    class="tree-toggle"
                    aria-label="Развернуть подразделы"
                >−</button>';
        } else {
            echo '<span class="tree-toggle-placeholder"></span>';
        }

        echo '<button
                type="button"
                class="tree-node ' . ($isSelected ? 'is-selected' : '') . '"
                data-id="' . (int)$node['ID'] . '"
                data-name="' . $e($node['NAME']) . '"
                data-manager="' . $e($node['MANAGER_NAME']) . '"
            >
                <span class="department-icon">▰</span>
                <span>' . $e($node['NAME']) . '</span>
            </button>';

        echo '</div>';

        if ($hasChildren) {
            echo '<ul class="department-tree-list">';
            $renderTree($node['CHILDREN']);
            echo '</ul>';
        }

        echo '</li>';
    }
};
?>

<link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
>

<link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
>

<style>
    body {
        background: #f4f7fb;
    }

    .form-page {
        max-width: 940px;
        margin: 0 auto;
    }

    .form-card {
        border: 0;
        border-radius: 24px;
        box-shadow: 0 15px 45px rgba(31, 56, 88, 0.12);
        overflow: visible;
    }

    .form-card-header {
        padding: 32px 36px;
        color: #fff;
        background: linear-gradient(135deg, #1b74e4, #6845d8);
        border-radius: 24px 24px 0 0;
    }

    .form-card-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px;
    }

    .form-card-header p {
        margin: 0;
        opacity: .85;
    }

    .form-card-body {
        padding: 36px;
        background: #fff;
        border-radius: 0 0 24px 24px;
    }

    .form-label {
        font-weight: 600;
        color: #25324b;
    }

    .form-control,
    .department-picker-button {
        min-height: 50px;
        border-radius: 12px;
        border: 1px solid #dce3ef;
        padding: 12px 15px;
        transition: .2s ease;
    }

    .form-control:focus,
    .department-picker-button:focus {
        border-color: #6b5ce7;
        box-shadow: 0 0 0 4px rgba(107, 92, 231, .12);
    }

    .department-picker {
        position: relative;
    }

    .department-picker-button {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        color: #25324b;
        text-align: left;
    }

    .department-picker-button::after {
        content: "⌄";
        font-size: 20px;
        color: #6b7280;
    }

    .department-menu {
        position: absolute;
        z-index: 20;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        max-height: 360px;
        overflow-y: auto;
        padding: 12px;
        background: #fff;
        border: 1px solid #e3e8f1;
        border-radius: 16px;
        box-shadow: 0 16px 38px rgba(27, 47, 78, .18);
    }

    .department-tree-list {
        margin: 4px 0 0 20px;
        padding: 0;
        list-style: none;
    }

    .department-tree-item {
        list-style: none;
    }

    .department-tree-row {
        display: flex;
        align-items: center;
        gap: 4px;
        min-height: 38px;
    }

    .tree-toggle,
    .tree-toggle-placeholder {
        width: 26px;
        height: 26px;
        flex: 0 0 26px;
    }

    .tree-toggle {
        border: 0;
        border-radius: 7px;
        background: #f0f3f9;
        color: #65738b;
        line-height: 1;
        cursor: pointer;
    }

    .tree-toggle:hover {
        background: #e5eafa;
    }

    .tree-node {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 0;
        border-radius: 9px;
        background: transparent;
        padding: 7px 10px;
        color: #344054;
        text-align: left;
        cursor: pointer;
    }

    .tree-node:hover,
    .tree-node.is-selected {
        background: #eef2ff;
        color: #4c3ec7;
    }

    .tree-node.is-selected {
        font-weight: 600;
    }

    .department-icon {
        color: #6958d8;
        font-size: 13px;
    }

    .readonly-control {
        background-color: #f8faff;
    }

    .submit-button {
        min-height: 52px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #1b74e4, #6845d8);
        font-weight: 600;
        transition: .2s ease;
    }

    .submit-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(91, 76, 211, .25);
    }

    @media (max-width: 575px) {
        .form-card-header,
        .form-card-body {
            padding: 24px 18px;
        }

        .form-card-header h1 {
            font-size: 23px;
        }
    }
</style>

<div class="container py-5">
    <div class="form-page">
        <div class="card form-card">
            <div class="form-card-header">
                <h1>Заявка сотрудника</h1>
                <p>Заполните данные и выберите необходимый департамент</p>
            </div>

            <div class="form-card-body">
                <?php if ($errors): ?>
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= $e($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($successMessage): ?>
                    <div class="alert alert-success rounded-3">
                        <?= $e($successMessage) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="" id="employeeRequestForm">
                    <?= bitrix_sessid_post() ?>

                    <div id="ajaxMessage" class="d-none" role="alert"></div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="fio" class="form-label">
                                ФИО <span class="text-danger">*</span>
                            </label>

                            <input
                                    type="text"
                                    class="form-control"
                                    id="fio"
                                    name="FIO"
                                    value="<?= $e($fioValue) ?>"
                                    placeholder="Введите ФИО"
                                    required
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>

                            <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="EMAIL"
                                    value="<?= $e($emailValue) ?>"
                                    placeholder="name@example.com"
                                    required
                            >
                        </div>

                        <div class="col-12">
                            <label for="dateRange" class="form-label">
                                Период <span class="text-danger">*</span>
                            </label>

                            <input
                                    type="text"
                                    class="form-control"
                                    id="dateRange"
                                    placeholder="Выберите диапазон дат"
                                    autocomplete="off"
                                    readonly
                                    required
                            >

                            <input
                                    type="hidden"
                                    id="dateFrom"
                                    name="DATE_FROM"
                                    value="<?= $e($dateFromValue) ?>"
                            >

                            <input
                                    type="hidden"
                                    id="dateTo"
                                    name="DATE_TO"
                                    value="<?= $e($dateToValue) ?>"
                            >

                            <div class="form-text">
                                Даты отображаются в русском формате, например: 15.01.2026 — 31.01.2026
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                Департамент <span class="text-danger">*</span>
                            </label>

                            <div class="department-picker" id="departmentPicker">
                                <button
                                        type="button"
                                        class="department-picker-button"
                                        id="departmentPickerButton"
                                >
                                    <span id="departmentPickerText">
                                        <?= $e($selectedDepartmentName ?: 'Выберите департамент') ?>
                                    </span>
                                </button>

                                <div class="department-menu d-none" id="departmentMenu">
                                    <ul class="department-tree-list m-0">
                                        <?php $renderTree($tree); ?>
                                    </ul>
                                </div>
                            </div>

                            <input
                                    type="hidden"
                                    id="departmentId"
                                    name="UF_DEPARTMENT"
                                    value="<?= (int)$selectedDepartmentId ?>"
                            >

                            <div class="form-text">
                                По умолчанию выбран департамент из поля UF_DEPARTMENT текущего пользователя.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="departmentName" class="form-label">
                                Название департамента
                            </label>

                            <input
                                    type="text"
                                    class="form-control readonly-control"
                                    id="departmentName"
                                    name="DEPARTMENT_NAME"
                                    value="<?= $e($selectedDepartmentName) ?>"
                                    readonly
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="departmentHead" class="form-label">
                                Руководитель департамента
                            </label>

                            <input
                                    type="text"
                                    class="form-control readonly-control"
                                    id="departmentHead"
                                    name="DEPARTMENT_HEAD"
                                    value="<?= $e($selectedManagerName) ?>"
                                    placeholder="Руководитель не указан"
                                    readonly
                            >
                        </div>

                        <div class="col-12 pt-2">
                            <button
                                    type="submit"
                                    id="submitButton"
                                    class="btn btn-primary submit-button w-100"
                            >
                                Отправить форму
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateRange = document.getElementById('dateRange');
        const dateFrom = document.getElementById('dateFrom');
        const dateTo = document.getElementById('dateTo');

        const initialDateFrom = <?= CUtil::PhpToJSObject($dateFromValue) ?>;
        const initialDateTo = <?= CUtil::PhpToJSObject($dateToValue) ?>;

        function formatIsoDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return year + '-' + month + '-' + day;
        }

        const datePicker = flatpickr(dateRange, {
            mode: 'range',
            locale: flatpickr.l10ns.ru,
            dateFormat: 'd.m.Y',
            allowInput: false,
            onChange: function (selectedDates) {
                if (selectedDates.length === 2) {
                    dateFrom.value = formatIsoDate(selectedDates[0]);
                    dateTo.value = formatIsoDate(selectedDates[1]);
                } else {
                    dateFrom.value = '';
                    dateTo.value = '';
                }
            }
        });

        if (initialDateFrom && initialDateTo) {
            datePicker.setDate([
                initialDateFrom,
                initialDateTo
            ], true, 'Y-m-d');
        }

        const picker = document.getElementById('departmentPicker');
        const pickerButton = document.getElementById('departmentPickerButton');
        const pickerText = document.getElementById('departmentPickerText');
        const menu = document.getElementById('departmentMenu');

        const departmentId = document.getElementById('departmentId');
        const departmentName = document.getElementById('departmentName');
        const departmentHead = document.getElementById('departmentHead');

        pickerButton.addEventListener('click', function () {
            menu.classList.toggle('d-none');
        });

        menu.addEventListener('click', function (event) {
            const toggle = event.target.closest('.tree-toggle');

            if (toggle) {
                const parentLi = toggle.closest('.department-tree-item');
                const childList = Array.from(parentLi.children)
                    .find(function (element) {
                        return element.tagName.toLowerCase() === 'ul';
                    });

                if (childList) {
                    childList.classList.toggle('d-none');
                    toggle.textContent = childList.classList.contains('d-none')
                        ? '+'
                        : '−';
                }

                return;
            }

            const node = event.target.closest('.tree-node');

            if (!node) {
                return;
            }

            document.querySelectorAll('.tree-node.is-selected')
                .forEach(function (item) {
                    item.classList.remove('is-selected');
                });

            node.classList.add('is-selected');

            const id = node.dataset.id || '';
            const name = node.dataset.name || '';
            const manager = node.dataset.manager || '';

            departmentId.value = id;
            pickerText.textContent = name;
            departmentName.value = name;
            departmentHead.value = manager;

            menu.classList.add('d-none');
        });

        document.addEventListener('click', function (event) {
            if (!picker.contains(event.target)) {
                menu.classList.add('d-none');
            }
        });
        const form = document.getElementById('employeeRequestForm');
        const ajaxMessage = document.getElementById('ajaxMessage');
        const submitButton = document.getElementById('submitButton');

        function showAjaxMessage(type, message) {
            ajaxMessage.className = 'alert alert-' + type + ' rounded-3';
            ajaxMessage.textContent = message;
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = 'Отправка...';

            const formData = new FormData(form);

            try {
                const response = await fetch('/local/ajax/otpuskForm.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    showAjaxMessage(
                        'danger',
                        result.message || 'Не удалось отправить форму.'
                    );

                    return;
                }

                showAjaxMessage(
                    'success',
                    result.message || 'Форма успешно отправлена.'
                );

                // При необходимости можно очистить форму:
                // form.reset();
                // dateFrom.value = '';
                // dateTo.value = '';
                // departmentId.value = '';
            } catch (error) {
                showAjaxMessage(
                    'danger',
                    'Ошибка соединения с сервером. Попробуйте еще раз.'
                );
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Отправить форму';
            }
        });
    });
</script>

<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>
