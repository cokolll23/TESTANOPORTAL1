<?php

use Bitrix\Main\Routing\RoutingConfigurator;
use Korus\Office\Controller\Employee;
use Korus\Office\Controller\Components;
use Korus\Office\Controller\Request;

return function (RoutingConfigurator $routes) {
    $routes
        ->prefix('api')
        ->group(function (RoutingConfigurator $routes) {
            // API v1
            $routes
                ->prefix('v1')
                ->group(function (RoutingConfigurator $routes) {
                    // Сотрудник
                    $routes
                        ->prefix('employees/{id}')
                        ->where('id', '\d+')
                        ->group(function (RoutingConfigurator $routes) {
                            // Персональная информация
                            $routes->post('personal', [Employee::class, 'updatePersonalData']);
                            $routes->get('personal', [Employee::class, 'getPersonalData']);

                            // Обо мне
                            $routes->get('about', [Employee::class, 'getPersonalAbout']);
                            $routes->post('about', [Employee::class, 'updatePersonalAbout']);
                            $routes->delete('about', [Employee::class, 'deletePersonalAbout']);

                            // Загрузка/удаление аватарки
                            $routes->post('photo', [Employee::class, 'updatePhoto']);
                            $routes->delete('photo', [Employee::class, 'deletePhoto']);

                            // Орг. структура
                            $routes->get('structure', [Employee::class, 'getStructure']);

                            // Компетенции
                            $routes->get('competencies', [Employee::class, 'getCompetenceList']);
                            $routes->get('competencies/search', [Employee::class, 'searchCompetence']);
                            $routes->post('competencies', [Employee::class, 'addCompetence']);
                            $routes->delete('competencies', [Employee::class, 'deleteCompetence']);

                            // Интересы
                            $routes->get('interests/search', [Employee::class, 'searchInterest']);
                            $routes->get('interests', [Employee::class, 'getInterestList']);
                            $routes->post('interests', [Employee::class, 'addInterest']);
                            $routes->delete('interests', [Employee::class, 'deleteInterest']);

                            // Благодарности
                            $routes->get('gratitudes', [Employee::class, 'getGratitudeList']);

                            // Сервисы
                            $routes->get('services/favorite', [Employee::class, 'getFavoriteServiceList']);
                            $routes->get('services', [Employee::class, 'getServiceList']);

                            // Отпуск
                            $routes->get('vacations/current', [Employee::class, 'getCurrentVacation']);

                            // Заявки
                            $routes->get('requests', [Employee::class, 'getRequestList']);

                            // Магазин
                            $routes->get('shop', [Employee::class, 'getShopAccount']);

                            // Эмодзи
                            $routes->get('emojiList', [Employee::class, 'getListEmoji']);
                            $routes->get('emoji', [Employee::class, 'getEmoji']);
                            $routes->put('emoji/{emojiId}', [Employee::class, 'putEmoji']);

                            // Настройки полей
                            $routes->post('fieldSettings', [Employee::class, 'updateFieldSettings']);

                            // Уволить
                            $routes->get('fire', [Employee::class, 'fireEmployee']);

                            // Принять на работу
                            $routes->get('hire', [Employee::class, 'hireEmployee']);

                            // Дать права администратора
                            $routes->get('setAdminRights', [Employee::class, 'setAdminRightsEmployee']);

                            // Забрать права администратора
                            $routes->get('removeAdminRights', [Employee::class, 'removeAdminRightsEmployee']);
                        });

                    // Инициализация ЛК
                    $routes
                        ->get('employees/{id}', [Employee::class, 'initialize'])
                        ->where('id', '\d+');

                    // Заявки
                    $routes
                        ->prefix('requests/{requestId}')
                        ->where('requestId', '\d+')
                        ->group(function (RoutingConfigurator $routes) {
                            // Комментарии
                            $routes->post('comments/view', [Request::class, 'markCommentViewed']);

                        });

                    // Компоненты
                    $routes
                        ->prefix('components')
                        ->group(function (RoutingConfigurator $routes) {
                            // Визуальный редактор
                            $routes->get('editor', [Components::class, 'getEditor']);

                            // Меню в профиле
                            $routes->get('profile_menu', [Components::class, 'getProfileMenu']);
                        });
                });
        });
};
