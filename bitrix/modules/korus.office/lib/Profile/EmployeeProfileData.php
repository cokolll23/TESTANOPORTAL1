<?php

namespace Korus\Office\Profile;

use Bitrix\Intranet\Component\UserProfile;
use Bitrix\Intranet\CurrentUser;
use Bitrix\Main\AccessDeniedException;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Engine\Response\HtmlContent;
use Bitrix\Main\InvalidOperationException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use CComponentEngine;
use CExtranet;
use CIntranetUtils;
use COption;
use CUser;
use CUserOptions;
use CUserTypeManager;
use Korus\Office\Entity\OfficeFieldSettingsTable;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

Loc::loadMessages(__DIR__ . '/../../admin/korus_office_lk_field_admin.php');

class EmployeeProfileData
{
    /**
     * @var Employee
     */
    private Employee $employee;

    /**
     * @var mixed
     */
    private mixed $em;

    /**
     * @param Employee $employee
     * @throws ObjectNotFoundException
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
        $this->em = ServiceLocator::getInstance()->get('korus.main.entity.manager');
    }

    /**
     * @return array|array[]
     * @throws ArgumentException
     * @throws LoaderException
     * @throws NotFoundExceptionInterface
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ReflectionException
     */
    public function getDepartmentStructure(): array
    {
        Loader::includeModule('intranet');
        Loader::includeModule('korus.main');

        $structure = [
            'STRUCTURE' => [],
            'USERS' => [],
        ];

        $employeeDepartments = (array)$this->employee->getField('UF_DEPARTMENT');
        if (empty($employeeDepartments)) {
            return $structure;
        }

        $users = [];

        $queryBuilder = $this->em->createQueryBuilder(
            $this->em->Iblock('departments', 'structure')->createIblockSectionEntity()
        );
        $list = $queryBuilder
            ->setSelect(['PARENTS_TREE'])
            ->addSelect('CHILDREN_SECTIONS.DEPTH_LEVEL', '_CHILDREN_DEPTH_LEVEL')
            ->addSelect('CHILDREN_SECTIONS.ID', '_CHILDREN_ID')
            ->addSelect('CHILDREN_SECTIONS.IBLOCK_SECTION_ID', '_CHILDREN_PARENTS_ID')
            ->addSelect('CHILDREN_SECTIONS.UF_HEAD', '_CHILDREN_HEAD')
            ->whereIn('ID', $employeeDepartments)
            ->setGroup('ID')
            ->getQuery()
            ->fetchAll();

        $departmentTeam = [];
        $departments = [];
        foreach ($list as $row) {
            $teamId = (int)end($row['PARENTS_TREE']);
            if (!isset($departments[$teamId])) {
                $departments[$teamId] = [
                    'PARENTS_TREE' => $row['PARENTS_TREE'],
                    'CHILDREN' => [
                        $teamId,
                    ],
                ];
                $departmentTeam[] = $teamId;
            }
            if (empty($row['_CHILDREN_ID'])) {
                continue;
            }
            if (((int)$row['_CHILDREN_HEAD'] === $this->employee->getId() || empty($row['_CHILDREN_HEAD']))
                && in_array((int)$row['_CHILDREN_PARENTS_ID'], $departments[$teamId]['CHILDREN'], true)) {
                $departments[$teamId]['CHILDREN'][] = (int)$row['_CHILDREN_ID'];
                $departmentTeam[] = (int)$row['_CHILDREN_ID'];
            }
        }
        foreach ($departments as $row) {
            $branch = [
                'HEAD' => null,
                'TREE' => [],
                'TEAM_ID' => $row['CHILDREN'],
                'TEAM' => [],
            ];
            foreach ($row['PARENTS_TREE'] as $departmentId) {
                $data = static::getDepartmentData((int)$departmentId);
                if ($data['UF_HEAD'] && (int)$data['UF_HEAD'] !== $this->employee->getId()) {
                    $branch['HEAD'] = $data['UF_HEAD'];
                }

                $branch['TREE'][] = [
                    'ID' => (int)$data['ID'],
                    'NAME' => $data['NAME'],
                    'PARENT_ID' => $data['IBLOCK_SECTION_ID'] ? (int)$data['IBLOCK_SECTION_ID'] : null,
                    'DEPTH_LEVEL' => (int)$data['DEPTH_LEVEL'],
                    'HEAD' => $data['UF_HEAD'] ? (int)$data['UF_HEAD'] : null,
                    'URL' => CComponentEngine::MakePathFromTemplate($data['SECTION_PAGE_URL'], [
                        'ID' => $data['ID'],
                    ]),
                ];
            }
            $structure['STRUCTURE'][] = $branch;

            if ($branch['HEAD']) {
                $users[] = $branch['HEAD'];
            }
        }

        $filter = (new ConditionTree())->where('ACTIVE', 'Y');
        $filterDepOrId = (new ConditionTree())->logic('OR');
        if (!empty($departmentTeam)) {
            $filterDepOrId->whereIn('UF_DEPARTMENT', $departmentTeam);
        }
        if (!empty($users)) {
            $filterDepOrId->whereIn('ID', $users);
        }
        $filter->addCondition($filterDepOrId);
        if (!empty($departments) || !empty($users)) {
            $structure['USERS'] = Employee::getUsersShortFromFilter($filter);
            foreach ($structure['STRUCTURE'] as &$item) {
                foreach ($structure['USERS'] as $user) {
                    if ($this->employee->getId() !== (int)$user['ID']
                        && !empty(array_intersect($item['TEAM_ID'], $user['UF_DEPARTMENT']))) {
                        $item['TEAM'][] = $user['ID'];
                    }
                }
            }
            unset($item);
        }
        return $structure;
    }

    /**
     * @param int $departmentId
     * @return array|null
     * @throws LoaderException
     */
    private static function getDepartmentData(int $departmentId): ?array
    {
        static $departmentsData;
        if (empty($departmentsData)) {
            Loader::includeModule('intranet');
            $departmentsData = CIntranetUtils::getStructure()['DATA'];
        }

        return $departmentsData[$departmentId];
    }


    /**
     * @return array|array[]
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws NotFoundExceptionInterface
     */
    public function getEditFormInfo(): array
    {
        $fullProfileFields = Employee::getFullProfileFields();
        $fieldsSettings = self::getFieldsSettings();

        $currentUser = CurrentUser::get();

        $availableFields = [];
        foreach ($fullProfileFields as $fieldCode) {
            /**
             * Для показа поля необходимо чтоб оно было не скрыто админом и пользователем (если доступна пользователю доступна такая настройка)
             */
            $setting = $fieldsSettings[$fieldCode];
            if ($setting['VIEW'] && (!$setting['MANAGEMENT_EMPLOYEE'] || !$setting['EMPLOYEES_SETTINGS'][$this->employee->getId(
                    )] || $this->employee->isOwnProfile())) {
                $availableFields[] = $fieldCode;
            }
        }


        $form = new UserProfile\Form($this->employee->getId());
        $editForm = $form->getFieldInfo($this->employee->getEmployeeData(), $availableFields);

        $fieldWithSettings = array_column($editForm, 'name');

        $this->getAdditionalFieldSettings($fieldWithSettings, $availableFields, $editForm);

        $fieldSort = self::getFieldSort($this->employee->getId());

        foreach ($editForm as &$formField) {
            $setting = $fieldsSettings[$formField['name']];

            if (($this->employee->isOwnProfile()) && $setting['MANAGEMENT_EMPLOYEE']) {
                $formField['management'] = true;
                $formField['managementStatus'] = $setting['EMPLOYEES_SETTINGS'][$this->employee->getId()] ?? false;
            } else {
                $formField['management'] = false;
                $formField['managementStatus'] = false;
            }

            $formField['editable'] = (in_array(
                        $formField['name'],
                        $availableFields
                    ) && $setting['EDIT_EMPLOYEE']) || $currentUser->IsAdmin();

            $formField['sort'] = [
                'type' => $fieldSort['VIEW_TYPE'][$formField['name']] ?? 'M',
                'value' => $fieldSort['VIEW_SORT'][$formField['name']] ?? 0,
            ];
        }

        return $editForm;
    }

    /**
     * Заполняем дополнительные настройки для полей ЛК
     *
     * @param array $fieldWithSettings
     * @param array $availableFields
     * @param array $result
     * @return void
     */
    private function getAdditionalFieldSettings(array $fieldWithSettings, array $availableFields, array &$result): void
    {
        $fieldWithoutSettings = array_diff($availableFields, $fieldWithSettings);

        unset($fieldWithoutSettings[array_search('ID', $fieldWithoutSettings)]);
        unset($fieldWithoutSettings[array_search('PERSONAL_PHOTO', $fieldWithoutSettings)]);

        if (in_array('WORK_COUNTRY', $availableFields)) {
            foreach ($result as $key => $field) {
                if ($field['name'] == 'WORK_COUNTRY') {
                    $result[$key]['type'] = 'list';
                    $result[$key]['data'] = [
                        'items' => static::getCountryList(),
                    ];
                    break;
                }
            }
        }

        /**
         * \Bitrix\Intranet\Component\UserProfile\Form::getFieldInfo не возвращает поле фотографии, но для него тоже доступны настройки
         */
        if (in_array('PERSONAL_PHOTO', $availableFields)) {
            $result[] = [
                'title' => Loc::GetMessage('KORUS_OFFICE_FIELD_PERSONAL_PHOTO'),
                'name' => 'PERSONAL_PHOTO',
            ];
        }

        foreach ($fieldWithoutSettings as $fieldCode) {
            $result[] = [
                'title' => Loc::GetMessage('KORUS_OFFICE_FIELD_' . $fieldCode),
                'type' => 'text',
                'name' => $fieldCode,
            ];
        }

        if (!in_array('TIME_ZONE', $availableFields)) {
            $editFormOlder = $result;
            $result = [];
            foreach ($editFormOlder as $field) {
                if ($field['name'] === 'TIME_ZONE') {
                    continue;
                }
                $result[] = $field;
            }
        }

        /**
         * Обработка идентификаторов элементов списков для корректной работы селекторов
         */
        foreach ($result as $key => $field) {
            if ($field['name'] === 'PERSONAL_GENDER') {
                continue;
            }

            if (in_array($field['type'], ['list', 'multilist']) && !empty($field['data']['items'])) {
                $result[$key]['data']['items'] = array_map(function ($item) {
                    return [
                        "NAME" => $item['NAME'],
                        "VALUE" => (int)$item['VALUE'],
                    ];
                }, $field['data']['items']);
            }
        }
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException|NotFoundExceptionInterface
     */
    public static function getFieldsSettings(): array
    {
        $em = ServiceLocator::getInstance()->get('korus.main.entity.manager');

        $queryBuilder = $em->createQueryBuilder($em->Table(OfficeFieldSettingsTable::class)->createEntity());
        $fieldSettingsQuery = $queryBuilder
            ->setSelect(OfficeFieldSettingsTable::getFullSelect())
            ->getQuery()
            ->fetchAll();

        $fieldSettings = [];

        foreach (
            array_merge(
                $fieldSettingsQuery,
                OfficeFieldSettingsTable::getPrimaryFieldSettings()
            ) as $fieldSetting
        ) {
            $fieldSettings[$fieldSetting['FIELD_CODE']] = [
                'VIEW' => (int)$fieldSetting['VIEW'] === OfficeFieldSettingsTable::VIEW['SHOW'],
                'EDIT_EMPLOYEE' => (int)$fieldSetting['EDIT_EMPLOYEE'] === OfficeFieldSettingsTable::EDIT_EMPLOYEE['ALLOW'],
                'MANAGEMENT_EMPLOYEE' => (int)$fieldSetting['MANAGEMENT_EMPLOYEE'] === OfficeFieldSettingsTable::MANAGEMENT_EMPLOYEE['ALLOW'],
                'EMPLOYEES_SETTINGS' => unserialize($fieldSetting['EMPLOYEES_SETTINGS']),
            ];
        }

        return $fieldSettings;
    }

    public static function getDefaultSettings(): array
    {
        $defaultSettingsFile = __DIR__ . '/../../configs/default_fields_settings.txt';
        if (!file_exists($defaultSettingsFile)) {
            return [];
        }

        $settings = unserialize(file_get_contents($defaultSettingsFile));
        if (empty($settings)) {
            return [];
        }

        return $settings;
    }

    /**
     * @param int $userId
     * @return array
     */
    public static function getFieldSort(int $userId): array
    {
        $userSortSetting = unserialize(CUserOptions::GetOption('korus.office', 'fields_sort', false, $userId));

        if (!$userSortSetting) {
            $fieldSettingsSort = unserialize(CUserOptions::GetOption('korus.office', 'fields_sort', true));
        } else {
            $fieldSettingsSort = $userSortSetting;
        }

        if (!$fieldSettingsSort) {
            $fieldSettingsSort = static::getDefaultSettings();
        }

        if (empty($fieldSettingsSort)) {
            return [];
        }

        $fieldSettingsSort['VIEW_SORT'] = empty($fieldSettingsSort['VIEW_SORT'])
            ? []
            : array_map(
                fn($value): int => (int)$value,
                $fieldSettingsSort['VIEW_SORT']
            );

        return $fieldSettingsSort;
    }

    /**
     * @param string $fieldCode
     * @param bool $value
     * @return bool|null
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function updateFieldSettings(string $fieldCode, bool $value): ?bool
    {
        if ($this->employee->isOwnProfile()) {
            return OfficeFieldSettingsTable::updateEmployeesSettings(
                $this->employee->getId(),
                $this->fieldNameCorrection($fieldCode),
                $value
            );
        } else {
            return false;
        }
    }

    /**
     * @param string $fieldCode
     * @return string
     */
    private function fieldNameCorrection(string $fieldCode): string
    {
        $correctionFieldName = [
            'PERSONAL_BIRTHDAY_FORMATTED' => 'PERSONAL_BIRTHDAY',
        ];

        return $correctionFieldName[$fieldCode] ?? $fieldCode;
    }

    /**
     * @return array[]
     */
    private static function getCountryList(): array
    {
        $personalCountryItems = [
            [
                "NAME" => Loc::getMessage("INTRANET_USER_PROFILE_FIELD_EMPTY"),
                "VALUE" => "",
            ],
        ];
        $countryList = GetCountryArray();
        foreach ($countryList["reference_id"] as $key => $id) {
            $personalCountryItems[] = [
                "NAME" => $countryList["reference"][$key],
                "VALUE" => $id,
            ];
        }

        return $personalCountryItems;
    }

    /**
     * Возвращает запись для блока "Обо мне"
     *
     * @return array|HtmlContent
     * @throws LoaderException
     */
    public function getPersonalAbout(): HtmlContent|array
    {
        return $this->getProfilePost()->getProfileBlogPostAction(null);
    }

    /**
     * @return UserProfile\ProfilePost
     * @throws LoaderException
     */
    protected function getProfilePost(): UserProfile\ProfilePost
    {
        return new UserProfile\ProfilePost([
            'profileId' => $this->employee->getId(),
            'permissions' => $this->employee->getSocialNetworkPermissions(),
        ]);
    }

    /**
     * @return array|null
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws NotFoundExceptionInterface
     */
    public function getPersonalData(): ?array
    {
        $personalData = $this->employee->getEmployeeData(true);

        $extranetGroupId = (Loader::includeModule('extranet') ? (int)CExtranet::getExtranetUserGroupId() : 0);

        $personalData["IS_EXTRANET_USER"] = false;
        $userGroups = $this->getGroups();

        if (in_array(1, $userGroups)) {
            $personalData["STATUS"] = "admin";
        } else {
            $personalData["STATUS"] = "employee";

            if (
                isset($personalData['UF_DEPARTMENT']) &&
                (!is_array($personalData['UF_DEPARTMENT'])
                    || empty($personalData['UF_DEPARTMENT'][0]))
            ) {
                if (
                    $extranetGroupId
                    && in_array($extranetGroupId, $userGroups)
                ) {
                    $personalData["STATUS"] = "extranet";
                    $personalData["IS_EXTRANET_USER"] = true;
                } else {
                    $personalData["STATUS"] = "visitor";
                }
            }
        }

        if (Loader::includeModule("bitrix24") && Integrator::isIntegrator($this->employee->getId())) {
            $personalData["STATUS"] = "integrator";
        }

        if ($personalData["ACTIVE"] === false) {
            $personalData["STATUS"] = "fired";
        }

        if (
            $personalData["ACTIVE"] === true
            && !empty($personalData["CONFIRM_CODE"])
        ) {
            $personalData["STATUS"] = "invited";
        }

        if (in_array($personalData["EXTERNAL_AUTH_ID"], ['email'])) {
            $personalData["STATUS"] = $personalData["EXTERNAL_AUTH_ID"];
        } elseif (in_array($personalData["EXTERNAL_AUTH_ID"], ['shop', 'sale', 'saleanonymous'])) {
            $personalData["STATUS"] = 'shop';
        }

        return $personalData;
    }

    /**
     * Возвращает настройки прав для текущего пользователя на показ личного кабинета запрашиваемого пользователя
     *
     * @return array
     * @throws LoaderException
     */
    public function getPermissionsForUserProfile(): array
    {
        $permissions = [
            'IS_OWN_PROFILE' => $this->employee->isOwnProfile(),
            'CAN_EDIT' => $this->employee->canEdit(),
            'SHOW_SONET_ADMIN' => false,
            'DISABLE_EMOJI_STATUS' => Option::get('korus.office', 'disable_emoji_status') === 'Y',
            'SORT_DISABLE' => Option::get('korus.office', 'disable_field_sort_edit', 'N') === 'Y',
        ];
        $permissions['EDIT_DEPUTY'] = $permissions['CAN_EDIT'];
        if (
            $this->employee->isOwnProfile() &&
            Loader::includeModule('socialnetwork') &&
            CurrentUser::get()->isAdmin()
        ) {
            $permissions['SHOW_SONET_ADMIN'] = true;
            $permissions['IS_SESSION_ADMIN'] = isset($_SESSION['SONET_ADMIN']);
        }

        return $permissions;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return (new CUser())->getUserGroup($this->employee->getId());
    }

    /**
     * @param array $data
     * @return void
     * @throws AccessDeniedException
     * @throws InvalidOperationException
     * @throws LoaderException
     * @throws SystemException|NotFoundExceptionInterface
     */
    public function updatePersonalData(array $data): void
    {
        if (!$this->employee->canEdit()) {
            throw new AccessDeniedException('Доступ запрещен.');
        }


        if (isset($data["UF_DEPARTMENT"])) {
            if (empty($data["UF_DEPARTMENT"][0]) || !CurrentUser::get()->isAdmin()) {
                unset($data["UF_DEPARTMENT"]);
            }
        }

        if (isset($data["UF_ASSISTANT"])) {
            $data['UF_ASSISTANT'] = explode(',', $data['UF_ASSISTANT']);
        }

        if (isset($data['UF_CONTACT_PERSONS'])) {
            $data['UF_CONTACT_PERSONS'] = explode(',', $data['UF_CONTACT_PERSONS']);
        }

        if (isset($data['UF_EXPERTISE'])) {
            $personalExpertiseSkills = explode(',', $data['UF_EXPERTISE']);
            unset($data['UF_EXPERTISE']);

            $expertiseSkills = self::getExpertiseSkills();
            foreach ($personalExpertiseSkills as $personalExpertiseSkill) {
                $expertiseSkillId = array_search(trim($personalExpertiseSkill), $expertiseSkills);
                if (!$expertiseSkillId) {
                    $expertiseSkillId = self::addExpertiseSkills($personalExpertiseSkill);
                }
                if ($expertiseSkillId) {
                    $data['UF_EXPERTISE'][] = $expertiseSkillId;
                }
            }
        }

        $fields = Employee::getActiveFieldsCodes();

        $newFields = [];
        foreach ($fields as $key) {
            if (isset($data[$key])) {
                if (in_array($key, ['NAME', 'LAST_NAME', 'SECOND_NAME'])) {
                    $data[$key] = trim($data[$key]);
                }

                $newFields[$key] = $data[$key];
            }
        }

        $form = new UserProfile\Form($this->employee->getId());
        $ufReserved = $form->getReservedUfFields();
        foreach ($data as $fieldName => $fieldValue) {
            if (in_array($fieldName, $ufReserved)) {
                unset($data[$fieldName]);
            }
        }

        $cUser = new CUser();
        $userFieldManager = new CUserTypeManager();

        $userFieldManager->EditFormAddFields('USER', $newFields, ['FORM' => $data]);
        if (!$cUser->Update($this->employee->getId(), $newFields)) {
            throw new InvalidOperationException($cUser->LAST_ERROR);
        }

        if (defined('BX_COMP_MANAGED_CACHE')) {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->ClearByTag('USER_CARD_' . (int)($this->employee->getId() / TAGGED_user_card_size));
        }
    }

    /**
     * @param array $sortSettings
     * @return void
     */
    public function updateSortSettings(array $sortSettings): void
    {
        $currentUser = CurrentUser::get();
        $employeeId = $this->employee->getId();

        if ((int)$currentUser->getId() !== $employeeId) {
            return;
        }

        if ($sortSettings['setFromDefault'] === 'Y') {
            CUserOptions::DeleteOption('korus.office', 'fields_sort', false, $currentUser->getId());
            return;
        }

        $sortConfig = [
            'VIEW_TYPE' => [
                'NAME' => 'M',
                'LAST_NAME' => 'M',
                'SECOND_NAME' => 'M',
                'PERSONAL_PHOTO' => 'M',
            ],
            'VIEW_SORT' => [
                'NAME' => 0,
                'LAST_NAME' => 0,
                'SECOND_NAME' => 0,
                'PERSONAL_PHOTO' => 0,
            ],
        ];

        foreach ($sortSettings['main'] as $field => $fieldSort) {
            $sortConfig['VIEW_TYPE'][$field] = 'M';
            $sortConfig['VIEW_SORT'][$field] = $fieldSort;
        }

        foreach ($sortSettings['additional'] as $field => $fieldSort) {
            $sortConfig['VIEW_TYPE'][$field] = 'A';
            $sortConfig['VIEW_SORT'][$field] = $fieldSort;
        }

        $setDefault = false;

        if ($currentUser->isAdmin()) {
            $setDefault = $sortSettings['updateDefault'] === 'Y';
            COption::SetOptionString('korus.office', 'disable_field_sort_edit', $sortSettings['disableSort']);
        }

        if ($setDefault) {
            CUserOptions::DeleteOptionsByName('korus.office', 'fields_sort');
        }

        if (
            COption::GetOptionString('korus.office', 'disable_field_sort_edit', 'N') === 'N' ||
            $currentUser->isAdmin()
        ) {
            CUserOptions::SetOption(
                'korus.office',
                'fields_sort',
                serialize($sortConfig),
                $setDefault,
                $currentUser->getId()
            );
        }
    }

    /**
     * Навыки для экспертизы
     *
     * @return array
     * @throws NotFoundExceptionInterface|ObjectNotFoundException
     */
    public static function getExpertiseSkills(): array
    {
        $expertiseSkills = [];

        $em = ServiceLocator::getInstance()->get('korus.main.entity.manager');

        $queryBuilder = $em->createQueryBuilder($em->Highload('Expertise')->getHighloadEntity());
        $skills = $queryBuilder
            ->setSelect(['*'])
            ->getQuery()
            ->fetchAll();

        foreach ($skills as $skill) {
            $expertiseSkills[$skill['ID']] = $skill['UF_SKILL'];
        }

        return $expertiseSkills;
    }

    /**
     * Добавляет новый навык для экспертизы и возвращает его ID
     *
     * @param string $skill
     * @return int
     * @throws SystemException|NotFoundExceptionInterface
     */
    protected static function addExpertiseSkills(string $skill): int
    {
        $em = ServiceLocator::getInstance()->get('korus.main.entity.manager');

        return $em->Highload('Expertise')->getHighloadEntity()->getDataClass()::add(
            [
                'UF_SKILL' => $skill,
                'UF_CREATED_AT' => DateTime::createFromTimestamp(time()),
                'UF_SKILL_CATEGORY' => 'soft',
            ]
        );
    }
}
