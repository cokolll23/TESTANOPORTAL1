<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Users Json");
?>
<?php


/**
 * Сервис для импорта пользователей из JSON и обновления внешних кодов (xml_id) в Битрикс24
 */



class UserImportService
{
    private $jsonFilePath;
    private $importedUsers = [];
    private $matchedUsers = [];
    private $updatedUsers = [];
    private $errors = [];

    /**
     * Конструктор класса
     *
     * @param string $jsonFilePath Путь к JSON файлу
     */
    public function __construct($jsonFilePath)
    {
        $this->jsonFilePath = $jsonFilePath;
    }

    /**
     * Основной метод выполнения импорта
     */
    public function run()
    {
        try {
            // 1. Читаем и парсим JSON файл
            $this->parseJsonFile();

            // 2. Преобразуем в массив с нужными ключами
            $this->convertToArray();

            // 3. Сравниваем с активными пользователями Битрикс
            $this->compareWithBitrixUsers();

            // 4. Обновляем пользователей без xml_id
            $this->updateUsersWithoutXmlId();

            // 5. Выводим результат
            $this->showResult();

        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            $this->showResult();
        }
    }

    /**
     * Чтение и парсинг JSON файла
     */
    private function parseJsonFile()
    {
        if (!file_exists($this->jsonFilePath)) {
            throw new Exception("Файл не найден: " . $this->jsonFilePath);
        }

        $jsonContent = file_get_contents($this->jsonFilePath);
        if ($jsonContent === false) {
            throw new Exception("Не удалось прочитать файл: " . $this->jsonFilePath);
        }

        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Ошибка парсинга JSON: " . json_last_error_msg());
        }

        if (empty($data)) {
            throw new Exception("JSON файл пуст или содержит некорректные данные");
        }

        $this->importedUsers = $data;
    }

    /**
     * Преобразование данных в массив с ключами: фио, email, xml_id
     */
    private function convertToArray()
    {
        $converted = [];
        $index = 0;

        foreach ($this->importedUsers as $user) {
            // Проверяем наличие обязательных полей
            if (!isset($user['email']) || empty($user['email'])) {
                $this->errors[] = "Строка " . ($index + 1) . ": пропущен email";
                $index++;
                continue;
            }

            // Формируем массив с нужными ключами
            $converted[] = [
                'фио' => isset($user['фио']) ? trim($user['фио']) : '',
                'email' => trim($user['email']),
                'xml_id' => isset($user['xml_id']) ? trim($user['xml_id']) : ''
            ];

            $index++;
        }

        $this->importedUsers = $converted;

        if (empty($this->importedUsers)) {
            throw new Exception("Нет валидных данных для импорта");
        }
    }

    /**
     * Сравнение с активными пользователями Битрикс по email
     */
    private function compareWithBitrixUsers()
    {
        if (!CModule::IncludeModule('main')) {
            throw new Exception("Модуль main не подключен");
        }

        $this->matchedUsers = [];

        // Получаем всех активных пользователей
        $rsUsers = CUser::GetList(
            ($by = "ID"),
            ($order = "asc"),
            ['ACTIVE' => 'Y'],
            ['FIELDS' => ['ID', 'EMAIL', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'XML_ID']]
        );

        $bitrixUsers = [];
        while ($arUser = $rsUsers->Fetch()) {
            $email = strtolower(trim($arUser['EMAIL']));
            if (!empty($email)) {
                $bitrixUsers[$email] = $arUser;
            }
        }

        // Сравниваем с импортированными пользователями
        foreach ($this->importedUsers as &$importedUser) {
            $email = strtolower(trim($importedUser['email']));

            if (isset($bitrixUsers[$email])) {
                $this->matchedUsers[$email] = [
                    'imported' => $importedUser,
                    'bitrix' => $bitrixUsers[$email]
                ];

                // Добавляем ID пользователя в импортированный массив
                $importedUser['bitrix_id'] = $bitrixUsers[$email]['ID'];
            }
        }
    }

    /**
     * Обновление пользователей, у которых отсутствует xml_id (внешний код)
     */
    private function updateUsersWithoutXmlId()
    {
        if (!CModule::IncludeModule('main')) {
            throw new Exception("Модуль main не подключен");
        }

        $this->updatedUsers = [];

        foreach ($this->matchedUsers as $email => $data) {
            $bitrixUser = $data['bitrix'];
            $importedUser = $data['imported'];

            // Проверяем, есть ли xml_id у пользователя Битрикс
            if (empty($bitrixUser['XML_ID']) && !empty($importedUser['xml_id'])) {
                // Обновляем пользователя
                $user = new CUser;
                $updateResult = $user->Update(
                    $bitrixUser['ID'],
                    ['XML_ID' => $importedUser['xml_id']]
                );

                if ($updateResult) {
                    $this->updatedUsers[] = [
                        'id' => $bitrixUser['ID'],
                        'email' => $email,
                        'фио' => $importedUser['фио'],
                        'old_xml_id' => $bitrixUser['XML_ID'],
                        'new_xml_id' => $importedUser['xml_id']
                    ];
                } else {
                    $this->errors[] = "Не удалось обновить пользователя ID: {$bitrixUser['ID']}, Email: $email. Ошибка: " . $user->LAST_ERROR;
                }
            }
        }
    }

    /**
     * Вывод результатов работы сервиса
     */
    private function showResult()
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Импорт пользователей</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
                .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                h1 { color: #333; border-bottom: 2px solid #1e88e5; padding-bottom: 10px; }
                .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0; }
                .stat-card { background: #e3f2fd; padding: 15px; border-radius: 6px; }
                .stat-card h3 { margin: 0; color: #1565c0; }
                .stat-card .number { font-size: 28px; font-weight: bold; color: #0d47a1; margin: 5px 0; }
                .success { color: #2e7d32; background: #e8f5e9; padding: 10px; border-radius: 4px; margin: 10px 0; }
                .error { color: #c62828; background: #ffebee; padding: 10px; border-radius: 4px; margin: 10px 0; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th { background: #1976d2; color: white; padding: 12px; text-align: left; }
                td { padding: 12px; border-bottom: 1px solid #e0e0e0; }
                tr:hover { background: #f5f5f5; }
                .no-data { text-align: center; color: #666; padding: 40px; }
                .back-link { display: inline-block; margin-top: 20px; color: #1976d2; text-decoration: none; }
                .back-link:hover { text-decoration: underline; }
            </style>
        </head>
        <body>
        <div class="container">
            <h1>📊 Результаты импорта пользователей</h1>

            <div class="stats">
                <div class="stat-card">
                    <h3>Всего импортировано</h3>
                    <div class="number"><?= count($this->importedUsers) ?></div>
                </div>
                <div class="stat-card">
                    <h3>Найдено совпадений</h3>
                    <div class="number"><?= count($this->matchedUsers) ?></div>
                </div>
                <div class="stat-card">
                    <h3>Обновлено пользователей</h3>
                    <div class="number"><?= count($this->updatedUsers) ?></div>
                </div>
            </div>

            <?php if (!empty($this->errors)): ?>
                <div class="error">
                    <strong>⚠️ Ошибки:</strong>
                    <ul>
                        <?php foreach ($this->errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($this->updatedUsers)): ?>
                <div class="success">
                    <strong>✅ Обновлены следующие пользователи:</strong>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>EMAIL</th>
                        <th>ФИО</th>
                        <th>Старый XML_ID</th>
                        <th>Новый XML_ID</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->updatedUsers as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['фио']) ?></td>
                            <td><?= htmlspecialchars($user['old_xml_id'] ?: 'пусто') ?></td>
                            <td><strong><?= htmlspecialchars($user['new_xml_id']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #666;">Нет пользователей для обновления</p>
            <?php endif; ?>

            <?php if (empty($this->updatedUsers) && empty($this->errors)): ?>
                <div class="success">
                    <strong>✅ Все пользователи уже имеют xml_id или нет совпадений по email</strong>
                </div>
            <?php endif; ?>
        </div>
        </body>
        </html>
        <?php
    }
}

// ============= ИСПОЛЬЗОВАНИЕ СЕРВИСА =============

// Указываем путь к JSON файлу (можно изменить или передать через GET параметр)
echo $jsonFile = isset($_GET['file']) ? $_GET['file'] : 'users.json';

// Создаем экземпляр сервиса и запускаем
$service = new UserImportService($jsonFile);
$service->run();

?>






<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>