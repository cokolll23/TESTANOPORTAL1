<?php

namespace Korus\Office\Profile;

use Bitrix\Intranet\Component\UserProfile;
use Bitrix\Main\ArgumentException;
use KTeam\Main\EO_User;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Web\Uri;
use Korus\Office\Formatter;
use Psr\Container\NotFoundExceptionInterface;

class EmployeeGratitude
{
    /**
     * @var Employee
     */
    private Employee $employee;

    /**
     * @param Employee $employee
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * @param int $maxResult
     * @param int $page
     * @return array
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException|NotFoundExceptionInterface
     */
    public function getList(int $maxResult = 20, int $page = 1): array
    {
        Loader::includeModule('intranet');

        $gratitudeList = [
            'USERS' => [],
            'URLS' => []
        ];

        $urls = static::getGratitudeUrlTemplates();

        $grats = new UserProfile\Grats([
            'profileId' => $this->employee->getId(),
            'pathToPostEdit' => $urls['edit'],
            'pathToUserGrat' => $urls['list'],
            'pathToPost' => $urls['view'],
            'pathToUser' => $urls['user'],
            'pageSize' => $maxResult,
        ]);

        $stub = $grats->getStub();
        $gratitudeList['BADGES'] = array_map([Formatter\Gratitude\Badge::class, 'format'], $stub['BADGES']);
        $gratitudeList['URLS']['ADD'] = $stub['URL_ADD'];

        $uri = new Uri($stub['URL_LIST']);
        $gratitudeList['URLS']['LIST'] = $uri->addParams(["gratUserId" => $this->employee->getId()])->getUri();

        $data = $grats->getGratitudePostListAction(['pageNum' => $page]);
        $gratitudeList['BADGES_COUNTERS'] = $data['BADGES'];
        $gratitudeList['POSTS'] = array_map([Formatter\Gratitude\Post::class, 'format'], (array)$data['POSTS']);
        $gratitudeList['TOTAL'] = $data['POSTS_COUNT'];

        foreach ($data['AUTHORS'] as $userData) {
            $user = EO_User::wakeUp([
                'ID' => $userData['ID'],
                'NAME' => $userData['NAME'],
                'LAST_NAME' => $userData['LAST_NAME'],
                'SECOND_NAME' => $userData['SECOND_NAME'],
                'LOGIN' => $userData['LOGIN'],
                'PERSONAL_PHOTO' => $userData['PERSONAL_PHOTO']
            ]);
            $author = new Employee($user);

            $gratitudeList['USERS'][$user->getId()] = array_merge(
                $author->getEmployeeData(),
                ['URL' => $userData['URL']]
            );
        }

        return $gratitudeList;
    }

    /**
     * @return string[]
     */
    private static function getGratitudeUrlTemplates(): array
    {
        return [
            'list' => '/company/personal/user/#user_id#/grat/',
            'edit' => '/company/personal/user/#user_id#/blog/edit/grat/#post_id#/',
            'view' => '/company/personal/user/#user_id#/blog/#post_id#/',
            'user' => '/company/personal/user/#user_id#/',
        ];
    }
}
