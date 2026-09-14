<?php

namespace Korus\Office\Service;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use Doctrine\ORM\Exception\NotSupported;
use Korus\Main\Orm\Iblock\Manager;
use Korus\Personalarea\Doctrine\Entity\BUser;
use Korus\Personalarea\Doctrine\Entity\DeputyUser;
use Korus\Personalarea\Helpers\DoctrineHelper;
use Korus\Personalarea\Services\Entity\BUserService;

class Vacation extends Service
{
    private ?DataManager $dataManager = null;

    private function getDataManager(): DataManager
    {
        if (!$this->dataManager) {
            $iblockId = (int)Option::get('intranet', 'iblock_absence');
            if ($iblockId) {
                $provider = Manager::getInstance()->getProvider($iblockId);
            } else {
                $provider = Manager::getInstance()->getProviderByCode('absence', 'structure');
            }

            $className = $provider->getElementTableClassName();
            $this->dataManager = new $className();
        }

        return $this->dataManager;
    }

    public function getCurrentVacation(): ?array
    {
        static $current = null;
        if ($current === null) {
            $today = new Date();

            $query = $this->buildQuery();
            $query
                ->where('ACTIVE_FROM', '<=', $today)
                ->where('ACTIVE_TO', '>=', $today)
                ->setLimit(1);

            $current = $query->fetch();
        }

        return $current ?: null;
    }

    public function getComingVacation(): ?array
    {
        static $result = null;
        if ($result === null) {
            $today = new Date();

            $query = $this->buildQuery();
            $query
                ->where('ACTIVE_FROM', '>', $today)
                ->setOrder('ACTIVE_FROM')
                ->setLimit(1);

            $result = $query->fetch();
        }

        return $result ?: null;
    }

    public function getComingVacationList(): array
    {
        $today = new Date();

        $query = $this->buildQuery();
        $query->where('ACTIVE_FROM', '>', $today);

        return $query->fetchAll();
    }

    protected function buildQuery(): Query
    {
        /** @var Query $query */
        $query = $this->getDataManager()::query();
        $query
            ->setSelect(['ACTIVE_FROM', 'ACTIVE_TO'])
            ->where('ACTIVE', 'Y')
            ->where('USER', $this->user->getId())
            ->wherein('ABSENCE_TYPE_REF.XML_ID', ['VACATION', 'LEAVEUNPAYED', 'LEAVEMATERINITY']);

        return $query;
    }

    public function getRestVacationDays(): int
    {
        Loader::requireModule('korus.personalarea');

        $em = DoctrineHelper::getEntityManager();

        $user = $em->getRepository(BUser::class)->findOneBy(['ID' => $this->user->getId()]);
        $userService = new BUserService($user);

        return (int)$userService->getVacationsDay();
    }

    public function getWidgetDetails(): string
    {
        $arComing = $this->getComingVacationList();
        if (empty($arComing)) {
            return 'Нет запланированных отпусков.';
        }

        $resultHtml = "Запланирован<br>";
        foreach ($arComing as $vacation) {
            $resultHtml .= sprintf(
                "%s - %s <b>(%s)</b><br>",
                $vacation['ACTIVE_FROM']->format('d.m.Y'),
                $vacation['ACTIVE_TO']->format('d.m.Y'),
                static::getVacationLength($vacation['ACTIVE_FROM'], $vacation['ACTIVE_TO'])
            );
        }

        return $resultHtml;
    }

    public static function getVacationLength(DateTime $start, DateTime $end): string
    {
        return FormatDate('ddiff', $start, $end);
    }

    /**
     * @throws LoaderException
     */
    public function getDeputyId(): ?int
    {
        Loader::requireModule('korus.personalarea');

        $em = DoctrineHelper::getEntityManager();

        /** @var DeputyUser $deputy */
        $deputy = $em->getRepository(DeputyUser::class)->findOneBy([
            'active' => true,
            'deleted' => null,
            'user_id' => $this->user->getId(),
        ]);

        if (empty($deputy)) {
            return null;
        }

        return $deputy->getDeputyId();
    }

    /**
     * @return array
     * @throws LoaderException
     * @throws NotSupported
     */
    public function getDeputiesId(): array
    {
        $deputiesId = [];

        Loader::requireModule('korus.personalarea');

        $entityManager = DoctrineHelper::getEntityManager();

        $qb = $entityManager->getRepository(DeputyUser::class)->createQueryBuilder('dep');
        $qb->leftJoin('dep.type', 'type')
            ->where($qb->expr()->eq('dep.user_id', $this->user->getId()))
            ->andWhere($qb->expr()->eq('dep.active', true))
            ->andWhere($qb->expr()->isNull('dep.deleted'))
            ->andWhere($qb->expr()->isNotNull('type.id'));
        $deputies = $qb->getQuery()->getResult();

        foreach ($deputies as $deputy) {
            $deputiesId[] = (int)$deputy->getDeputyId();
        }

        return array_unique($deputiesId);
    }
}
