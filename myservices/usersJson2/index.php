<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Users Json");
?>
<?php

// Функция для обработки JSON файла и поиска пользователей
function processUsersFromJson($filePath) {
    // Проверяем существование файла
    if (!file_exists($filePath)) {
        return ['error' => 'Файл не найден'];
    }

    // Читаем и декодируем JSON
    $jsonContent = file_get_contents($filePath);
    $jsonData = json_decode($jsonContent, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'Ошибка парсинга JSON: ' . json_last_error_msg()];
    }

    // Преобразуем в нужный формат (массив с ключами: фио, email, xml_id)
    $usersArray = [];
    foreach ($jsonData as $item) {
        // Проверяем наличие необходимых ключей
        if (isset($item['EMAIL']) && isset($item['фио']) && isset($item['XML_ID'])) {
            $usersArray[] = [
                'email' => trim($item['EMAIL']),
                'фио' => trim($item['фио']),
                'xml_id' => trim($item['XML_ID'])
            ];
        }
    }

    if (empty($usersArray)) {
        return ['error' => 'Нет валидных записей в JSON файле'];
    }

    // Подключаем Битрикс
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

    // Получаем всех активных пользователей с группой ID = 12
    $users = getActiveUsersByGroup(12);

    if (empty($users)) {
        return ['error' => 'Пользователи с группой ID 12 не найдены'];
    }

    // Находим совпадения по email
    $matchedUsers = findUsersByEmailMatch($usersArray, $users);

    // Находим пользователей без XML_ID
    $usersWithoutXmlId = findUsersWithoutXmlId($matchedUsers);

    return [
        'total_json_users' => count($usersArray),
        'total_bx_users_in_group' => count($users),
        'matched_users' => count($matchedUsers),
        'users_without_xml_id' => $usersWithoutXmlId,
        'details' => [
            'matched_users_list' => $matchedUsers,
            'users_without_xml_id_list' => $usersWithoutXmlId
        ]
    ];
}

// Функция получения активных пользователей по ID группы
function getActiveUsersByGroup($groupId) {
    global $USER;

    $users = [];

    // Получаем пользователей с нужной группой
    $rsUsers = CUser::GetList(
        ($by = "ID"),
        ($order = "asc"),
        [
            "GROUPS_ID" => [$groupId],
            "ACTIVE" => "Y"
        ],
        [
            "FIELDS" => [
                "ID",
                "EMAIL",
                "NAME",
                "LAST_NAME",
                "SECOND_NAME",
                "XML_ID"
            ]
        ]
    );

    while ($arUser = $rsUsers->Fetch()) {
        // Формируем ФИО
        $fullName = trim(
            $arUser['LAST_NAME'] . ' ' .
            $arUser['NAME'] . ' ' .
            $arUser['SECOND_NAME']
        );

        $users[$arUser['EMAIL']] = [
            'id' => $arUser['ID'],
            'email' => $arUser['EMAIL'],
            'фио' => $fullName,
            'xml_id' => $arUser['XML_ID']
        ];
    }

    return $users;
}

// Функция поиска совпадений по email
function findUsersByEmailMatch($jsonUsers, $bxUsers) {
    $matchedUsers = [];

    foreach ($jsonUsers as $jsonUser) {
        $email = strtolower($jsonUser['email']);

        // Ищем совпадение в Битрикс пользователях
        foreach ($bxUsers as $bxEmail => $bxUser) {
            if (strtolower($bxEmail) === $email) {
                $matchedUsers[] = [
                    'json_data' => $jsonUser,
                    'bx_data' => $bxUser
                ];
                break;
            }
        }
    }

    return $matchedUsers;
}

// Функция поиска пользователей без XML_ID
function findUsersWithoutXmlId($matchedUsers) {
    $usersWithoutXmlId = [];

    foreach ($matchedUsers as $match) {
        if (empty($match['bx_data']['xml_id'])) {
            $usersWithoutXmlId[] = [
                'bx_id' => $match['bx_data']['id'],
                'email' => $match['bx_data']['email'],
                'фио' => $match['bx_data']['фио'],
                'json_xml_id' => $match['json_data']['xml_id']
            ];
        }
    }

    return $usersWithoutXmlId;
}

// Функция для вывода результатов в удобном формате
function displayResults($results) {
    if (isset($results['error'])) {
        echo "Ошибка: " . $results['error'] . "\n";
        return;
    }



    if (!empty($results['users_without_xml_id'])) {
        echo "=== СПИСОК ПОЛЬЗОВАТЕЛЕЙ БЕЗ XML_ID ===\n";
        echo "<br>";
        foreach ($results['users_without_xml_id'] as $index => $user) {
            echo ($index + 1) . ". ID: " . $user['bx_id'] .
                " <br> Email: " . $user['email'] .
                " <br>ФИО: " . $user['фио'] .
                " <br> JSON XML_ID: <b>" . $user['json_xml_id'] . "</b>\n";
            echo "<br>";
            echo "<br>";
            echo "<br>";
        }
    } else {
        echo "Все пользователи имеют XML_ID\n";
    }


}

// Пример использования
$filePath = 'users.json'; // Путь к вашему JSON файлу
$results = processUsersFromJson($filePath);
displayResults($results);

// Дополнительная функция для обновления XML_ID у пользователей
function updateUserXmlIds($matchedUsers) {
    global $USER;

    $updated = 0;
    $errors = 0;

    foreach ($matchedUsers as $match) {
        $bxUserId = $match['bx_data']['id'];
        $newXmlId = $match['json_data']['xml_id'];

        // Проверяем, что XML_ID не пустой и отличается от текущего
        if (!empty($newXmlId) && $match['bx_data']['xml_id'] != $newXmlId) {
            $user = new CUser;
            $fields = ['XML_ID' => $newXmlId];

            if ($user->Update($bxUserId, $fields)) {
                $updated++;
                echo "Обновлен пользователь ID: $bxUserId, XML_ID: $newXmlId\n";
            } else {
                $errors++;
                echo "Ошибка обновления пользователя ID: $bxUserId\n";
            }
        }
    }

    echo "\nОбновлено: $updated, Ошибок: $errors\n";
}

// Раскомментируйте для обновления XML_ID
// updateUserXmlIds($results['details']['matched_users_list']);

?>


<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>