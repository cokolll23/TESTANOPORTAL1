<?php

declare(strict_types=1);

define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('DisableEventsCheck', true);

require $_SERVER['DOCUMENT_ROOT']
    . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;

global $USER;

header('Content-Type: application/json; charset=UTF-8');

const IDEA_BLOG_URL = 'idea_s1';
const IDEA_DETAIL_URL_TEMPLATE = '/services/idea/#post_id#/';

const MAX_FILES = 10;
const MAX_FILE_SIZE = 20971520; // 20 МБ

$savedFileIds = [];

function sendJson(
    bool $success,
    string $message,
    array $data = [],
    int $status = 200
): never {
    http_response_code($status);

    echo Json::encode(
        array_merge(
            [
                'success' => $success,
                'message' => $message,
            ],
            $data
        ),
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    die();
}

function cleanText(mixed $value, int $maxLength = 10000): string
{
    if (!is_string($value)) {
        return '';
    }

    $value = trim($value);

    if (mb_strlen($value) > $maxLength) {
        $value = mb_substr($value, 0, $maxLength);
    }

    return $value;
}

function html(string $value): string
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

function paragraph(string $value): string
{
    return nl2br(html($value), false);
}

/**
 * Приводит $_FILES['files'] к нормальному массиву.
 */
function normalizeFiles(array $files): array
{
    if (
        !isset($files['name'])
        || !is_array($files['name'])
    ) {
        return [];
    }

    $result = [];

    foreach ($files['name'] as $index => $name) {
        $result[] = [
            'name' => $name,
            'type' => $files['type'][$index] ?? '',
            'tmp_name' => $files['tmp_name'][$index] ?? '',
            'error' => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
            'size' => (int)($files['size'][$index] ?? 0),
        ];
    }

    return $result;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendJson(
            false,
            'Метод запроса не поддерживается.',
            [],
            405
        );
    }

    if (!check_bitrix_sessid()) {
        sendJson(
            false,
            'Сессия истекла. Обновите страницу и повторите отправку.',
            [],
            403
        );
    }

    if (!$USER instanceof CUser || !$USER->IsAuthorized()) {
        sendJson(
            false,
            'Для отправки идеи необходимо авторизоваться.',
            [],
            401
        );
    }

    if (!Loader::includeModule('blog')) {
        throw new RuntimeException(
            'Не удалось подключить модуль «Блоги».'
        );
    }

    if (!Loader::includeModule('idea')) {
        throw new RuntimeException(
            'Не удалось подключить модуль «Менеджер идей».'
        );
    }

    $audienceMap = [
        'department' => 'Сотрудникам моего подразделения',
        'departments' => 'Нескольким подразделениям',
        'all' => 'Всем сотрудникам',
        'projects' => 'Проектам организации',
        'residents' => 'Жителям и туристам',
    ];

    $complexityMap = [
        'fast' => 'Можно реализовать быстро — до 1 месяца',
        'medium' => 'Потребуется проработка — от 1 до 6 месяцев',
        'long' => 'Долгосрочная идея — более 6 месяцев',
    ];

    $audienceInput = $_POST['audience'] ?? [];

    if (!is_array($audienceInput)) {
        $audienceInput = [];
    }

    /*
     * Сервер принимает только заранее разрешённые значения.
     */
    $audienceKeys = array_values(
        array_unique(
            array_intersect(
                array_keys($audienceMap),
                array_map('strval', $audienceInput)
            )
        )
    );

    $ideaDescription = cleanText(
        $_POST['idea_description'] ?? ''
    );

    $ideaProblem = cleanText(
        $_POST['idea_problem'] ?? ''
    );

    $ideaResult = cleanText(
        $_POST['idea_result'] ?? ''
    );

    $complexityKey = cleanText(
        $_POST['complexity'] ?? '',
        30
    );

    $errors = [];

    if ($audienceKeys === []) {
        $errors[] = 'Выберите, кому поможет идея.';
    }

    if ($ideaDescription === '') {
        $errors[] = 'Заполните описание идеи.';
    }

    if ($ideaProblem === '') {
        $errors[] = 'Опишите проблему, которую решает идея.';
    }

    if ($ideaResult === '') {
        $errors[] = 'Опишите ожидаемый результат.';
    }

    if (!isset($complexityMap[$complexityKey])) {
        $errors[] = 'Выберите сложность реализации.';
    }

    if ($errors !== []) {
        sendJson(
            false,
            implode(' ', $errors),
            ['errors' => $errors],
            422
        );
    }

    $blog = CBlog::GetByUrl(IDEA_BLOG_URL);

    if (!is_array($blog) || empty($blog['ID'])) {
        throw new RuntimeException(
            'Не найден блог банка идей с кодом '
            . IDEA_BLOG_URL
            . '. Проверьте параметр BLOG_URL.'
        );
    }

    $blogId = (int)$blog['ID'];
    $userId = (int)$USER->GetID();

    /*
     * Проверка права пользователя на создание записей.
     * Администратор сайта допускается независимо от прав блога.
     */
    if (!$USER->IsAdmin()) {
        $permission = CBlog::GetBlogUserPostPerms(
            $userId,
            $blogId
        );

        $writePermissions = array_filter([
            defined('BLOG_PERMS_WRITE')
                ? BLOG_PERMS_WRITE
                : 'W',

            defined('BLOG_PERMS_FULL')
                ? BLOG_PERMS_FULL
                : 'X',
        ]);

        if (!in_array($permission, $writePermissions, true)) {
            sendJson(
                false,
                'У вас нет прав на добавление идей.',
                [],
                403
            );
        }
    }

    $files = isset($_FILES['files'])
        ? normalizeFiles($_FILES['files'])
        : [];

    /*
     * UPLOAD_ERR_NO_FILE не считается ошибкой,
     * поскольку файлы на шестом шаге необязательны.
     */
    $files = array_values(
        array_filter(
            $files,
            static fn(array $file): bool =>
                (int)$file['error'] !== UPLOAD_ERR_NO_FILE
        )
    );

    if (count($files) > MAX_FILES) {
        sendJson(
            false,
            'Можно загрузить не более '
            . MAX_FILES
            . ' файлов.',
            [],
            422
        );
    }

    $allowedExtensions = [
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx',
        'txt',
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp',
        'zip',
    ];

    $allowedMimeTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/zip',
        'application/x-zip-compressed',
        'application/octet-stream',
    ];

    $attachmentLinks = [];
    $finfo = new finfo(FILEINFO_MIME_TYPE);

    foreach ($files as $file) {
        if ((int)$file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException(
                'Ошибка загрузки файла «'
                . (string)$file['name']
                . '». Код: '
                . (int)$file['error']
            );
        }

        if (
            $file['size'] <= 0
            || $file['size'] > MAX_FILE_SIZE
        ) {
            throw new RuntimeException(
                'Файл «'
                . (string)$file['name']
                . '» имеет недопустимый размер.'
            );
        }

        if (
            !is_string($file['tmp_name'])
            || !is_uploaded_file($file['tmp_name'])
        ) {
            throw new RuntimeException(
                'Файл «'
                . (string)$file['name']
                . '» не является корректной загрузкой.'
            );
        }

        $originalName = basename((string)$file['name']);

        $extension = mb_strtolower(
            (string)pathinfo(
                $originalName,
                PATHINFO_EXTENSION
            )
        );

        if (!in_array($extension, $allowedExtensions, true)) {
            throw new RuntimeException(
                'Тип файла «'
                . $originalName
                . '» не разрешён.'
            );
        }

        $detectedMime = $finfo->file($file['tmp_name']);

        /*
         * У DOC/XLS/PPT и ZIP-подобных форматов MIME может
         * определяться как application/octet-stream.
         */
        if (
            is_string($detectedMime)
            && !in_array($detectedMime, $allowedMimeTypes, true)
        ) {
            throw new RuntimeException(
                'Содержимое файла «'
                . $originalName
                . '» не соответствует разрешённым типам.'
            );
        }

        $fileArray = [
            'name' => $originalName,
            'type' => $detectedMime ?: (string)$file['type'],
            'tmp_name' => (string)$file['tmp_name'],
            'error' => UPLOAD_ERR_OK,
            'size' => (int)$file['size'],
            'MODULE_ID' => 'idea',
        ];

        $fileId = (int)CFile::SaveFile(
            $fileArray,
            'idea_attachments'
        );

        if ($fileId <= 0) {
            throw new RuntimeException(
                'Не удалось сохранить файл «'
                . $originalName
                . '».'
            );
        }

        $savedFileIds[] = $fileId;

        $filePath = CFile::GetPath($fileId);

        if (is_string($filePath) && $filePath !== '') {
            $attachmentLinks[] = [
                'name' => $originalName,
                'path' => $filePath,
            ];
        }
    }

    $audienceLabels = array_map(
        static fn(string $key): string => $audienceMap[$key],
        $audienceKeys
    );

    $audienceHtml = '';

    foreach ($audienceLabels as $audienceLabel) {
        $audienceHtml .= '<li>'
            . html($audienceLabel)
            . '</li>';
    }


    $html = <<<HTML
<div class="card">
    <h2>Карточка</h2>
    <p>Это содержимое карточки.</p>
</div>
HTML;




    $detailText = ''
        . paragraph('Кому поможет идея')
        . '<ul>' . $audienceHtml . '</ul>'
        .PHP_EOL .PHP_EOL
        . paragraph('Описание идеи')
        .PHP_EOL
        . paragraph($ideaDescription)
        .PHP_EOL .PHP_EOL
        . 'Какую проблему решает идея'
        .PHP_EOL
        . paragraph($ideaProblem)
        .PHP_EOL .PHP_EOL
        . 'Ожидаемый результат'
        .PHP_EOL
        .  paragraph($ideaResult)
        .PHP_EOL .PHP_EOL
        . 'Сложность реализации'.PHP_EOL
        . html($complexityMap[$complexityKey]) .PHP_EOL.PHP_EOL
    ;

    if ($attachmentLinks !== []) {
        $detailText .= 'Приложенные материалы <ul>';

        foreach ($attachmentLinks as $attachment) {
            $detailText .= '<li><a href="'
                . html($attachment['path'])
                . '" target="_blank" rel="noopener">'
                . html($attachment['name'])
                . '</a></li>'.PHP_EOL.PHP_EOL;
        }

        $detailText .= '</ul>';
    }

    /*
     * Так как отдельного поля названия в форме нет,
     * заголовок строится из начала описания идеи.
     */
    $title = preg_replace(
        '/\s+/u',
        ' ',
        $ideaDescription
    );

    $title = trim((string)$title);

    if (mb_strlen($title) > 120) {
        $title = rtrim(mb_substr($title, 0, 117))
            . '...';
    }

    if ($title === '') {
        $title = 'Новая идея';
    }

    $postFields = [
        'TITLE' => $title,
        'DETAIL_TEXT' => $detailText,
        'DETAIL_TEXT_TYPE' => 'text',
        'BLOG_ID' => $blogId,
        'AUTHOR_ID' => $userId,
        'PUBLISH_STATUS' => 'P',
        'ENABLE_TRACKBACK' => 'N',
        'ENABLE_COMMENTS' => 'Y',
        'MICRO' => 'N',

    ];

    $postId = (int)CBlogPost::Add($postFields);

    if ($postId <= 0) {
        global $APPLICATION;

        $exception = $APPLICATION->GetException();

        throw new RuntimeException(
            $exception
                ? $exception->GetString()
                : 'Не удалось создать запись в банке идей.'
        );
    }else{
        global $USER_FIELD_MANAGER;

// $newID — это ID созданной вами записи блога (POST_ID)
// UF_STATUS — это код поля, значение которого вы хотите установить
        $USER_FIELD_MANAGER->Update('BLOG_POST', $postId, [
            'UF_STATUS' => 1, // 1 соответствует "Новые"
        ]);
    }

    /*
     * После успешного создания запись владеет ссылками
     * на сохранённые файлы. При дальнейшей реализации
     * удаления идеи нужно также удалять эти CFile.
     */
    $savedFileIds = [];

    $ideaUrl = str_replace(
        '#post_id#',
        (string)$postId,
        IDEA_DETAIL_URL_TEMPLATE
    );

    sendJson(
        true,
        'Идея успешно отправлена.',
        [
            'ideaId' => $postId,
            'url' => $ideaUrl,
        ]
    );
} catch (Throwable $exception) {
    /*
     * Удаляем файлы, если они сохранились,
     * но сама идея не была создана.
     */
    foreach ($savedFileIds as $fileId) {
        CFile::Delete((int)$fileId);
    }

    sendJson(
        false,
        $exception->getMessage(),
        [],
        500
    );
}
