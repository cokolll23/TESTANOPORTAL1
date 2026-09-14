<?php

namespace Korus\Office\Profile;

use Bitrix\Intranet\Component\UserProfile;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use CFile;
use COption;
use Korus\Office\Entity\OfficeFieldSettingsTable;
use KTeam\Main\EO_User;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\FieldTypeMask;
use Bitrix\Main\ORM\Objectify\Values;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UserTable;
use Bitrix\Socialnetwork\ComponentHelper;
use CSite;
use CSocNetUser;
use CSocNetUserPerms;
use CUser;
use Exception;
use Korus\Office\Dto\DateFormatterSettingsDTO;
use Korus\Office\Formatter;
use Psr\Container\NotFoundExceptionInterface;
use Korus\OfficeMap\PlaceManager;
use ReflectionException;

class Employee
{
    private EO_User $employee;
    private CurrentUser $currentUser;
    private int $employeeId;

    public function __construct(EO_User $user)
    {
        $this->employee = $user;
        $this->employeeId = $this->employee->getId();
        $this->currentUser = CurrentUser::get();
    }

    /**
     * @param ConditionTree $filter
     * @return array
     * @throws ArgumentException
     * @throws LoaderException
     * @throws NotFoundExceptionInterface
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ReflectionException
     */
    public static function getUsersShortFromFilter(ConditionTree $filter): array
    {
        $result = [];

        $em = ServiceLocator::getInstance()->get('korus.main.entity.manager');

        $queryBuilder = $em->createQueryBuilder($em->Table(UserTable::class)->createEntity());
        $query = $queryBuilder
            ->setSelect(self::getShortProfileFields())
            ->addSelect('UF_DEPARTMENT')
            ->addSelect('WORK_POSITION')
            ->where($filter)
            ->setOrder([
                'LAST_NAME' => 'ASC',
                'NAME' => 'ASC',
                'SECOND_NAME' => 'ASC',
            ])
            ->getQuery();
        foreach ($query->fetchCollection() as $user) {
            $result[$user->getId()] = self::prepareEmployeeData($user);
        }

        return $result;
    }

    /**
     * Getter в основном для EmployeeService, т.к. пока сервисы из korus.personalarea завязаны на EO_User
     *
     * @return EO_User
     */
    public function getEntityObject(): EO_User
    {
        return $this->employee;
    }

    /**
     * @param string $fieldName
     * @return void
     */
    public function fillField(string $fieldName): void
    {
        if (!$this->employee->has($fieldName)) {
            $this->employee->fill($fieldName);
        }
    }

    /**
     * @param string $fieldName
     * @return mixed
     */
    public function getField(string $fieldName): mixed
    {
        $this->fillField($fieldName);
        return $this->employee->get($fieldName);
    }

    /**
     * @param string $fieldName
     * @param $value
     * @return void
     */
    public function setField(string $fieldName, $value): void
    {
        $this->employee->set($fieldName, $value);
    }

    /**
     * @param string $fieldName
     * @return void
     */
    public function unsetField(string $fieldName): void
    {
        $this->employee->unset($fieldName);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
        $userProfileValues = $this->employee->collectValues();
        unset($userProfileValues['ID']);

        if (isset($userProfileValues['ACTIVE'])) {
            $userProfileValues['ACTIVE'] = $userProfileValues['ACTIVE'] ? 'Y' : 'N';
        }
        if ($userProfileValues['PERSONAL_PHOTO']) {
            $userProfileValues['PERSONAL_PHOTO'] = CFile::MakeFileArray($userProfileValues['PERSONAL_PHOTO']);
        }
        $cUser = new CUser();
        if ($cUser->Update($this->employeeId, $userProfileValues)) {
            return true;
        } else {
            throw new Exception($cUser->LAST_ERROR);
        }
    }

    public function getId(): int
    {
        return $this->employeeId;
    }

    /**
     * Возвращает настройки прав из настроек socialnetwork
     *
     * @return array|bool
     * @throws LoaderException
     */
    public function getSocialNetworkPermissions(): array|bool
    {
        if (Loader::includeModule('socialnetwork')) {
            $currentUserId = $this->currentUser->getId();

            $currentUserPerms = CSocNetUserPerms::initUserPerms(
                $currentUserId,
                $this->employeeId,
                CSocNetUser::isCurrentUserModuleAdmin(SITE_ID, false)
            );

            $result = [
                'view' => (
                    $currentUserPerms['IsCurrentUser']
                    || CSocNetUser::canProfileView(
                        $currentUserId,
                        $this->employeeId,
                        SITE_ID,
                        ComponentHelper::getUrlContext()
                    )
                ),
                'edit' => (
                    $currentUserPerms['IsCurrentUser']
                    || ($currentUserPerms['Operations']['modifyuser'] && $currentUserPerms['Operations']['modifyuser_main'])
                ),
            ];

            if ($this->currentUser->isAdmin() && !$currentUserPerms['IsCurrentUser']) {
                $result['edit'] = $result['edit'] && CSocNetUser::isCurrentUserModuleAdmin(SITE_ID, false);
            }
        } else {
            return false;
        }

        return $result;
    }


    /**
     * @return bool
     * @throws LoaderException
     */
    public function canEdit(): bool
    {
        $perms = $this->getSocialNetworkPermissions();
        if (empty($perms)) {
            return false;
        }

        return $perms['edit'];
    }

    /**
     * Проверка на собственный личный кабинет пользователя
     *
     * @return bool
     */
    public function isOwnProfile(): bool
    {
        return $this->currentUser->getId() == $this->employeeId;
    }

    /**
     * @param int|string|EO_User $employee - Объект пользователя или его идентификатор
     * @return EmployeeProfileData
     * @throws ObjectNotFoundException|NotFoundExceptionInterface
     */
    public static function getProfileDataManager(int|string|EO_User $employee): EmployeeProfileData
    {
        if (!($employee instanceof EO_User)) {
            $employee = self::getEmployee($employee);
        }

        return new EmployeeProfileData(new self($employee));
    }

    /**
     * @param int|string|EO_User $employee - Объект пользователя или его идентификатор
     * @return EmployeePhoto
     * @throws ObjectNotFoundException
     * @throws NotFoundExceptionInterface
     */
    public static function getPhotoManager(int|string|EO_User $employee): EmployeePhoto
    {
        if (!($employee instanceof EO_User)) {
            $employee = self::getShortEmployee($employee);
        }

        return new EmployeePhoto(new self($employee));
    }

    /**
     * @param int|string|EO_User $employee - Объект пользователя или его идентификатор
     * @return EmployeeBusinessBadges
     * @throws ObjectNotFoundException
     * @throws SystemException|NotFoundExceptionInterface
     */
    public static function getBusinessBadgesManager(int|string|EO_User $employee): EmployeeBusinessBadges
    {
        if (!($employee instanceof EO_User)) {
            $employee = self::getShortEmployee($employee);
        }

        return new EmployeeBusinessBadges(new self($employee));
    }

    /**
     * @param int|string|EO_User $employee - Объект пользователя или его идентификатор
     * @return EmployeeCompetencies
     * @throws ObjectNotFoundException|LoaderException|NotFoundExceptionInterface
     */
    public static function getCompetenceManager(int|string|EO_User $employee): EmployeeCompetencies
    {
        if (!($employee instanceof EO_User)) {
            $employee = self::getEmployee($employee);
        }

        return new EmployeeCompetencies(new self($employee));
    }

    /**
     * @param int|string|EO_User $employee - Объект пользователя или его идентификатор
     * @return EmployeeService
     * @throws ObjectNotFoundException|NotFoundExceptionInterface
     */
    public static function getServiceManager(int|string|EO_User $employee): EmployeeService
    {
        if (!($employee instanceof EO_User)) {
            $employee = self::getEmployee($employee);
        }

        return new EmployeeService(new self($employee));
    }

    /**
     * @param int|string|EO_User $employee - Объект пользователя или его идентификатор
     * @return EmployeeEmoji
     * @throws ObjectNotFoundException|NotFoundExceptionInterface
     */
    public static function getEmojiManager(int|string|EO_User $employee): EmployeeEmoji
    {
        if (!($employee instanceof EO_User)) {
            $employee = self::getEmployee($employee);
        }

        return new EmployeeEmoji(new self($employee));
    }

    /**
     * @param int|string|EO_User $employee - Объект пользователя или его идентификатор
     * @return EmployeeInterests
     * @throws ObjectNotFoundException|LoaderException|NotFoundExceptionInterface
     */
    public static function getInterestManager(int|string|EO_User $employee): EmployeeInterests
    {
        if (!($employee instanceof EO_User)) {
            $employee = self::getEmployee($employee);
        }

        return new EmployeeInterests(new self($employee));
    }

    /**
     * @param int|string|EO_User $employee - Объект пользователя или его идентификатор
     * @return EmployeeGratitude
     * @throws ObjectNotFoundException|NotFoundExceptionInterface
     */
    public static function getGratitudeManager(int|string|EO_User $employee): EmployeeGratitude
    {
        if (!($employee instanceof EO_User)) {
            $employee = self::getEmployee($employee);
        }

        return new EmployeeGratitude(new self($employee));
    }

    /**
     * @return UserProfile\ProfilePost
     * @throws LoaderException
     */
    public function getAboutManager(): UserProfile\ProfilePost
    {
        static $manager;
        if (empty($manager)) {
            $manager = new UserProfile\ProfilePost([
                'profileId' => $this->employeeId,
                'permissions' => $this->getSocialNetworkPermissions(),
            ]);
        }

        return $manager;
    }

    /**
     * @param int|string $employeeId
     * @return EO_User
     * @throws ObjectNotFoundException|NotFoundExceptionInterface
     */
    public static function getEmployee(int|string $employeeId): EO_User
    {
        return self::fetchEmployeeData($employeeId, self::getFullProfileFields());
    }

    /**
     * @param int|string $employeeId
     * @return EO_User
     * @throws ObjectNotFoundException
     * @throws NotFoundExceptionInterface
     */
    public static function getShortEmployee(int|string $employeeId): EO_User
    {
        return self::fetchEmployeeData($employeeId, self::getShortProfileFields());
    }

    /**
     * @param array $ids
     * @return array
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException|NotFoundExceptionInterface
     */
    public static function getUsersShort(array $ids): array
    {
        $result = [];

        if (empty($ids)) {
            return $result;
        }

        $em = ServiceLocator::getInstance()->get('korus.main.entity.manager');

        $queryBuilder = $em->createQueryBuilder($em->Table(UserTable::class)->createEntity());
        $collection = $queryBuilder
            ->setSelect(self::getShortProfileFields())
            ->whereIn('ID', array_unique($ids))
            ->getQuery()
            ->fetchCollection();

        foreach ($collection as $user) {
            $result[$user->getId()] = self::prepareEmployeeData($user);
        }

        return $result;
    }

    /**
     * @param array|string|int $employeeId
     * @param array $select
     * @return EO_User
     * @throws ObjectNotFoundException|NotFoundExceptionInterface
     */
    protected static function fetchEmployeeData(array|string|int $employeeId, array $select): EO_User
    {
        try {
            $em = ServiceLocator::getInstance()->get('korus.main.entity.manager');

            $userEntity = $em->Table(UserTable::class)->createEntity();
            $userEntity->addField(new StringField('AUTO_TIME_ZONE'));

            $queryBuilder = $em->createQueryBuilder($userEntity);
            $employee = $queryBuilder
                ->setSelect($select)
                ->where('ID', $employeeId)
                ->setLimit(1)
                ->getQuery()
                ->fetchObject();

            if (!$employee) {
                throw new ObjectNotFoundException('Сотрудник не найден.');
            }
        } catch (ArgumentException|ObjectPropertyException|SystemException $exception) {
            throw new ObjectNotFoundException('Ошибка поиска сотрудника. ' . $exception->getMessage());
        }

        return $employee;
    }

    /**
     * @param bool $formattingFullName
     * @return array
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws NotFoundExceptionInterface
     */
    public function getEmployeeData(bool $formattingFullName = false): array
    {
        return self::prepareEmployeeData($this->employee, $formattingFullName);
    }

    /**
     * @param EO_User $employee
     * @param bool $formattingFullName
     * @return array
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException|NotFoundExceptionInterface
     */
    protected static function prepareEmployeeData(EO_User $employee, bool $formattingFullName = false): array
    {
        $userValuesCollection = $employee->collectValues(Values::ACTUAL, FieldTypeMask::ALL & ~FieldTypeMask::RELATION);

        $employeeId = $employee->getId();
        $currentUser = CurrentUser::get();

        $userValuesCollection['ACTIVE'] = $employee->fillActive();

        $fieldsSettings = EmployeeProfileData::getFieldsSettings();

        $userResultCollection = [
            'ID' => $employee->getId(),
            'UF_DEPARTMENT' => $employee->fillUfDepartment(),
            'WORK_POSITION' => $employee->getWorkPosition(),
        ];

        /**
         * Для показа поля необходимо чтоб оно было не скрыто админом и пользователем (если доступна пользователю доступна такая настройка)
         */
        foreach ($userValuesCollection as $fieldCode => $field) {
            $setting = $fieldsSettings[$fieldCode];
            if (
                $setting['VIEW'] &&
                (!$setting['MANAGEMENT_EMPLOYEE'] || !$setting['EMPLOYEES_SETTINGS'][$employeeId] ||
                    $employeeId === (int)$currentUser->getId())
            ) {
                $userResultCollection[$fieldCode] = $field;
            }
        }

        if ($fieldsSettings['FULL_NAME']['VIEW']) {
            $userResultCollection['FULL_NAME'] = '';
        }

        if (isset($userResultCollection['FULL_NAME']) || CurrentUser::get()->isAdmin()) {
            if ($formattingFullName) {
                $userResultCollection['FULL_NAME'] = static::formatName(
                    $userResultCollection,
                    '#LAST_NAME# #NAME# #SECOND_NAME#'
                );
            } else {
                $userResultCollection['FULL_NAME'] = static::formatName($userResultCollection);
            }
        }

        if ($userResultCollection['PERSONAL_PHOTO']) {
            $userResultCollection['PHOTO'] = self::getPhotoManager($employee)->get();
        } else {
            $userResultCollection['PHOTO'] = self::getPhotoManager($employee)->getDefaultSource();
        }

        if (isset($userResultCollection['PERSONAL_COUNTRY'])) {
            $userResultCollection['PERSONAL_COUNTRY'] = intval($userResultCollection['PERSONAL_COUNTRY']);
        }

        if (isset($userResultCollection['WORK_COUNTRY'])) {
            $userResultCollection['WORK_COUNTRY'] = intval($userResultCollection['WORK_COUNTRY']);
        }

        if ($employee->hasLastActivityDate()) {
            $userResultCollection['ONLINE_STATUS'] = self::getOnlineStatus(
                $employee->getId(),
                $employee->getLastActivityDate()
            );
        }

        if (isset($userResultCollection['UF_EMPLOYMENT_DATE'])) {
            $userResultCollection['COMPANY_EXPERIENCE'] = self::calcEmployeeExperience(
                $userResultCollection['UF_EMPLOYMENT_DATE']
            );
        }

        if ($userResultCollection['UF_ASSISTANT']) {
            $userResultCollection['UF_ASSISTANT'] = array_values(
                static::getUsersShort($userResultCollection['UF_ASSISTANT'])
            );
        }

        if ($userResultCollection['UF_CONTACT_PERSONS']) {
            $userResultCollection['UF_CONTACT_PERSONS'] = array_values(
                static::getUsersShort($userResultCollection['UF_CONTACT_PERSONS'])
            );
        }

        if ($userResultCollection['UF_MESSENGERS']) {
            $personalMessengers = [];
            foreach ($userResultCollection['UF_MESSENGERS'] as $personalMessenger) {
                if ($messenger = unserialize($personalMessenger)) {
                    $personalMessengers[] = $messenger;
                }
            }

            $userResultCollection['UF_MESSENGERS'] = $personalMessengers;
        }

        if ($userResultCollection['UF_EXPERTISE']) {
            $personalExpertiseSkills = [];
            $expertiseSkills = EmployeeProfileData::getExpertiseSkills();
            foreach ($userResultCollection['UF_EXPERTISE'] as $personalExpertiseSkillID) {
                $personalExpertiseSkills[] = $expertiseSkills[$personalExpertiseSkillID];
            }
            $userResultCollection['UF_EXPERTISE'] = $personalExpertiseSkills;
        }

        if (isset($userResultCollection['PERSONAL_BIRTHDAY'])) {
            $showYearValue = ((int)CurrentUser::get()->getId() === $employee->getId()) ? 'Y' :
                Option::get('intranet', 'user_profile_show_year', 'Y');

            $dateSettingsDTO = new DateFormatterSettingsDTO(
                showYearValue: $showYearValue,
                longDateFormat: Context::getCurrent()->getCulture()->getLongDateFormat(),
                dayMonthFormat: Context::getCurrent()->getCulture()->getDayMonthFormat(),
            );

            $dateFormatter = new Formatter\Date($dateSettingsDTO);

            $userResultCollection['PERSONAL_BIRTHDAY_FORMATTED'] = $dateFormatter->formatBirthdayByGender(
                $userResultCollection['PERSONAL_BIRTHDAY'],
                (string)$userResultCollection['PERSONAL_GENDER']
            );
        }

        if (Loader::includeModule('korus.officemap')) {
            $userResultCollection['UF_WORKPLACE'] = PlaceManager::getInstance()->getWorkplaceUser($employee->getId());
        }

        return $userResultCollection;
    }

    /**
     * Возвращает онлайн статус пользователя
     *
     * @param int|string $employeeId
     * @param DateTime|null $lastActivity
     * @return array
     */
    public static function getOnlineStatus(int|string $employeeId, ?DateTime $lastActivity = null): array
    {
        $res = CUser::GetOnlineStatus($employeeId, $lastActivity);

        $res['NOW'] = DateTime::createFromTimestamp($res['NOW']);

        if ($res['LAST_SEEN']) {
            $res['LAST_SEEN'] = DateTime::createFromTimestamp($res['LAST_SEEN']);
        } elseif (empty($res['LAST_SEEN_TEXT'])) {
            $res['LAST_SEEN_TEXT'] = CUser::FormatLastActivityDate(0);
        }

        return $res;
    }

    /**
     * Вычисляет стаж работы пользователя
     *
     * @param Date $employmentDate
     * @return string
     */
    protected static function calcEmployeeExperience(Date $employmentDate): string
    {
        $now = new Date();

        $years = FormatDate('Ydiff', $employmentDate, $now);
        $iYears = intval($years);

        if ($iYears) {
            $result = $years;
            $months = FormatDate('mdiff', (clone $employmentDate)->add($iYears . 'Y'), $now);
            if (intval($months) > 0) {
                $result .= ' и ' . $months;
            }

            return $result;
        }

        $months = FormatDate('mdiff', $employmentDate, $now);
        $days = FormatDate('ddiff', $employmentDate, $now);

        return intval($months) > 0 ? $months : $days;
    }

    /**
     * @param array $user
     * @param string $format
     * @return string
     */
    public static function formatName(array $user, string $format = ''): string
    {
        if (empty($format)) {
            $format = CSite::GetNameFormat();
        }

        return CUser::FormatName($format, $user);
    }

    /**
     * @return array
     */
    public static function getFullProfileFields(): array
    {
        return self::getActiveFieldsCodes();
    }

    /**
     * @return string[]
     */
    public static function getShortProfileFields(): array
    {
        $arRequiredFields = [
            'LOGIN',
            'EMAIL',
            'NAME',
            'SECOND_NAME',
            'LAST_NAME',
            'PERSONAL_PHOTO',
            'PERSONAL_GENDER',
        ];
        $shortProfileFields = [];
        $activeFields = self::getActiveFieldsCodes();

        foreach ($arRequiredFields as $requiredField) {
            if (in_array($requiredField, $activeFields)) {
                $shortProfileFields[] = $requiredField;
            }
        }
        return $shortProfileFields;
    }

    /**
     * @return array
     */
    public static function getActiveFieldsCodes(): array
    {
        $fieldListSettings = COption::GetOptionString('korus.office', 'settings_field_list');
        $fieldListSettings = unserialize($fieldListSettings);

        $activeFields = OfficeFieldSettingsTable::getPrimaryFieldList();

        foreach ($fieldListSettings as $fieldList) {
            foreach ($fieldList as $field) {
                if ($field['active']) {
                    $activeFields[] = $field['code'];
                }
            }
        }

        return $activeFields;
    }
}
