<?php
namespace Lab\Helpers;


use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;

class UserRegistrationService
{
    /**
     * Регистрация пользователей с проверкой по Email
     * @param array $usersData Массив данных пользователей
     * @return array Результаты регистрации
     */
    public static function registerUsers(array $usersData): array
    {
        Loader::includeModule('main');
        
        $registered = [];
        $notRegistered = [];
        $errors = [];
        
        foreach ($usersData as $index => $userData) {
            try {
                // Валидация входных данных
                $validationResult = self::validateUserData($userData);
                if (!$validationResult['success']) {
                    $notRegistered[] = [
                        'data' => $userData,
                        'reason' => 'VALIDATION_ERROR'
                    ];
                    $errors[] = [
                        'email' => $userData['EMAIL'] ?? '',
                        'message' => $validationResult['message'],
                        'user_index' => $index
                    ];
                    continue;
                }
                
                $email = trim($userData['EMAIL']);
                
                // Проверка существования пользователя с таким Email
                if (self::userExists($email)) {
                    $notRegistered[] = [
                        'data' => $userData,
                        'reason' => 'USER_EXISTS'
                    ];
                    $errors[] = [
                        'email' => $email,
                        'message' => 'Пользователь с таким Email уже существует',
                        'user_index' => $index
                    ];
                    continue;
                }
                
                // Регистрация пользователя
                $registrationResult = self::registerSingleUser($userData);
                
                if ($registrationResult['success']) {
                    $registered[] = [
                        'id' => $registrationResult['user_id'],
                        'email' => $email,
                        'data' => $userData,
                        'additional_info' => $registrationResult['additional_info'] ?? null
                    ];
                } else {
                    $notRegistered[] = [
                        'data' => $userData,
                        'reason' => 'REGISTRATION_FAILED'
                    ];
                    $errors[] = [
                        'email' => $email,
                        'message' => $registrationResult['message'],
                        'user_index' => $index,
                        'bitrix_error' => $registrationResult['bitrix_error'] ?? null
                    ];
                }
                
            } catch (\Exception $e) {
                $notRegistered[] = [
                    'data' => $userData,
                    'reason' => 'EXCEPTION'
                ];
                $errors[] = [
                    'email' => $userData['EMAIL'] ?? '',
                    'message' => 'Системная ошибка: ' . $e->getMessage(),
                    'user_index' => $index
                ];
            }
        }
        
        return [
            'registered' => $registered,
            'not_registered' => $notRegistered,
            'errors' => $errors,
            'summary' => [
                'total' => count($usersData),
                'success' => count($registered),
                'failed' => count($notRegistered)
            ]
        ];
    }
    
    /**
     * Валидация данных пользователя
     */
    private static function validateUserData(array $data): array
    {
        // Проверка обязательных полей
        if (empty($data['EMAIL'])) {
            return ['success' => false, 'message' => 'Email обязателен для заполнения'];
        }
        
        $email = trim($data['EMAIL']);
        
        // Валидация формата Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Неверный формат Email'];
        }
        
        // Проверка пароля (если требуется)
        if (empty($data['PASSWORD'])) {
            return ['success' => false, 'message' => 'Пароль обязателен для заполнения'];
        }
        
        if (strlen($data['PASSWORD']) < 6) {
            return ['success' => false, 'message' => 'Пароль должен быть не менее 6 символов'];
        }
        
        return ['success' => true];
    }
    
    /**
     * Проверка существования пользователя по Email
     */
    private static function userExists(string $email): bool
    {
        $user = UserTable::getList([
            'select' => ['ID'],
            'filter' => ['=EMAIL' => $email],
            'limit' => 1
        ])->fetch();
        
        return !empty($user);
    }
    
    /**
     * Регистрация одного пользователя
     */
    private static function registerSingleUser(array $data): array
    {
        $user = new \CUser;
        
        // Базовые обязательные поля
        $fields = [
            'EMAIL' => trim($data['EMAIL']),
            'PASSWORD' => $data['PASSWORD'],
            'CONFIRM_PASSWORD' => $data['PASSWORD'],
            'ACTIVE' => $data['ACTIVE'] ?? 'Y',
        ];
        
        // Добавляем дополнительные поля если они есть
        $optionalFields = ['NAME', 'LAST_NAME', 'SECOND_NAME', 'PERSONAL_PHONE', 'WORK_COMPANY','GROUP_ID'];
        foreach ($optionalFields as $field) {
            if (!empty($data[$field])) {
                $fields[$field] = $data[$field];
            }
        }
        
        // Добавляем пользовательские поля UF_*
        foreach ($data as $key => $value) {
            if (strpos($key, 'UF_') === 0 && !empty($value)) {
                $fields[$key] = $value;
            }
        }
        
        // Генерация логина из email если не указан
        if (empty($data['LOGIN'])) {
            $fields['LOGIN'] = $fields['EMAIL'];
        } else {
            $fields['LOGIN'] = $data['LOGIN'];
        }
        
        // Регистрация пользователя
        $userId = $user->Add($fields);
        
        if ($userId) {
            return [
                'success' => true,
                'user_id' => $userId,
                'additional_info' => [
                    'fields_set' => array_keys($fields)
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => $user->LAST_ERROR,
                'bitrix_error' => $user->LAST_ERROR
            ];
        }
    }
}

// Пример использования





// Пример использования контроллера
/*
$apiResult = UserRegistrationController::registerAction([
    'users' => $usersToRegister
]);

$singleResult = UserRegistrationController::registerSingleAction([
    'EMAIL' => 'newuser@example.com',
    'PASSWORD' => 'strongpassword123',
    'NAME' => 'Новый',
    'LAST_NAME' => 'Пользователь'
]);
*/