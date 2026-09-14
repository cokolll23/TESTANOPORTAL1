<?php

namespace Korus\Office\Profile;

use Bitrix\Intranet\Component\UserProfile\Tags;
use Bitrix\Main\AccessDeniedException;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\AddResult;
use Bitrix\Main\ORM\Data\DeleteResult;
use Bitrix\Main\ORM\Data\Result;
use Bitrix\Main\SystemException;
use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 *
 */
class EmployeeInterests
{
    /**
     * @var Employee
     */
    private Employee $employee;
    /**
     * @var Tags
     */
    private Tags $interestsManager;

    /**
     * @param Employee $employee
     * @throws LoaderException
     */
    public function __construct(Employee $employee)
    {
        Loader::requireModule('intranet');

        $this->employee = $employee;
        $this->interestsManager = new Tags([
            'profileId' => $employee->getId(),
            'permissions' => $this->employee->getSocialNetworkPermissions(),
        ]);
    }

    /**
     * @return Result
     */
    public function list(): Result
    {
        $result = new Result();

        try {
            $resultData = [];
            foreach ($this->interestsManager->getTagsListAction() as $name => $data) {
                $resultData[$name] = array_merge(
                    static::formatTagData($data),
                    ['CHECKSUM' => $data['CHECKSUM']]
                );
            }

            $result->setData($resultData);
        } catch (Exception $e) {
            $result->addError(new Error($e->getMessage()));
        }

        return $result;
    }

    /**
     * @param string $tag
     * @return Result
     */
    public function search(string $tag = ''): Result
    {
        $result = new Result();

        try {
            $params = [];
            if (!empty($tag)) {
                $params['searchString'] = $tag;
            }

            $resultData = [];
            foreach ($this->interestsManager->searchTagsAction($params) as $name => $data) {
                $data['COUNT'] = $data['CNT'];
                $resultData[$name] = array_merge(
                    static::formatTagData($data),
                    [
                        'NAME' => $data['NAME'],
                        'CHECKSUM' => $data['CHECKSUM']
                    ]
                );
            }

            $result->setData($resultData);
        } catch (Exception $e) {
            $result->addError(new Error($e->getMessage()));
        }

        return $result;
    }

    /**
     * @param string $tag
     * @return AddResult
     */
    public function add(string $tag): AddResult
    {
        $result = new AddResult();

        try {
            $this->checkContext($tag);

            $params = ['tag' => $tag];

            $resultData = [];
            foreach ($this->interestsManager->addTagAction($params) as $name => $data) {
                $resultData[$name] = static::formatTagData($data);
            }

            $result->setData($resultData);
        } catch (Exception $e) {
            $result->addError(new Error($e->getMessage()));
        }

        return $result;
    }

    /**
     * @param string $tag
     * @return DeleteResult
     */
    public function delete(string $tag): DeleteResult
    {
        $result = new DeleteResult();

        try {
            $this->checkContext($tag);

            $params = ['tag' => $tag];
            return $this->interestsManager->removeTagAction($params);
        } catch (Exception $e) {
            $result->addError(new Error($e->getMessage()));
        }

        return $result;
    }

    /**
     * @param string $tag
     * @return void
     * @throws AccessDeniedException
     * @throws ArgumentNullException
     * @throws LoaderException
     */
    private function checkContext(string $tag): void
    {
        if (empty($tag)) {
            throw new ArgumentNullException('tag');
        }

        if (!$this->employee->canEdit()) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @param array $data
     * @return array
     */
    private static function formatTagData(array $data): array
    {
        $result = [
            'COUNT' => (int)$data['COUNT'],
            'USERS' => []
        ];
        foreach ($data['USERS'] as $user) {
            $result['USERS'][] = [
                'ID' => $user['ID'],
                'WEIGHT' => $user['WEIGHT']
            ];
        }

        return $result;
    }

    /**
     * @param Result $result
     * @return array
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException|NotFoundExceptionInterface
     */
    public static function formatInterests(Result $result): array
    {
        $data = $result->getData();

        $users = [];
        foreach ($data as $info) {
            $users = array_merge($users, array_column($info['USERS'], 'ID'));
        }

        return [
            'INTERESTS' => $data,
            'USERS' => Employee::getUsersShort($users)
        ];
    }
}
