<?php

declare(strict_types=1);

/** @bxnolanginspection */

namespace Korus\Office\Profile;

use Bitrix\Iblock\EO_Section;
use Bitrix\Im\Model\ChatTable;
use Bitrix\Im\Model\MessageTable;
use Bitrix\Im\Model\MessageUnreadTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use CUserOptions;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Exception;
use Korus\Gifts\Helpers\User as GiftUserHelper;
use Korus\Office\Service\FavoriteWidgetService;
use Korus\Office\Service\MainWidgetService;
use Korus\Office\Service\Manager as ServiceManager;
use Korus\Office\Service\Vacation;
use Korus\Office\Service\WidgetService;
use Korus\Personalarea\Doctrine\Entity\UserRequests;
use Korus\Personalarea\Doctrine\Entity\UserRequestType;
use Korus\Personalarea\System\Doctrine;
use Psr\Container\NotFoundExceptionInterface;

class EmployeeService
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
     * Возвращает виджеты основных сервисов личного кабинета
     *
     * @return array
     */
    public function getMainServiceWidgets(): array
    {
        try {
            return (new MainWidgetService())->getActiveWidgets();
        } catch (ObjectPropertyException|ArgumentException|SystemException) {
            return [];
        }
    }

    /**
     * @return array
     * @throws LoaderException
     */
    public function getShopAccountAction(): array
    {
        if (Loader::includeModule('korus.gifts')) {
            $balance = number_format(
                (int)GiftUserHelper::getGiftBalans(),
                0,
                '.',
                ' '
            );
            return [
                'ACCOUNT' => [
                    'BALANCE' => $balance,
                ],
                'URLS' => [
                    'SHOP' => '/gifts/catalog/',
                ],
            ];
        }

        return [];
    }

    /**
     * Возвращает виджеты избранных сервисов личного кабинета
     *
     * @return array
     */
    public function getFavoriteServiceWidgets(): array
    {
        try {
            return (new FavoriteWidgetService())->getActiveWidgets();
        } catch (LoaderException) {
            return [];
        }
    }

    /**
     * @return array|null
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectNotFoundException
     * @throws ObjectPropertyException
     * @throws SystemException|NotFoundExceptionInterface
     */
    public function getCurrentVacationBlock(): ?array
    {
        $currentVacationBlock = [];

        /** @var Vacation $vacationService */
        $vacationService = ServiceManager::getService('vacation', $this->employee->getEntityObject());
        $currentVacation = $vacationService->getCurrentVacation();

        if (!empty($currentVacation) || (int)CurrentUser::get()->getId() === $this->employee->getId()) {
            $currentVacation['DEPUTY'] = $vacationService->getDeputiesId();
        }
        if ($currentVacation['ACTIVE_FROM'] instanceof DateTime
            && $currentVacation['ACTIVE_TO'] instanceof DateTime) {
            $days = $currentVacation['ACTIVE_TO']->getDiff($currentVacation['ACTIVE_FROM'])->days;
            if ($days > 0) {
                $currentVacation['DAYS'] = Loc::getMessagePlural(
                    'KORUS_EMPLOYEE_SERVICE_DAYS',
                    $days,
                    [
                        '#NUM#' => $days,
                    ]
                );
                $currentVacation['FROM'] = $currentVacation['ACTIVE_FROM']->format('d.m.Y');
                $currentVacation['TO'] = $currentVacation['ACTIVE_TO']->format('d.m.Y');
            }
            unset($currentVacation['ACTIVE_FROM'], $currentVacation['ACTIVE_TO']);
        }

        $currentVacationBlock['VACATION'] = $currentVacation;

        if ($currentVacation['DEPUTY'] === null) {
            $currentVacationBlock['USERS'] = [];
        } else {
            $currentVacationBlock['USERS'] = Employee::getUsersShort($currentVacation['DEPUTY']);
        }

        return $currentVacationBlock;
    }

    /**
     * @param string $status
     * @param int $offset
     * @param int $maxResults
     * @return array|null
     * @throws ArgumentException
     * @throws LoaderException
     * @throws NonUniqueResultException
     * @throws NotFoundExceptionInterface
     * @throws NotSupported
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws Exception
     */
    public function getRequestList(string $status = 'active', int $offset = 0, int $maxResults = 20): ?array
    {
        Loader::requireModule('korus.personalarea');

        $statusMap = [
            'active' => [UserRequests::$statuses['new'], UserRequests::$statuses['work']],
            'closed' => [
                UserRequests::$statuses['end'],
                UserRequests::$statuses['canceled'],
                UserRequests::$statuses['abort'],
            ],
        ];

        if (!isset($statusMap[$status])) {
            $status = 'active';
        }

        $entityManager = Doctrine::getInstance()?->getEntityManager();

        $requestRepository = $entityManager->getRepository(UserRequests::class);
        $requestTypeRepository = $entityManager->getRepository(UserRequestType::class);

        $query = $requestTypeRepository
            ->createQueryBuilder('rt')
            ->select(['rt.id', 'rt.title'])
            ->getQuery();
        $types = $query->getArrayResult();

        $statuses = [];
        foreach (UserRequests::$statusesNames as $key => $name) {
            $statuses[] = [
                'id' => $key,
                'title' => $name,
            ];
        }

        $qb = $requestRepository->createQueryBuilder('req');
        $qb->andWhere($qb->expr()->eq('req.user', $this->employee->getId()));
        $qb->andWhere($qb->expr()->in('req.status', $statusMap[$status]));

        $total = (int)$qb->select('COUNT(DISTINCT req.id) AS cnt')
            ->getQuery()
            ->getSingleScalarResult();

        $queryBuilder = $qb->leftJoin('req.bp', 'bp')
            ->leftJoin('bp.bpTasks', 'bpTask');
        if ($status === 'active') {
            $queryBuilder
                ->innerJoin(
                    'bpTask.bpTasksUsers',
                    'usTask',
                    Join::WITH,
                    'usTask.completed = 0'
                )
                ->andWhere('usTask.active = 1')
                ->leftJoin(
                    'bpTask.bpTasksUsers',
                    'usTaskChild',
                    Join::WITH,
                    'usTaskChild.assignment = usTask.id'
                )
                ->andWhere($qb->expr()->isNull('usTaskChild.id'));
        } else {
            $queryBuilder
                ->innerJoin(
                    'bpTask.bpTasksUsers',
                    'usTask',
                    Join:: WITH,
                    'usTask.completed = 1'
                );
        }
        $query = $queryBuilder
            ->leftJoin('usTask.bUser', 'resp')
            ->select([
                'req.id',
                'req.status',
                'req.type_id as type',
                'req.parameters',
                'req.createdAt as dateCreate',
                'MAX(bpTask.date_deadline) as dateEnd',
                'GROUP_CONCAT(resp.ID) as response',
                'bp.parameters as BpParams',
                'bp.state as stateType',
            ])
            ->setMaxResults($maxResults)
            ->addGroupBy('req.id')
            ->orderBy('req.id', 'desc')
            ->setFirstResult($offset)
            ->getQuery();

        $now = new Date();
        $viewedComments = CUserOptions::GetOption('korus.office', 'viewed_comments', []);
        $list = [];
        $usersToLoad = [];
        foreach ($query->getResult() as $item) {
            if (empty($item['response']) || (int)$item['status'] === UserRequests::$statuses['abort']) {
                $item['response'] = [];
            }
            if (is_string($item['response'])) {
                $item['response'] = array_filter(
                    array_unique(
                        array_map('trim', explode(',', $item['response']))
                    ),
                );
                if ($status !== 'active') {
                    $item['response'] = [array_pop($item['response'])];
                }

                $item['response'] = array_values($item['response']);
                foreach ($item['response'] as $userId) {
                    $usersToLoad[$userId] = $userId;
                }
            }

            if (is_string($item['dateEnd'])) {
                $item['dateEnd'] = new \DateTime($item['dateEnd']);
            }
            $item['maxDeadline'] = '';
            $item['isOverdue'] = false;
            if (!empty($item['BpParams']->maxDeadline)) {
                $item['maxDeadline'] = DateTime::createFromPhp(new \DateTime($item['BpParams']->maxDeadline));
                $item['isOverdue'] = $now->getTimestamp() > $item['maxDeadline']->getTimestamp();
            }

            $item['dateCreate'] = DateTime::createFromPhp($item['dateCreate']);
            $item['dateEnd'] = DateTime::createFromPhp($item['dateEnd']);

            $item['delivery'] = match ($item['parameters']->receipt) {
                'delivery' => $item['parameters']->delivery,
                'personal' => 'Лично',
                default => 'По электронной почте'
            };
            $item['canAbort'] = $item['status'] <= 2;

            if ((int)$item['status'] === UserRequests::$statuses['new']) {
                if ($item['stateType'] === 'bossApproval') {
                    $item['status'] = UserRequests::$statuses['coordination'];
                } elseif ($item['BpParams']->headApprove === true) {
                    $item['status'] = UserRequests::$statuses['agreed'];
                }
            }
            $item['indicator'] = $this->getIndicator($item);
            $item['color'] = UserRequests::$statusesColors[$item['status']];

            $item['comment'] = [
                'text' => $item['BpParams']->comment ?? '',
                'new' => !in_array($item['id'], $viewedComments),
            ];

            unset($item['parameters'], $item['BpParams'], $item['stateType']);
            $list[] = $item;
        }
        $this->addCounterMessage($list);
        return [
            'list' => $list,
            'types' => $types,
            'statuses' => $statuses,
            'users' => Employee::getUsersShort($usersToLoad),
            'total' => $total,
        ];
    }

    private function getIndicator(array $item): string
    {
        $result = '';
        switch ((int)$item['status']) {
            case UserRequests::$statuses['agreed']:
            case UserRequests::$statuses['new']:
                $result = count($item['response']) > 1 ? 'Ждет распределения' : 'В обработке';
                break;
            case UserRequests::$statuses['coordination']:
                $result = 'На согласовании';
                break;
            case UserRequests::$statuses['work']:
                $result = 'На исполнении';
                break;
            case UserRequests::$statuses['end']:
                $result = 'Процесс завершен';
                break;
            case UserRequests::$statuses['abort']:
            case UserRequests::$statuses['canceled']:
                $result = 'Процесс прерван';
                break;
        }
        return $result;
    }

    /**
     * @throws ObjectPropertyException
     * @throws ArgumentException
     * @throws SystemException
     */
    private function addCounterMessage(array &$list): void
    {
        $ids = array_column($list, 'id');
        $query = ChatTable::query()
            ->where('AUTHOR_ID', CurrentUser::get()->getId())
            ->where('TYPE', IM_MESSAGE_SYSTEM)
            ->registerRuntimeField(
                new Reference(
                    '_MESSAGE', MessageTable::class,
                    \Bitrix\Main\ORM\Query\Join::on('this.ID', 'ref.CHAT_ID')
                        ->where('ref.NOTIFY_MODULE', 'korus.personalarea')
                        ->whereLike('ref.NOTIFY_TAG', 'TASK_LK'),
                )
            )
            ->registerRuntimeField(
                new Reference(
                    '_UNREAD', MessageUnreadTable::class,
                    \Bitrix\Main\ORM\Query\Join::on('this._MESSAGE.CHAT_ID', 'ref.CHAT_ID')
                        ->whereColumn('this._MESSAGE.ID', 'ref.MESSAGE_ID'),
                )
            )
            ->whereIn('_MESSAGE.NOTIFY_SUB_TAG', $ids)
            ->addSelect('_MESSAGE.NOTIFY_SUB_TAG', 'TASK_ID')
            ->addSelect(Query::expr()->count('_UNREAD.ID'), 'NEW_RESPONSE');
        $arCount = array_column($query->fetchAll(), 'NEW_RESPONSE', 'TASK_ID');

        foreach ($list as &$item) {
            $item['countMessage'] = (int)$arCount[$item['id']];
        }
        unset($item);
    }
}
