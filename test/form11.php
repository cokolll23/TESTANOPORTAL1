<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

use Bitrix\Main\Loader;

global $USER, $APPLICATION;

$APPLICATION->SetTitle('Подача заявки на отпуск');

if (!$USER->IsAuthorized()) {
    ShowError('Для подачи заявки необходимо авторизоваться.');
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
    return;
}

if (!Loader::includeModule('iblock')) {
    ShowError('Не удалось подключить модуль инфоблоков.');
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
    return;
}

$currentUserId = (int)$USER->GetID();
$currentUser = CUser::GetByID($currentUserId)->Fetch();

$currentUserName = trim(
        ($currentUser['LAST_NAME'] ?? '') . ' ' .
        ($currentUser['NAME'] ?? '') . ' ' .
        ($currentUser['SECOND_NAME'] ?? '')
);
if ($currentUserName === '') {
    $currentUserName = $currentUser['LOGIN'] ?? '';
}
$currentUserEmail = (string)($currentUser['EMAIL'] ?? '');

// Пользовательские департаменты
$userDepartmentIds = $currentUser['UF_DEPARTMENT'] ?? [];
if (!is_array($userDepartmentIds)) {
    $userDepartmentIds = [$userDepartmentIds];
}
$userDepartmentIds = array_values(array_filter(array_map('intval', $userDepartmentIds)));
$defaultDepartmentId = (int)($userDepartmentIds[0] ?? 0);

// Получаем структуру инфоблока departments
$depIblock = CIBlock::GetList([], ['=CODE' => 'departments', 'ACTIVE' => 'Y'])->Fetch();
$depIblockId = (int)($depIblock['ID'] ?? 0);

$sections = [];
$headIds = [];

if ($depIblockId > 0) {
    $secRes = CIBlockSection::GetList(
            ['LEFT_MARGIN' => 'ASC'],
            ['IBLOCK_ID' => $depIblockId, 'ACTIVE' => 'Y'],
            false,
            ['ID', 'NAME', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID', 'UF_HEAD']
    );

    while ($sec = $secRes->Fetch()) {
        $secId = (int)$sec['ID'];
        $uHead = $sec['UF_HEAD'] ?? 0;
        if (is_array($uHead)) {
            $uHead = reset($uHead);
        }
        $uHead = (int)$uHead;

        $sections[$secId] = [
                'ID' => $secId,
                'NAME' => (string)$sec['NAME'],
                'DEPTH_LEVEL' => (int)$sec['DEPTH_LEVEL'],
                'HEAD_ID' => $uHead,
                'HEAD_NAME' => '',
        ];

        if ($uHead > 0) {
            $headIds[$uHead] = $uHead;
        }
    }
}

// Загружаем имена руководителей
if (!empty($headIds)) {
    $headUsersRes = CUser::GetList('ID', 'ASC', ['ID' => implode('|', $headIds)]);
    $headsMap = [];
    while ($hu = $headUsersRes->Fetch()) {
        $hFio = trim(($hu['LAST_NAME'] ?? '') . ' ' . ($hu['NAME'] ?? '') . ' ' . ($hu['SECOND_NAME'] ?? ''));
        $headsMap[(int)$hu['ID']] = $hFio ?: ($hu['LOGIN'] ?? ('User #' . $hu['ID']));
    }
    foreach ($sections as &$sec) {
        if ($sec['HEAD_ID'] > 0 && isset($headsMap[$sec['HEAD_ID']])) {
            $sec['HEAD_NAME'] = $headsMap[$sec['HEAD_ID']];
        }
    }
    unset($sec);
}

// Формируем JSON-карту для скрипта
$jsDepartments = [];
foreach ($sections as $id => $sec) {
    $jsDepartments[$id] = [
            'name'     => $sec['NAME'],
            'headId'   => $sec['HEAD_ID'] > 0 ? $sec['HEAD_ID'] : '',
            'headName' => $sec['HEAD_NAME'] ?: ($sec['HEAD_ID'] > 0 ? ('ID ' . $sec['HEAD_ID']) : 'Не назначен'),
    ];
}
?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .otpusk-card {
            max-width: 860px;
            margin: 30px auto;
            border: none;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            background: #ffffff;
        }
        .otpusk-card-header {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: #ffffff;
            padding: 28px 36px;
        }
        .otpusk-card-body {
            padding: 36px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 11px 16px;
            border: 1px solid #cbd5e1;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .bg-readonly {
            background-color: #f8fafc !important;
            color: #475569;
        }
        .btn-submit {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border: none;
            border-radius: 10px;
            padding: 13px 28px;
            font-weight: 600;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-submit:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(99, 102, 241, 0.35);
        }
    </style>

    <div class="container py-4">
        <div class="card otpusk-card">
            <div class="otpusk-card-header">
                <h3 class="mb-1 fw-bold">Заявка на отпуск</h3>
                <p class="mb-0 text-white-50">Заполните период и проверьте департамент для согласования</p>
            </div>

            <div class="otpusk-card-body">
                <div id="formAlert" class="alert d-none" role="alert"></div>

                <form id="otpuskForm" novalidate>
                    <?= bitrix_sessid_post() ?>

                    <div class="row g-3">
                        <!-- Текущий пользователь -->
                        <div class="col-md-9">
                            <label class="form-label fw-semibold">ФИО сотрудника</label>
                            <input type="text" class="form-control bg-readonly" value="<?= htmlspecialcharsbx($currentUserName) ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">ID сотрудника</label>
                            <input type="text" class="form-control bg-readonly" value="<?= $currentUserId ?>" readonly>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control bg-readonly" value="<?= htmlspecialcharsbx($currentUserEmail) ?>" readonly>
                        </div>

                        <!-- Выбор дат -->
                        <div class="col-12">
                            <label for="dateRangeInput" class="form-label fw-semibold">
                                Период отпуска <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="dateRangeInput" class="form-control" placeholder="Выберите диапазон дат" autocomplete="off" required>
                            <input type="hidden" name="DATE_FROM" id="dateFrom">
                            <input type="hidden" name="DATE_TO" id="dateTo">
                            <div class="form-text">Выберите первый и последний день отпуска в календаре.</div>
                        </div>

                        <!-- Древовидный выбор департамента -->
                        <div class="col-12">
                            <label for="departmentSelect" class="form-label fw-semibold">
                                Департамент <span class="text-danger">*</span>
                            </label>
                            <select name="DEPARTMENT" id="departmentSelect" class="form-select" required>
                                <option value="">-- Выберите подразделение --</option>
                                <?php foreach ($sections as $sec): ?>
                                    <?php
                                    $indent = str_repeat('— ', max(0, $sec['DEPTH_LEVEL'] - 1));
                                    $isSelected = ($sec['ID'] === $defaultDepartmentId);
                                    ?>
                                    <option value="<?= $sec['ID'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                                        <?= htmlspecialcharsbx($indent . $sec['NAME']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Автоматические поля выбранного департамента -->
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Руководитель департамента</label>
                            <input type="text" id="departmentHeadName" class="form-control bg-readonly" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">ID руководителя</label>
                            <input type="text" id="departmentHeadId" class="form-control bg-readonly" readonly>
                        </div>

                        <div class="col-12 pt-3">
                            <button type="submit" id="submitBtn" class="btn btn-primary btn-submit w-100">
                                <span id="submitSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                                Отправить заявку
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const departmentsMap = <?= json_encode($jsDepartments, JSON_UNESCAPED_UNICODE) ?>;

            const form = document.getElementById('otpuskForm');
            const formAlert = document.getElementById('formAlert');
            const submitBtn = document.getElementById('submitBtn');
            const submitSpinner = document.getElementById('submitSpinner');

            const departmentSelect = document.getElementById('departmentSelect');
            const headNameInput = document.getElementById('departmentHeadName');
            const headIdInput = document.getElementById('departmentHeadId');

            const dateRangeInput = document.getElementById('dateRangeInput');
            const dateFromInput = document.getElementById('dateFrom');
            const dateToInput = document.getElementById('dateTo');

            // Функция обновления отображения руководителя при смене подразделения
            function syncDepartmentHead() {
                const selectedId = departmentSelect.value;
                const dep = departmentsMap[selectedId];

                if (dep) {
                    headNameInput.value = dep.headName || 'Не назначен';
                    headIdInput.value = dep.headId || '—';
                } else {
                    headNameInput.value = '';
                    headIdInput.value = '';
                }
            }

            departmentSelect.addEventListener('change', syncDepartmentHead);
            syncDepartmentHead(); // Первичная инициализация

            // Инициализация календаря Flatpickr с русской локализацией
            flatpickr(dateRangeInput, {
                mode: 'range',
                locale: flatpickr.l10ns.ru,
                dateFormat: 'd.m.Y',
                allowInput: false,
                onChange: function (selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        dateFromInput.value = instance.formatDate(selectedDates[0], 'Y-m-d');
                        dateToInput.value = instance.formatDate(selectedDates[1], 'Y-m-d');
                    } else if (selectedDates.length === 1) {
                        dateFromInput.value = instance.formatDate(selectedDates[0], 'Y-m-d');
                        dateToInput.value = '';
                    } else {
                        dateFromInput.value = '';
                        dateToInput.value = '';
                    }
                }
            });

            function showAlert(text, type) {
                formAlert.className = 'alert alert-' + type;
                formAlert.textContent = text;
                formAlert.classList.remove('d-none');
                formAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            // Отправка формы через Fetch API
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                formAlert.classList.add('d-none');

                if (!dateFromInput.value || !dateToInput.value) {
                    showAlert('Пожалуйста, выберите диапазон дат (начало и конец).', 'danger');
                    return;
                }

                if (!departmentSelect.value) {
                    showAlert('Пожалуйста, выберите департамент.', 'danger');
                    return;
                }

                const formData = new FormData(form);

                console.log(formData);
                submitBtn.disabled = true;
                submitSpinner.classList.remove('d-none');

                try {
                    const response = await fetch('/local/ajax/otpusks/otpuskForm11.php', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        showAlert(result.message || 'Заявка успешно отправлена!', 'success');
                        form.reset();
                        syncDepartmentHead();
                    } else {
                        showAlert(result.message || 'Произошла ошибка при сохранении.', 'danger');
                    }
                } catch (err) {
                    showAlert('Сетевая ошибка или некорректный ответ сервера.', 'danger');
                    console.error('Fetch error:', err);
                } finally {
                    submitBtn.disabled = false;
                    submitSpinner.classList.add('d-none');
                }
            });
        });
    </script>

<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>