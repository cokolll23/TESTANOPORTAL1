<?php

declare(strict_types=1);

define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', false);

require $_SERVER['DOCUMENT_ROOT']
    . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Main\Web\Json;

global $USER;

header('Content-Type: application/json; charset=UTF-8');

/**
 * Возвращает JSON и завершает выполнение.
 */
function sendJson(array $data): void
{
    echo Json::encode($data);
    die();
}
function getFio($userId)
{
    $result = CUser::GetByID($userId);
    if ($user = $result->Fetch()) {
        $fio = $user['LAST_NAME'] .' '. $user['NAME'];
    } else {
        $fio = 'Пользователь не найден.';
    }
    return $fio;
};

/**
 * Проверка права на создание задач для конкретной идеи.
 *
 * В данном примере это может сделать:
 * — администратор;
 * — автор поста.
 *
 * Эту функцию желательно заменить проверкой вашей роли
 * "модератор идей", отдела, рабочей группы и т. п.
 */
function canCreateIdeaTasks(array $post, int $currentUserId): bool
{
    global $USER;

    if ($USER->IsAdmin()) {
        return true;
    }

    return (int)$post['AUTHOR_ID'] === $currentUserId;
}

if (!$USER->IsAuthorized()) {
    sendJson([
        'success' => false,
        'message' => 'Необходимо авторизоваться.',
    ]);
}

$request = Application::getInstance()
    ->getContext()
    ->getRequest();

if (!$request->isPost()) {
    sendJson([
        'success' => false,
        'message' => 'Допустим только POST-запрос.',
    ]);
}

if (!check_bitrix_sessid()) {
    sendJson([
        'success' => false,
        'message' => 'Сессия истекла. Обновите страницу.',
    ]);
}

if (
    !Loader::includeModule('blog')
    || !Loader::includeModule('tasks')
) {
    sendJson([
        'success' => false,
        'message' => 'Не подключены необходимые модули.',
    ]);
}

$currentUserId = (int)$USER->GetID();
$postId = (int)$request->getPost('postId');
$moderatorIds = $request->getPost('moderatorIds');
$author = [$request->getPost('author')];

if ($postId <= 0) {
    sendJson([
        'success' => false,
        'message' => 'Не передан ID поста.',
    ]);
}

if (!is_array($moderatorIds)) {
    $moderatorIds = [];
}

$moderatorIds = array_map('intval', $moderatorIds);
$moderatorIds = array_filter(
    $moderatorIds,
    static fn(int $userId): bool => $userId > 0
);
$moderatorIds = array_values(array_unique($moderatorIds));

if (empty($moderatorIds)) {
    sendJson([
        'success' => false,
        'message' => 'Не выбраны модераторы.',
    ]);
}

/*
 * Ограничение защищает обработчик от отправки чрезмерного
 * количества пользователей вручную.
 */
if (count($moderatorIds) > 50) {
    sendJson([
        'success' => false,
        'message' => 'За один запрос можно выбрать не более 50 пользователей.',
    ]);
}

$post = CBlogPost::GetByID($postId);

if (!$post) {
    sendJson([
        'success' => false,
        'message' => 'Пост не найден.',
    ]);
}

if (!canCreateIdeaTasks($post, $currentUserId)) {
    sendJson([
        'success' => false,
        'message' => 'Недостаточно прав для создания задач.',
    ]);
}

/*
 * Берём только существующих активных пользователей.
 * Нельзя доверять ID, полученным из JavaScript.
 */
$validUserIds = [];

$userResult = UserTable::getList([
    'select' => ['ID'],
    'filter' => [
        '@ID' => $moderatorIds,
        '=ACTIVE' => 'Y',
    ],
]);

while ($user = $userResult->fetch()) {
    $validUserIds[] = (int)$user['ID'];
}

$validUserIds = array_values(array_unique($validUserIds));

if (empty($validUserIds)) {
    sendJson([
        'success' => false,
        'message' => 'Выбранные пользователи не найдены или неактивны.',
    ]);
}

$postTitle = trim((string)$post['TITLE']);

if ($postTitle === '') {
    $postTitle = 'Без названия';
}

/*
 * Ограничиваем итоговую длину названия задачи.
 */
$postTitle = mb_substr($postTitle, 0, 180);

$taskTitle = sprintf(
    'Модерация идеи #%d: %s',
    $postId,
    $postTitle
);

$taskDescription = sprintf(
    "Необходимо проверить идею [B]#%d[/B].\n\nНазвание: %s",
    $postId,
    $postTitle
);

$createdTasks = [];
$errors = [];


/*
 * Создаётся отдельная задача для каждого пользователя.
 */
foreach ($validUserIds as $i => $responsibleId) {
    try {
        $task = new CTasks();

        $taskId = $task->Add([
            'TITLE' => $taskTitle,
            'DESCRIPTION' => $taskDescription,
            'DESCRIPTION_IN_BBCODE' => 'Y',

            'CREATED_BY' => $currentUserId,
            'RESPONSIBLE_ID' => $responsibleId,
            'ACCOMPLICES' => $author,// массив идентификаторов соисполнителей;

            'PRIORITY' => 1,
            'SITE_ID' => SITE_ID,
        ]);

        if ($taskId) {
            $createdTasks[(string)$responsibleId]['taskId'] = (int)$taskId;
            $createdTasks[(string)$responsibleId]['userFio'] = getFio((int)$taskId);
        } else {
            $errors[] = [
                'userId' => $responsibleId,
                'message' => $task->LAST_ERROR
                    ?: 'Не удалось создать задачу.',
            ];
        }
    } catch (Throwable $exception) {
        /*
         * Полный текст исключения лучше записывать в журнал,
         * а не возвращать пользователю.
         */
        AddMessage2Log(
            sprintf(
                'Ошибка создания задачи для идеи #%d, пользователь #%d: %s',
                $postId,
                $responsibleId,
                $exception->getMessage()
            ),
            'idea_moderation'
        );

        $errors[] = [
            'userId' => $responsibleId,
            'message' => 'Внутренняя ошибка создания задачи.',
        ];
    }
}

if (empty($createdTasks)) {
    sendJson([
        'success' => false,
        'message' => 'Не удалось создать ни одной задачи.',
        'createdCount' => 0,
        'failedCount' => count($errors),
        'errors' => $errors,
    ]);
}

sendJson([
    'success' => true,
    'postId' => $postId,
    'createdCount' => count($createdTasks),
    'failedCount' => count($errors),
    'taskIds' => $createdTasks,
    'errors' => $errors,
]);
