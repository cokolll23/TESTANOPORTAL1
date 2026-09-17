<?php

define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', false);

require $_SERVER['DOCUMENT_ROOT']
    . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\UserTable;
use Bitrix\Main\Web\Json;

global $USER;

/**
 * ID группы, участники которой могут создавать задачи модерации.
 * Замените 12 на ID своей группы.
 */
const MODERATION_MANAGER_GROUP_ID = 12;

/**
 * Адрес сайта без завершающего слеша.
 */
const MODERATION_SITE_URL = 'https://test-portal.welcome.moscow';

/**
 * Отправляет JSON и завершает обработчик.
 */
function sendJsonResponse(
    array $response,
    int $httpStatus = 200
): void {
    http_response_code($httpStatus);

    header('Content-Type: application/json; charset=UTF-8');
    header('Cache-Control: no-store, no-cache, must-revalidate');

    echo Json::encode($response);
    die();
}

$request = Context::getCurrent()->getRequest();

if (!$request->isPost()) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Допускаются только POST-запросы.',
    ], 405);
}

if (!check_bitrix_sessid()) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Сессия истекла. Обновите страницу.',
    ], 403);
}

if (!$USER->IsAuthorized()) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Необходима авторизация.',
    ], 401);
}

$currentUserId = (int)$USER->GetID();
$currentUserGroups = array_map(
    'intval',
    CUser::GetUserGroup($currentUserId)
);

$isAllowed = $USER->IsAdmin()
    || in_array(
        MODERATION_MANAGER_GROUP_ID,
        $currentUserGroups,
        true
    );

if (!$isAllowed) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Недостаточно прав для создания задач.',
    ], 403);
}

if (!Loader::includeModule('tasks')) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Модуль задач не установлен.',
    ], 500);
}

$postId = (int)$request->getPost('postId');

if ($postId <= 0) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Не указана публикация.',
    ], 400);
}

try {
    $decodedUserIds = Json::decode(
        (string)$request->getPost('userIds')
    );
} catch (Throwable $exception) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Некорректный список пользователей.',
    ], 400);
}

if (!is_array($decodedUserIds)) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Некорректный список пользователей.',
    ], 400);
}

$userIds = [];

foreach ($decodedUserIds as $userId) {
    $userId = (int)$userId;

    if ($userId > 0) {
        $userIds[$userId] = $userId;
    }
}

$userIds = array_values($userIds);

/**
 * Ограничение защищает обработчик от отправки слишком большого запроса.
 */
if (count($userIds) === 0) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Не выбраны пользователи.',
    ], 400);
}

if (count($userIds) > 100) {
    sendJsonResponse([
        'success' => false,
        'message' => 'За один запрос можно выбрать не более 100 пользователей.',
    ], 400);
}

/*
 * Здесь необходимо получить публикацию из вашей базы и проверить,
 * что текущий пользователь имеет право создавать для неё задачи.
 *
 * Не передавайте заголовок и ссылку публикации из JavaScript:
 * пользователь может изменить данные запроса.
 *
 * Пример для инфоблока:
 *
 * Loader::includeModule('iblock');
 *
 * $post = CIBlockElement::GetList(
 *     [],
 *     [
 *         'ID' => $postId,
 *         'ACTIVE' => 'Y',
 *     ],
 *     false,
 *     false,
 *     ['ID', 'NAME', 'DETAIL_PAGE_URL']
 * )->GetNext();
 *
 * if (!$post) {
 *     sendJsonResponse([
 *         'success' => false,
 *         'message' => 'Публикация не найдена.',
 *     ], 404);
 * }
 */

$postTitle = 'Публикация №' . $postId;

/**
 * Замените маршрут на реальный адрес страницы публикации.
 */
$postPath = '/services/idea/' . $postId . '/';
$postUrl = MODERATION_SITE_URL . $postPath;

$users = [];

$userResult = UserTable::getList([
    'select' => [
        'ID',
        'NAME',
        'LAST_NAME',
        'SECOND_NAME',
        'EMAIL',
    ],
    'filter' => [
        '=ID' => $userIds,
        '=ACTIVE' => 'Y',
    ],
]);

while ($user = $userResult->fetch()) {
    $users[(int)$user['ID']] = $user;
}

$createdTasks = [];
$errors = [];

foreach ($userIds as $responsibleUserId) {
    if (!isset($users[$responsibleUserId])) {
        $errors[] = [
            'userId' => $responsibleUserId,
            'message' => 'Активный пользователь не найден.',
        ];

        continue;
    }

    $user = $users[$responsibleUserId];

    $taskTitle = 'Создана Идея в банке идей: ' . $postTitle;

    $taskDescription =
        "Необходимо проверить публикацию.\n\n"
        . "[URL=" . $postUrl . "]Открыть публикацию[/URL]";

    try {
        $task = new CTasks();

        $taskId = $task->Add([
            'TITLE' => $taskTitle,
            'DESCRIPTION' => $taskDescription,
            'DESCRIPTION_IN_BBCODE' => 'Y',
            'CREATED_BY' => $currentUserId,
            'RESPONSIBLE_ID' => $responsibleUserId,
            'PRIORITY' => 1,
        ]);

        if (!$taskId) {
            throw new RuntimeException(
                'Bitrix не создал задачу. Проверьте права постановщика.'
            );
        }

        $taskId = (int)$taskId;

        $taskUrl = MODERATION_SITE_URL
            . '/company/personal/user/'
            . $responsibleUserId
            . '/tasks/task/view/'
            . $taskId
            . '/';

        $createdTasks[] = [
            'taskId' => $taskId,
            'userId' => $responsibleUserId,
        ];

        $email = trim((string)$user['EMAIL']);

        if ($email === '') {
            $errors[] = [
                'userId' => $responsibleUserId,
                'taskId' => $taskId,
                'message' => 'Задача создана, но у пользователя не заполнен EMAIL.',
            ];

            continue;
        }

        $userName = trim(implode(' ', array_filter([
            $user['NAME'],
            $user['SECOND_NAME'],
            $user['LAST_NAME'],
        ])));

        if ($userName === '') {
            $userName = 'Пользователь';
        }

        Event::send([
            'EVENT_NAME' => 'MODERATION_TASK_CREATED',
            'LID' => defined('SITE_ID') ? SITE_ID : 's1',
            'C_FIELDS' => [
                'EMAIL_TO' => $email,
                'USER_NAME' => $userName,
                'TASK_ID' => $taskId,
                'TASK_TITLE' => $taskTitle,
                'TASK_URL' => $taskUrl,
                'POST_ID' => $postId,
                'POST_TITLE' => $postTitle,
                'POST_URL' => $postUrl,
            ],
        ]);
    } catch (Throwable $exception) {
        $errors[] = [
            'userId' => $responsibleUserId,
            'message' => $exception->getMessage(),
        ];
    }
}

sendJsonResponse([
    'success' => count($createdTasks) > 0,
    'createdCount' => count($createdTasks),
    'createdTasks' => $createdTasks,
    'errors' => $errors,
    'message' => count($createdTasks) > 0
        ? 'Задачи созданы.'
        : 'Не удалось создать ни одной задачи.',
]);
