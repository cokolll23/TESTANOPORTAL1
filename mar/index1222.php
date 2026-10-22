<?php require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php'); ?>
<style>
    .selected-employees__row {
        align-items: flex-start;
    }

    .selected-employees__info {
        flex: 1;
    }

    .selected-employees__id {
        margin-top: 3px;
        font-size: 12px;
        color: #888;
    }

    .selected-employees__comment {
        margin-top: 12px;
    }

    .selected-employees__comment-label {
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        font-weight: 600;
        color: #333;
    }

    .selected-employees__textarea {
        display: block;
        box-sizing: border-box;
        width: 100%;
        min-height: 80px;
        padding: 8px 10px;
        border: 1px solid #c9cfd6;
        border-radius: 4px;
        font: inherit;
        resize: vertical;
    }

    .selected-employees__textarea:focus {
        border-color: #2fc6f6;
        outline: 2px solid rgba(47, 198, 246, 0.2);
    }

    .selected-employees {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 12px;
    }

    .selected-employees__row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 10px 12px;
        border: 1px solid #dfe3e6;
        border-radius: 6px;
        background: #fff;
    }

    .selected-employees__info {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .selected-employees__name {
        font-weight: 600;
        color: #333;
    }

    .selected-employees__department {
        margin-top: 3px;
        font-size: 13px;
        color: #777;
    }

    .selected-employees__remove {
        flex-shrink: 0;
        padding: 5px 8px;
        border: 0;
        border-radius: 4px;
        background: transparent;
        color: #c0392b;
        cursor: pointer;
    }

    .selected-employees__remove:hover {
        background: #fff0ef;
    }

    .selected-employees__remove:focus-visible {
        outline: 2px solid #c0392b;
        outline-offset: 2px;
    }

    .selected-employees__empty {
        font-size: 13px;
        color: #888;
    }
</style>
<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\UI\Extension;
use Bitrix\Iblock\IblockTable;

global $USER;

if ($USER->IsAuthorized()) {
    $userId = $USER->GetID();

    // Получаем пользователя
    $rsUser = CUser::GetByID($userId);
    $arUser = $rsUser->Fetch();

    // UF_DEPARTMENT — массив ID подразделений

    $arDepartments = $arUser['UF_DEPARTMENT'];

    $blGenDir = $arDepartments == 1 ? 1 : 0;

    if (!empty($arDepartments) && is_array($arDepartments)) {
        foreach ($arDepartments as $departmentId) {
            // ID подразделения
            $departmentId = (int)$departmentId;

            // Получаем название подразделения
            $departmentName = '';

            // В Битрикс24 оргструктура обычно хранится в инфоблоке.
            // Если у вас известен ID инфоблока подразделений, укажите его:
            $iblockId = 3; // замените на реальный ID инфоблока подразделений


            Loader::includeModule('iblock');

            $iblockId = IblockTable::getList([
                    'filter' => ['=CODE' => 'departments'],
                    'select' => ['ID']
            ])->fetch()['ID'] ?? null;


            if (\Bitrix\Main\Loader::includeModule('iblock')) {
                $rsSection = \CIBlockSection::GetList(
                        [],
                        ['ID' => $departmentId, 'IBLOCK_ID' => $iblockId],
                        false,
                        ['ID', 'NAME']
                );

                if ($arSection = $rsSection->Fetch()) {
                    echo $departmentName = $arSection['NAME'];
                    $departmentID = $arSection['ID'];
                }
            }

        }
    }
}
if (!$USER->IsAuthorized()) {
    return;
}

if (
        !Loader::includeModule('intranet')
        || !Loader::includeModule('iblock')
) {
    throw new RuntimeException('Необходимы модули intranet и iblock');
}

Extension::load('ui.entity-selector');

//$userId = (int)$USER->GetID();

$arUser = CUser::GetByID($userId)->Fetch();
if (!$arUser) {
    return;
}
$arDepartments = (array)($arUser['UF_DEPARTMENT'] ?? []);

$arDepartments = array_filter(array_map('intval', $arDepartments));
if (!$arDepartments) {
    echo 'Пользователь не привязан к подразделениям';
    return;
}

$iblockId = (int)(IblockTable::getList([
        'filter' => ['=CODE' => 'departments'],
        'select' => ['ID'],
])->fetch()['ID'] ?? 0);

if (!$iblockId) {
    echo 'Инфоблок подразделений не найден';
    return;
}

function getSectionChain(int $sectionId, int $iblockId): array
{
    $res = CIBlockSection::GetNavChain($iblockId, $sectionId, ['ID', 'NAME', 'DEPTH_LEVEL']);
    $chain = [];
    while ($row = $res->Fetch()) {
        $chain[] = [
                'ID' => (int)$row['ID'],
                'NAME' => (string)$row['NAME'],
                'DEPTH_LEVEL' => (int)$row['DEPTH_LEVEL'],
        ];
    }
    return $chain;
}

function findInChain(array $chain, array $keywords): ?array
{
    foreach ($chain as $node) {
        foreach ($keywords as $kw) {
            if (mb_stripos($node['NAME'], $kw) !== false) {
                return $node;
            }
        }
    }
    return null;
}

/**
 * Получить ID всех вложенных разделов (на всех уровнях) для указанного раздела
 * @return int[]
 */
function getNestedSectionIds(int $rootSectionId, int $iblockId): array
{
    $ids = [];

    $rs = CIBlockSection::GetList(
            ['LEFT_MARGIN' => 'ASC'],
            [
                    'IBLOCK_ID' => $iblockId,
                    'SECTION_ID' => $rootSectionId,
                    'ACTIVE' => 'Y',
                    'GLOBAL_ACTIVE' => 'Y',
            ],
            false,
            ['ID']
    );
    while ($row = $rs->Fetch()) {
        $ids[] = (int)$row['ID'];
    }

    // Если структура может быть многоуровневой и GET_LIST с SECTION_ID
    // возвращает только прямых потомков — обойдём рекурсивно.
    // Проверим "глубину" через LEFT_MARGIN / RIGHT_MARGIN, чтобы взять всех.
    $rootRes = CIBlockSection::GetList(
            [],
            ['IBLOCK_ID' => $iblockId, 'ID' => $rootSectionId],
            false,
            ['ID', 'LEFT_MARGIN', 'RIGHT_MARGIN']
    )->Fetch();

    if ($rootRes && $rootRes['LEFT_MARGIN'] !== null && $rootRes['RIGHT_MARGIN'] !== null) {
        $ids = [];
        $rs = CIBlockSection::GetList(
                ['LEFT_MARGIN' => 'ASC'],
                [
                        'IBLOCK_ID' => $iblockId,
                        '>LEFT_MARGIN' => (int)$rootRes['LEFT_MARGIN'],
                        '<RIGHT_MARGIN' => (int)$rootRes['RIGHT_MARGIN'],
                        'ACTIVE' => 'Y',
                        'GLOBAL_ACTIVE' => 'Y',
                ],
                false,
                ['ID']
        );
        while ($row = $rs->Fetch()) {
            $ids[] = (int)$row['ID'];
        }
    }

    return $ids;
}

$directorateKeywords = ['Генеральный директор', 'Дирекция','Комитет', 'Советник', 'Заместитель директора', 'Директор дивизиона', 'заместитель генерального директора'];
$managementKeywords = ['Управление', 'Группа'];

$foundDirectorate = null;
$foundManagement = null;
$foundGroup = null;

foreach ($arDepartments as $departmentId) {
    $chain = getSectionChain($departmentId, $iblockId);
    if (!$chain) {
        continue;
    }

    for ($i = count($chain) - 1; $i >= 0; $i--) {
        $node = $chain[$i];
        $name = $node['NAME'];

        if (!$foundGroup && mb_stripos($name, 'Группа') !== false) {
            $foundGroup = $node;
        }
        if (!$foundManagement && findInChain([$node], $managementKeywords)) {
            $foundManagement = $node;
        }
        if (!$foundDirectorate && findInChain([$node], $directorateKeywords)) {
            $foundDirectorate = $node;
        }
    }
}

// Определяем "главный" раздел: Дирекция > Управление > Группа
$rootSection = $foundDirectorate ?: $foundManagement ?: $foundGroup;

if (!$rootSection) {
    echo 'Не удалось определить раздел пользователя';
    return;
}

$rootSectionId = (int)$rootSection['ID'];

// Получаем ID всех вложенных разделов (включая сам корневой)
$nestedIds = getNestedSectionIds($rootSectionId, $iblockId);
$allSectionIds = array_merge([$rootSectionId], $nestedIds);

// Для удобства — уникализируем
$allSectionIds = array_values(array_unique($allSectionIds));

// Подключаем модуль инфоблоков
\Bitrix\Main\Loader::includeModule('iblock');

// 1. Получаем ID инфоблока по его символьному коду 'departments'
$iblockCode = 'departments';
$resIblock = CIBlock::GetList([], ['=CODE' => $iblockCode, 'CHECK_PERMISSIONS' => 'N']);
$arIblock = $resIblock->Fetch();

if (!$arIblock) {
    die("Инфоблок с кодом '{$iblockCode}' не найден.");
}

$iblockId = $arIblock['ID'];

// 2. Получаем все ID разделов этого инфоблока
$allSectionIds1 = [];
$rsSections = CIBlockSection::GetList(
        ['SORT' => 'ASC'],
        ['IBLOCK_ID' => $iblockId],
        false,
        ['ID']
);

while ($arSection = $rsSections->Fetch()) {
    $allSectionIds1[] = (int)$arSection['ID'];
}

// 3. Массив ID, которые нужно исключить (ваш определенный массив)
//$excludeIds = [5, 10, 15]; // Пример: замените на ваши реальные ID

// 4. Вычитаем один массив из другого
// array_diff вернет все значения из $allSectionIds1, которых нет в $excludeIds
$resultIds = array_diff($allSectionIds1, $allSectionIds);
// Вывод результата (для отладки)
// Теперь в $resultIds лежит массив с ID разделов, которые остались после исключения.
if ($arDepartments[0]==1) {
    $allowedDepartmentIds = array_diff($allSectionIds1, [$arDepartments[0]]);
} else {
    $allowedDepartmentIds = $resultIds;
}

// Если нужен именно массив без корневого:
// $nestedOnly = array_values(array_diff($allSectionIds, [$rootSectionId]));



// Подразделения, сотрудников которых разрешено выбирать.
// Задавайте список на сервере, а не берите его без проверки из запроса.


$allowedDepartmentIds = array_values(array_unique(array_filter(
        array_map('intval', $allowedDepartmentIds),
        static function ($id) {
            return $id > 0;
        }
)));

$users = [];
$departmentIds = [];

// При пустом списке подразделений никого не загружаем.
if ($allowedDepartmentIds) {
    $by = 'last_name';
    $order = 'asc';

    $result = CUser::GetList(
            $by,
            $order,
            [
                    'ACTIVE' => 'Y',
                    'UF_DEPARTMENT' => $allowedDepartmentIds,
            ],
            [
                    'FIELDS' => [
                            'ID',
                            'NAME',
                            'LAST_NAME',
                            'SECOND_NAME',
                            'LOGIN',
                    ],
                    'SELECT' => [
                            'UF_DEPARTMENT',
                    ],
            ]
    );

    while ($user = $result->Fetch()) {
        $user['UF_DEPARTMENT'] = array_values(array_filter(
                array_map('intval', (array)$user['UF_DEPARTMENT']),
                static function ($id) {
                    return $id > 0;
                }
        ));

        foreach ($user['UF_DEPARTMENT'] as $departmentId) {
            $departmentIds[$departmentId] = $departmentId;
        }

        $users[(int)$user['ID']] = $user;
    }
}

// Получаем названия подразделений одним запросом.
$departmentNames = [];

$structureIblockId = (int)Option::get(
        'intranet',
        'iblock_structure',
        '0'
);

if ($structureIblockId > 0 && $departmentIds) {
    $result = CIBlockSection::GetList(
            ['NAME' => 'ASC'],
            [
                    'IBLOCK_ID' => $structureIblockId,
                    'ID' => array_values($departmentIds),
            ],
            false,
            ['ID', 'NAME']
    );

    while ($department = $result->Fetch()) {
        $departmentNames[(int)$department['ID']] = $department['NAME'];
    }
}

// Формируем элементы селектора.
$items = [];

foreach ($users as $user) {
    $fullName = trim(implode(' ', array_filter(
            [
                    $user['LAST_NAME'],
                    $user['NAME'],
                    $user['SECOND_NAME'],
            ],
            static function ($value) {
                return trim((string)$value) !== '';
            }
    )));

    if ($fullName === '') {
        $fullName = $user['LOGIN'];
    }

    $names = [];

    foreach ($user['UF_DEPARTMENT'] as $departmentId) {
        if (isset($departmentNames[$departmentId])) {
            $names[] = $departmentNames[$departmentId];
        }
    }

    $items[] = [
            'id' => (int)$user['ID'],

        // Своя сущность, без стандартного провайдера всех пользователей.
            'entityId' => 'department-employee',

            'title' => $fullName,
            'subtitle' => implode(', ', array_unique($names)),

            'tabs' => ['employees'],

            'customData' => [
                    'departmentIds' => $user['UF_DEPARTMENT'],
            ],
    ];
}

$itemsJson = json_encode(
        $items,
        JSON_UNESCAPED_UNICODE
        | JSON_HEX_TAG
        | JSON_HEX_AMP
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
        | JSON_THROW_ON_ERROR
);
?>
<div id="employees-selector"></div>
<div
        id="selected-employees"
        class="selected-employees"
        aria-live="polite"
>
</div>
<script>
    BX.ready(function () {
        const employeeItems = <?= $itemsJson ?>;
        const selectedContainer = document.getElementById('selected-employees');

        // Справочник: ID сотрудника → его данные.
        const employeesById = new Map(
            employeeItems.map(function (item) {
                return [String(item.id), item];
            })
        );

        const selector = new BX.UI.EntitySelector.TagSelector({
            id: 'employees-by-department',
            multiple: false,

            events: {
                onAfterTagAdd: function () {
                    renderSelectedEmployees();
                },

                onAfterTagRemove: function () {
                    renderSelectedEmployees();
                }
            },

            dialogOptions: {
                recentTabOptions: {
                    visible: false
                },

                context: null,

                enableSearch: true,
                dynamicLoad: false,
                dynamicSearch: false,

                entities: [
                    {
                        id: 'department-employee',
                        dynamicLoad: false,
                        dynamicSearch: false
                    }
                ],

                tabs: [
                    {
                        id: 'employees',
                        title: 'Сотрудники'
                    }
                ],

                items: employeeItems
            }
        });

        // ID сотрудника → введённый комментарий.
// Сохраняет текст при перерисовке списка.
        const employeeComments = new Map();

        function renderSelectedEmployees() {
            selectedContainer.textContent = '';

            const tags = selector.getTags();

            if (tags.length === 0) {
                const empty = document.createElement('div');

                empty.className = 'selected-employees__empty';
                empty.textContent = 'Сотрудники не выбраны';

                selectedContainer.appendChild(empty);
                return;
            }

            const fragment = document.createDocumentFragment();

            tags.forEach(function (tag) {
                const userId = String(tag.getId());
                const employee = employeesById.get(userId);

                const name = employee
                    ? employee.title
                    : tag.getTitle();

                const department = employee && employee.subtitle
                    ? employee.subtitle
                    : 'Подразделение не указано';

                // Строка, привязанная к ID сотрудника.
                const row = document.createElement('div');

                row.className = 'selected-employees__row';
                row.id = 'selected-employee-' + userId;
                row.dataset.userId = userId;

                const info = document.createElement('div');
                info.className = 'selected-employees__info';

                const nameElement = document.createElement('div');
                nameElement.className = 'selected-employees__name';
                nameElement.textContent = name;

                const idElement = document.createElement('div');
                idElement.className = 'selected-employees__id';
                idElement.textContent = 'ID пользователя: ' + userId;

                const departmentElement = document.createElement('div');
                departmentElement.className = 'selected-employees__department';
                departmentElement.textContent = department;

                // ID сотрудника для отправки обычной HTML-формы.
                const userIdInput = document.createElement('input');

                userIdInput.type = 'hidden';
                userIdInput.name = 'employees[' + userId + '][id]';
                userIdInput.value = userId;

                // Обязательный комментарий конкретного сотрудника.
                const commentContainer = document.createElement('div');
                commentContainer.className = 'selected-employees__comment';

                const commentLabel = document.createElement('label');

                commentLabel.className = 'selected-employees__comment-label';
                commentLabel.htmlFor = 'employee-comment-' + userId;
                commentLabel.textContent = 'Комментарий *';

                const commentTextarea = document.createElement('textarea');

                commentTextarea.id = 'employee-comment-' + userId;
                commentTextarea.name = 'employees[' + userId + '][comment]';
                commentTextarea.className = 'selected-employees__textarea';
                commentTextarea.rows = 3;
                commentTextarea.required = true;
                commentTextarea.placeholder = 'Введите комментарий';
                commentTextarea.value = employeeComments.get(userId) || '';

                function updateComment() {
                    employeeComments.set(userId, commentTextarea.value);

                    // Не разрешаем комментарий, состоящий только из пробелов.
                    commentTextarea.setCustomValidity(
                        commentTextarea.value.trim() === ''
                            ? 'Заполните комментарий для сотрудника.'
                            : ''
                    );
                }

                commentTextarea.addEventListener('input', updateComment);
                updateComment();

                commentContainer.appendChild(commentLabel);
                commentContainer.appendChild(commentTextarea);

                const removeButton = document.createElement('button');

                removeButton.type = 'button';
                removeButton.className = 'selected-employees__remove';
                removeButton.textContent = 'Удалить';
                removeButton.setAttribute(
                    'aria-label',
                    'Удалить сотрудника: ' + name
                );

                removeButton.addEventListener('click', function () {
                    selector.removeTag({
                        id: tag.getId(),
                        entityId: tag.getEntityId()
                    });
                });

                info.appendChild(nameElement);
                info.appendChild(idElement);
                info.appendChild(departmentElement);
                info.appendChild(userIdInput);
                info.appendChild(commentContainer);

                row.appendChild(info);
                row.appendChild(removeButton);

                fragment.appendChild(row);
            });

            selectedContainer.appendChild(fragment);
        }



        selector.renderTo(
            document.getElementById('employees-selector')
        );

        renderSelectedEmployees();

        // ID выбранных сотрудников для отправки формы.
        window.getSelectedEmployeeIds = function () {
            return selector.getTags().map(function (tag) {
                return Number(tag.getId());
            });
        };


    });
</script>



