<?php

namespace Korus\Office\Entity;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\EntityError;
use Bitrix\Main\ORM\Event;
use Bitrix\Main\ORM\EventResult;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\ORM\Objectify\EntityObject;
use Bitrix\Main\SystemException;
use CFile;
use Korus\Menu\Helpers\CustomMenu;

/**
 * Таблица для хранения настроек меню
 */
class OfficeFieldSettingsTable extends DataManager
{

    /**
     * Отображение полей ЛК в профиле пользователя - VIEW
     * Доступно для настройки: Админ
     * Функция: отображать/скрывать в публичной части (по умолчанию установлено "отображать")
     * При "отображать": поле доступно в публичной части в профиле пользователя
     * При "скрывать": поле не отображается в списке полей в профиле пользователя
     */
    public const VIEW = [
        'SHOW' => 1,
        'HIDE' => 2
    ];

    /**
     * Редактирование полей ЛК в профиле пользователя (Для автора) - EDIT_EMPLOYEE
     * Доступно для настройки: Админ
     * Функция: разрешить ручное редактирование/запретить ручное редактирование автору (по умолчанию установлено "разрешить ручное редактирование")
     * При "разрешить": поле доступно для редактирования автором в публичной части в профиле пользователя
     * При "запретить": поле не доступно для редактирования в списке полей в профиле пользователя
     */
    public const EDIT_EMPLOYEE = [
        'ALLOW' => 1,
        'DENY' => 2
    ];

    /**
     * Возможность настройки видимости из-под автора - MANAGEMENT_EMPLOYEE
     * Функция: разрешить настройку видимости / запретить настройку видимости поля в публичной части в редактировании профиля пользователя (по умолчанию установлено "запретить настройку видимости")
     * При "разрешить": поле доступно для настройки видимости под категории пользователей: только автор / руководитель / мое подразделение, автором в публичной части в профиле пользователя
     * При "запретить": поле не доступно для настройки видимости для категорий только автор / руководитель / мое подразделение в профиле пользователя
     */
    public const MANAGEMENT_EMPLOYEE = [
        'ALLOW' => 1,
        'DENY' => 2
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'korus_office_field_settings';
    }

    /**
     * SELECT массив для формы редактирования
     *
     * @return string[]
     */
    public static function getFormSelect(): array
    {
        return ['ID', 'FIELD_CODE', 'VIEW', 'EDIT_EMPLOYEE', 'MANAGEMENT_EMPLOYEE'];
    }

    /**
     * @return string[]
     */
    public static function getFullSelect(): array
    {
        return ['ID', 'FIELD_CODE', 'VIEW', 'EDIT_EMPLOYEE', 'MANAGEMENT_EMPLOYEE', 'EMPLOYEES_SETTINGS'];
    }

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            (new Fields\IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),
            (new Fields\StringField('FIELD_CODE'))
                ->configureUnique()
                ->configureRequired(),
            (new Fields\IntegerField('VIEW'))
                ->configureRequired(),
            (new Fields\IntegerField('EDIT_EMPLOYEE'))
                ->configureRequired(),
            (new Fields\IntegerField('MANAGEMENT_EMPLOYEE'))
                ->configureRequired(),
            (new Fields\StringField('EMPLOYEES_SETTINGS'))
                ->configureDefaultValue(''),
        ];
    }

    /**
     * Поля которые должны запрашиваться всегда, вместе с option - settings_field_list
     *
     * @return string[]
     */
    public static function getPrimaryFieldList(): array
    {
        return ['ID'];
    }

    /**
     * Поля настройки которых не редактируем через админку
     *
     * @return array[]
     */
    public static function getPrimaryFieldSettings(): array
    {
        return [
            [
                'FIELD_CODE' => 'FULL_NAME',
                'VIEW' => static::VIEW['SHOW'],
                'EDIT_EMPLOYEE' => static::EDIT_EMPLOYEE['DENY'],
                'MANAGEMENT_EMPLOYEE' => static::MANAGEMENT_EMPLOYEE['DENY']
            ]
        ];
    }

    /**
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function updateEmployeesSettings(int $userId, string $fieldCode, bool $value): bool
    {
        $fieldCode = self::fieldNameCorrection($fieldCode);
        /**
         * @var EntityObject $field
         */
        $field = OfficeFieldSettingsTable::query()
            ->where('FIELD_CODE', $fieldCode)
            ->setSelect(['ID', 'EMPLOYEES_SETTINGS'])
            ->fetchObject();

        if ($field) {
            $settings = unserialize($field->getEmployeesSettings());
            $settings[$userId] = $value;
            $field->setEmployeesSettings(serialize($settings));
            return $field->save()->isSuccess();
        } else {
            return false;
        }
    }

    /**
     * @param string $fieldCode
     * @return string
     */
    private static function fieldNameCorrection(string $fieldCode): string
    {
        $correctionFieldName = [
            'PERSONAL_BIRTHDAY_FORMATTED' => 'PERSONAL_BIRTHDAY'
        ];

        return $correctionFieldName[$fieldCode] ?? $fieldCode;
    }
}