<?php

namespace Korus\Office\Controller;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\AutoWire\Parameter;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Engine\Response\HtmlContent;
use Bitrix\Main\Error;
use Bitrix\Main\InvalidOperationException;
use Bitrix\Main\Loader;
use Bitrix\Main\Result;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Web\Json;
use Bitrix\Socialnetwork\ComponentHelper;
use Exception;
use Korus\Office\Profile\{EmployeeInterests, Employee as EmployeeProfile};
use Korus\Main\Controller\BaseController;
use ReflectionParameter;
use Throwable;


class Employee extends BaseController
{
    /**
     * @return array
     */
    public function configureActions(): array
    {
        $result = parent::configureActions();

        $result['initialize']['+prefilters'] = [
            new ActionFilter\HttpMethod(
                [ActionFilter\HttpMethod::METHOD_GET]
            )
        ];

        $result['addCompetence']['+prefilters'] = [
            new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST])
        ];

        $result['addInterest']['+prefilters'] = [
            new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST])
        ];

        return $result;
    }

    /**
     * @return Parameter[]
     */
    public function getAutoWiredParameters(): array
    {
        return array_merge(
            parent::getAutoWiredParameters(),
            [
                new Parameter(
                    PageNavigation::class,
                    static function () {
                        $pageNavigation = new PageNavigation('nav');
                        $pageNavigation
                            ->setPageSizes(range(1, 50))
                            ->setPageSize(5)
                            ->initFromUri();

                        return $pageNavigation;
                    }
                )
            ]
        );
    }

    /**
     * @param $id
     * @return array|null
     */
    public function initializeAction($id): ?array
    {
        try {
            $navParameter = $this->getAutoWiredParameters()[0];
            $nav = $navParameter->constructValue(
                new ReflectionParameter([$this, 'getGratitudeListAction'], 'nav'),
                new Result()
            );

            $employee = EmployeeProfile::getEmployee($id);
            $employeeProfile = new EmployeeProfile($employee);

            $employeeServiceManager = EmployeeProfile::getServiceManager($employee);
            $profileDataManager = EmployeeProfile::getProfileDataManager($employee);
            $personalAbout = $profileDataManager->getPersonalAbout();


            $gratitude = EmployeeProfile::getGratitudeManager($employee)->getList($nav->getPageSize(), $nav->getCurrentPage());
            $nav->setRecordCount((int) $gratitude['TOTAL']);
            $gratitude['NAV'] = $nav;
            unset($gratitude['TOTAL']);

            $result = [
                'PERSONAL' => $profileDataManager->getPersonalData(),
                'STRUCTURE' => $profileDataManager->getDepartmentStructure(),
                'ABOUT' => !empty($personalAbout) ? Json::decode($personalAbout->getContent())['data'] : null,
                'COMPETENCIES' => EmployeeProfile::getCompetenceManager($employee)->list()->getData(),
                'INTERESTS' => EmployeeInterests::formatInterests(EmployeeProfile::getInterestManager($employee)->list()),
                'GRATITUDES' => $gratitude,
                'VACATIONS' => $employeeServiceManager->getCurrentVacationBlock(),
                'FIELDS' => $profileDataManager->getEditFormInfo(),
                'PERMISSIONS' => $profileDataManager->getPermissionsForUserProfile(),
                'BADGES' => EmployeeProfile::getBusinessBadgesManager($employee)->get()
            ];

            if ($employeeProfile->isOwnProfile()) {
                $nav = $navParameter->constructValue(
                    new ReflectionParameter([$this, 'getRequestListAction'], 'nav'),
                    new Result()
                );

                $result['SERVICES'] = [
                    'MAIN' => $employeeServiceManager->getMainServiceWidgets(),
                    'FAVORITE' => $employeeServiceManager->getFavoriteServiceWidgets(),
                ];

                $serviceRequest = EmployeeProfile::getServiceManager($id)->getRequestList('active', $nav->getOffset(), $nav->getPageSize());
                $nav->setRecordCount((int) $serviceRequest['total']);
                $serviceRequest['nav'] = $nav;
                $result['REQUESTS'] = $serviceRequest;

                $shopAccountAction = $employeeServiceManager->getShopAccountAction();
                if (!empty($shopAccountAction)) {
                    $result['SHOP'] = $shopAccountAction;
                }
            }

            return $result;
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getPersonalDataAction(int $id): ?array
    {
        try {
            return EmployeeProfile::getProfileDataManager($id)->getPersonalData();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param string $search
     * @return array|null
     */
    public function searchCompetenceAction(int $id, string $search = ''): ?array
    {
        try {
            return EmployeeProfile::getCompetenceManager($id)->search($search)->getData();
        } catch (\Exception $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * Возвращает запись для блока "Обо мне"
     *
     * @param int $id
     * @return HtmlContent|array|null
     */
    public function getPersonalAboutAction(int $id): HtmlContent|array|null
    {
        try {
            return EmployeeProfile::getProfileDataManager($id)->getPersonalAbout();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param array $data
     * @return HtmlContent|null
     */
    public function updatePersonalAboutAction(int $id, array $data = []): ?HtmlContent
    {
        try {
            // такой вот хак от размножения переносов строк
            $data['text'] = str_replace("\r\n", "\n", $data['text']);
            if (empty($data['text'])) {
                $this->deletePersonalAboutAction($id);
                return null;
            }

            $employeeProfile = new EmployeeProfile(EmployeeProfile::getShortEmployee($id));
            $result = $employeeProfile->getAboutManager()->sendProfileBlogPostFormAction($data);
            if (!$result) {
                return null;
            }

            return $this->getPersonalAboutAction($id);
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function deletePersonalAboutAction(int $id): ?bool
    {
        try {
            $employeeProfile = new EmployeeProfile(EmployeeProfile::getShortEmployee($id));
            return (bool)$employeeProfile->getAboutManager()->deleteProfileBlogPostAction();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param array $data
     * @return array|null
     */
    public function updatePersonalDataAction(int $id, array $data, array $sortSettings): ?array
    {
        try {
            if (empty($data)) {
                throw new ArgumentNullException('Нет данных для сохранения');
            }

            $profileDataManager = EmployeeProfile::getProfileDataManager($id);
            $profileDataManager->updatePersonalData($data);
            $profileDataManager->updateSortSettings($sortSettings);

            return [
                'PERSONAL' => $this->getPersonalDataAction($id),
                'FIELDS' => $profileDataManager->getEditFormInfo(),
                'PERMISSIONS' => $profileDataManager->getPermissionsForUserProfile(),
            ];
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return string|null
     */
    public function updatePhotoAction(int $id): ?string
    {
        try {
            $newPhotoFile = $this->getRequest()->getFile("newPhoto");
            if (empty($newPhotoFile)) {
                throw new ArgumentNullException('Не выбрано фото.');
            }

            $photoManager = EmployeeProfile::getPhotoManager($id);
            $photoManager->update($newPhotoFile);
            return $photoManager->get();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return string|null
     */
    public function deletePhotoAction(int $id): ?string
    {
        try {
            $photoManager = EmployeeProfile::getPhotoManager($id);
            $photoManager->delete();

            return $photoManager->get();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getStructureAction(int $id): ?array
    {
        try {
            $employeeDataManager = EmployeeProfile::getProfileDataManager($id);
            return $employeeDataManager->getDepartmentStructure();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param PageNavigation $nav
     * @return array|null
     */
    public function getGratitudeListAction(int $id, PageNavigation $nav): ?array
    {
        try {
            $result = EmployeeProfile::getGratitudeManager($id)->getList($nav->getPageSize(), $nav->getCurrentPage());
            $nav->setRecordCount((int) $result['TOTAL']);
            $result['NAV'] = $nav;
            unset($result['TOTAL']);

            return $result;
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getCompetenceListAction(int $id): ?array
    {
        try {
            return EmployeeProfile::getCompetenceManager($id)->list()->getData();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param string $tag
     * @return array|null
     */
    public function addCompetenceAction(int $id, string $tag): ?array
    {
        try {
            return EmployeeProfile::getCompetenceManager($id)->add($tag)->getData();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param string $tag
     * @return bool|null
     */
    public function deleteCompetenceAction(int $id, string $tag): ?bool
    {
        try {
            $result = EmployeeProfile::getCompetenceManager($id)->delete($tag);
            if (!$result->isSuccess()) {
                throw new InvalidOperationException(implode(PHP_EOL, $result->getErrorMessages()));
            }

            return true;
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getInterestListAction(int $id): ?array
    {
        try {
            $result = EmployeeProfile::getInterestManager($id)->list();
            return EmployeeInterests::formatInterests($result);
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param string $search
     * @return array|null
     */
    public function searchInterestAction(int $id, string $search = ''): ?array
    {
        try {
            $result = EmployeeProfile::getInterestManager($id)->search($search);
            return EmployeeInterests::formatInterests($result);
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param string $tag
     * @return array|null
     */
    public function addInterestAction(int $id, string $tag): ?array
    {
        try {
            $result = EmployeeProfile::getInterestManager($id)->add($tag);
            return EmployeeInterests::formatInterests($result);
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param string $tag
     * @return bool|null
     */
    public function deleteInterestAction(int $id, string $tag): ?bool
    {
        try {
            $result = EmployeeProfile::getInterestManager($id)->delete($tag);
            if (!$result->isSuccess()) {
                throw new InvalidOperationException(implode(PHP_EOL, $result->getErrorMessages()));
            }

            return true;
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getMainServiceListAction(int $id): ?array
    {
        try {
            return EmployeeProfile::getServiceManager($id)->getMainServiceWidgets();

        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getFavoriteServiceListAction(int $id): ?array
    {
        try {
            return EmployeeProfile::getServiceManager($id)->getFavoriteServiceWidgets();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getCurrentVacationAction(int $id): ?array
    {
        try {
            return EmployeeProfile::getServiceManager($id)->getCurrentVacationBlock();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param PageNavigation $nav
     * @param string $status
     * @return array|null
     */
    public function getRequestListAction(int $id, PageNavigation $nav, string $status = 'active'): ?array
    {
        try {
            $serviceManager = EmployeeProfile::getServiceManager($id);
            $result = $serviceManager->getRequestList($status, $nav->getOffset(), $nav->getPageSize());
            $nav->setRecordCount((int) $result['total']);
            $result['nav'] = $nav;

            return $result;
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));

            return null;
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getShopAccountAction(int $id): ?array
    {
        try {
            $employee = EmployeeProfile::getEmployee($id);
            $employeeServiceManager = EmployeeProfile::getServiceManager($employee);

            return $employeeServiceManager->getShopAccountAction();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * Подготавливает информацию о полях формы редактирования в личном кабинете
     *
     * @param int $id
     * @return array|null
     */
    public function getFieldsInfoAction(int $id): ?array
    {
        try {
            return EmployeeProfile::getProfileDataManager($id)->getEditFormInfo();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getListEmojiAction(int $id): ?array
    {
        try {
            return EmployeeProfile::getEmojiManager($id)->getList()->getData();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getEmojiAction(int $id): ?array
    {
        try {
            return EmployeeProfile::getEmojiManager($id)->get()->getData();
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param int $emojiId
     * @return bool|null
     */
    public function putEmojiAction(int $id, int $emojiId): ?bool
    {
        try {
            return EmployeeProfile::getEmojiManager($id)->put($emojiId);
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @param string $fieldCode
     * @param bool $value
     * @return bool|null
     */
    public function updateFieldSettingsAction(int $id, string $fieldCode, bool $value): ?bool
    {
        try {
            return EmployeeProfile::getProfileDataManager($id)->updateFieldSettings($fieldCode, $value);
        } catch (Throwable $exception) {
            $this->addError(new Error($exception->getMessage(), $exception->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return string[]|null
     */
    public function fireEmployeeAction(int $id): ?array
    {
        try {
            $profileDataManager = EmployeeProfile::getProfileDataManager($id);
            $profileDataManager->updatePersonalData(['ACTIVE' => 'N']);

            return ['STATUS' => 'fired'];
        } catch (Exception $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return string[]|null
     */
    public function hireEmployeeAction(int $id): ?array
    {
        try {
            $profileDataManager = EmployeeProfile::getProfileDataManager($id);
            $profileDataManager->updatePersonalData(['ACTIVE' => 'Y']);

            return ['STATUS' => 'employee'];
        } catch (Exception $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return string[]|null
     */
    public function setAdminRightsEmployeeAction(int $id): ?array
    {
        try {
            $currentUser = CurrentUser::get();

            $params = [
                'userId' => $id,
                'currentUserId' => $currentUser->getId(),
                'isCurrentUserAdmin' => $currentUser->isAdmin()
            ];

            if (!\Bitrix\Intranet\Util::setAdminRights($params)) {
                throw new InvalidOperationException('Can\'t set admin rights');
            }

            return ['STATUS' => 'admin'];
        } catch (\Exception $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    /**
     * @param int $id
     * @return string[]|null
     */
    public function removeAdminRightsEmployeeAction(int $id): ?array
    {
        try {
            $currentUser = CurrentUser::get();

            $params = [
                'userId' => $id,
                'currentUserId' => $currentUser->getId(),
                'isCurrentUserAdmin' => $currentUser->isAdmin()
            ];

            if (!\Bitrix\Intranet\Util::removeAdminRights($params)) {
                throw new InvalidOperationException('Can\'t remove admin rights');
            }

            return ['STATUS' => 'employee'];
        } catch (\Exception $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }
}
