<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Users Json");
?>
<?php
// Функция для обработки JSON файла и сравнения с пользователями Битрикс
function processUsersFromJson($jsonFilePath)
{
    // 1. Проверяем существование файла
    if (!file_exists($jsonFilePath)) {
        return ['error' => 'Файл не найден'];
    }

    // 2. Читаем и декодируем JSON
    $jsonContent = file_get_contents($jsonFilePath);
    $jsonData = json_decode($jsonContent, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'Ошибка парсинга JSON: ' . json_last_error_msg()];
    }

    // 3. Преобразуем в массив PHP с ключами: фио, email, xml_id
    $convertedUsers = [];
    foreach ($jsonData as $item) {
        // Проверяем наличие необходимых ключей
        if (isset($item['EMAIL']) && isset($item['фио']) && isset($item['XML_ID'])) {
            $convertedUsers[] = [
                'фио' => $item['фио'],
                'email' => strtolower(trim($item['EMAIL'])), // Приводим email к нижнему регистру для сравнения
                'xml_id' => $item['XML_ID']
            ];
        }
    }

    // 4. Получаем активных пользователей из Битрикс
    $bitrixUsers = getActiveBitrixUsers();

    if (isset($bitrixUsers['error'])) {
        return ['error' => $bitrixUsers['error']];
    }

    // 5. Сравниваем и находим совпадения
    $matchedUsers = [];
    $usersWithoutXmlId = [];
    $usersWithXmlId = [];

    foreach ($convertedUsers as $jsonUser) {
        $email = $jsonUser['email'];

        // Ищем пользователя с таким email в Битрикс
        if (isset($bitrixUsers[$email])) {
            $bitrixUser = $bitrixUsers[$email];

            // Проверяем, есть ли у пользователя XML_ID (внешний код)
            if (empty($bitrixUser['XML_ID'])) {
                $usersWithoutXmlId[] = [
                    'фио' => $jsonUser['фио'],
                    'email' => $jsonUser['email'],
                    'xml_id' => $jsonUser['xml_id'],
                    'bitrix_id' => $bitrixUser['ID'],
                    'bitrix_name' => $bitrixUser['NAME'] . ' ' . $bitrixUser['LAST_NAME']
                ];
            } else {
                $usersWithXmlId[] = [
                    'фио' => $jsonUser['фио'],
                    'email' => $jsonUser['email'],
                    'xml_id' => $jsonUser['xml_id'],
                    'bitrix_xml_id' => $bitrixUser['XML_ID'],
                    'bitrix_id' => $bitrixUser['ID']
                ];
            }

            $matchedUsers[] = [
                'json_data' => $jsonUser,
                'bitrix_data' => $bitrixUser
            ];
        }
    }

    // 6. Возвращаем результаты
    return [
        'total_json_users' => count($convertedUsers),
        'total_bitrix_users' => count($bitrixUsers),
        'matched_users' => $matchedUsers,
        'users_without_xml_id' => $usersWithoutXmlId,
        'users_with_xml_id' => $usersWithXmlId,
        'converted_users' => $convertedUsers // Для отладки
    ];
}

// Функция для получения активных пользователей из Битрикс
function getActiveBitrixUsers()
{
    // Подключаем модуль Битрикс
    if (!CModule::IncludeModule('main')) {
        return ['error' => 'Не удалось подключить модуль main'];
    }

    $users = [];

    // Получаем всех активных пользователей
    $rsUsers = CUser::GetList(
        $by = 'ID',
        $order = 'ASC',
        [
            'ACTIVE' => 'Y'
        ],
        [
            'FIELDS' => ['ID', 'NAME', 'LAST_NAME', 'EMAIL', 'XML_ID', 'ACTIVE']
        ]
    );

    while ($arUser = $rsUsers->Fetch()) {
        $email = strtolower(trim($arUser['EMAIL']));
        $users[$email] = [
            'ID' => $arUser['ID'],
            'NAME' => $arUser['NAME'],
            'LAST_NAME' => $arUser['LAST_NAME'],
            'EMAIL' => $arUser['EMAIL'],
            'XML_ID' => $arUser['XML_ID'],
            'ACTIVE' => $arUser['ACTIVE']
        ];
    }

    return $users;
}

// Функция для вывода результатов в удобном формате
function displayResults($results)
{
    if (isset($results['error'])) {
        echo "Ошибка: " . $results['error'] . "\n";
        return;
    }
    echo "<br>";
    echo "=== РЕЗУЛЬТАТЫ ОБРАБОТКИ ===\n\n";
    echo "<br>";
    echo "Всего записей в JSON: " . $results['total_json_users'] . "\n";
    echo "<br>";
    echo "Всего активных пользователей в Битрикс: " . $results['total_bitrix_users'] . "\n";
    echo "<br>";
    echo "Найдено совпадений: " . count($results['matched_users']) . "\n";
    echo "<br>";
    echo "Пользователей с XML_ID: " . count($results['users_with_xml_id']) . "\n";
    echo "<br>";
    echo "Пользователей БЕЗ XML_ID: " . count($results['users_without_xml_id']) . "\n\n";
    echo "<br>";
    echo "<br>";
    // Выводим список пользователей без XML_ID
    if (!empty($results['users_without_xml_id'])) {
        echo "=== СПИСОК ПОЛЬЗОВАТЕЛЕЙ БЕЗ XML_ID ===\n";
        echo "<br>";echo "<br>";
        foreach ($results['users_without_xml_id'] as $index => $user) {
            echo ($index + 1) . ". ФИО: " . $user['фио'] . "\n";
            echo "<br>";
            echo "   Bitrix ID: " . $user['bitrix_id'] . "\n";
            echo "<br>";
            echo "   Bitrix имя: " . $user['bitrix_name'] . "\n";
            echo "<br>";
            echo "   Email: " . $user['email'] . "\n";
            echo "<br>";
            echo "   JSON XML_ID: " . $user['xml_id'] . "\n";


            echo "<br>";

            echo "<br>";
        }
        echo "<br>";
    } else {
        echo "Все найденные пользователи имеют XML_ID.\n";
    }

    // Дополнительно выводим список пользователей с XML_ID (для информации)
    if (!empty($results['users_with_xml_id']) && count($results['users_with_xml_id']) <= 10) {
        echo "\n=== ПОЛЬЗОВАТЕЛИ С XML_ID (первые 10) ===\n";
        foreach (array_slice($results['users_with_xml_id'], 0, 10) as $index => $user) {
            echo ($index + 1) . ". " . $user['фио'] . " - " . $user['email'] .
                " (XML_ID: " . $user['bitrix_xml_id'] . ")\n";
        }
    }
}

// ========== ИСПОЛЬЗОВАНИЕ ==========

// Укажите путь к вашему JSON файлу
$jsonFilePath = 'users.json';

// Запускаем обработку
$results = processUsersFromJson($jsonFilePath);

// Выводим результаты
displayResults($results);

// Если нужно получить только массив пользователей без XML_ID
if (!isset($results['error'])) {
    $usersWithoutXmlId = $results['users_without_xml_id'];
    // Теперь $usersWithoutXmlId содержит массив пользователей без XML_ID
    // Можете использовать этот массив для дальнейшей обработки
}


// Функция для обновления XML_ID у пользователей, у которых он отсутствует
function updateUserXmlId($usersToUpdate)
{
    if (!CModule::IncludeModule('main')) {
        return ['error' => 'Не удалось подключить модуль main'];
    }

    $updated = [];
    $errors = [];

    foreach ($usersToUpdate as $user) {
        $userObj = new CUser();
        $fields = [
            'XML_ID' => $user['xml_id']
        ];

        $result = $userObj->Update($user['bitrix_id'], $fields);

        if ($result) {
            $updated[] = $user['email'];
        } else {
            $errors[] = [
                'email' => $user['email'],
                'error' => $userObj->LAST_ERROR
            ];
        }
    }

    return [
        'updated' => $updated,
        'errors' => $errors
    ];
}

// Пример использования:
 //$updateResult = updateUserXmlId($results['users_without_xml_id']);
 //pretty_print($updateResult);


?>


<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>