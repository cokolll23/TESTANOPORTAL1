<?php

namespace Korus\Office\EventHandlers;

class OnGenericMenu
{
    public static function getGenericMenu(array $menu): array
    {
        return [
            [
                'BITRIX_ID' => 'menu_office',
                'LINK' => '/company/personal/user/#USER#/',
                'TYPE' => 'STATIC',
                'TITLE' => 'Личный кабинет',
                'ICON' => dirname(__DIR__, 2) . '/src/icons/static-menu/office.svg',
                'SORT' => 700
            ],
        ];
    }
}