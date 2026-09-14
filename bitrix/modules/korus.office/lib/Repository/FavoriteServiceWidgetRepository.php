<?php

declare(strict_types=1);

namespace Korus\Office\Repository;

use Bitrix\Iblock\EO_Section;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ORM\Query\Query;
use CUserOptions;
use Korus\Main\EntityManager;
use Korus\Office\Profile\Employee;
use Korus\Office\Repository\Contract\ServiceWidgetRepositoryContract;
use Korus\Office\Service\IblockService;
use Korus\Office\Service\Widget\IblockServiceWidget;
use KTeam\Main\EO_User;
use Psr\Container\NotFoundExceptionInterface;

class FavoriteServiceWidgetRepository implements ServiceWidgetRepositoryContract
{
    private EntityManager $em;
    private EO_User $employee;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ObjectNotFoundException
     */
    public function __construct()
    {
        $this->employee = Employee::getEmployee(CurrentUser::get()->getId());
        $this->em = ServiceLocator::getInstance()->get('korus.main.entity.manager');
    }

    /**
     * @throws LoaderException
     */
    public function getActiveWidgets(): array
    {
        $favoriteServiceWidgets = [];

        if (!Loader::requireModule('korus.personalarea')) {
            return $favoriteServiceWidgets;
        }

        $userFavorites = CUserOptions::GetOption('favorite', 'favorite_service', []);

        if (empty($userFavorites)) {
            return $favoriteServiceWidgets;
        }

        /** @var Query $queryBuilder */
        $queryBuilder = $this->em->createQueryBuilder(
            $this->em->Iblock('menu_lk', 'Navigation')->createIblockSectionEntity()
        );

        $query = $queryBuilder
            ->setSelect([
                'ID',
                'NAME',
                'CODE',
                'UF_LINK',
                'PICTURE',
                'DESCRIPTION',
                'UF_COLOR',
                'PARENT_SECTION.UF_COLOR',
            ])
            ->where('ACTIVE', 'Y')
            ->whereIn('ID', $userFavorites)
            ->getQuery();

        /** @var EO_Section $section */
        foreach ($query->fetchCollection() as $section) {
            $service = new IblockService($section, $this->employee);
            $favoriteServiceWidgets[] = new IblockServiceWidget($service);
        }

        return $favoriteServiceWidgets;
    }
}
