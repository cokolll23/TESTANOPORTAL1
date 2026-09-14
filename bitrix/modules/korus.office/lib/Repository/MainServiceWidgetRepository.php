<?php

declare(strict_types=1);

namespace Korus\Office\Repository;

use Bitrix\Iblock\Iblock;
use Bitrix\Iblock\ORM\CommonElementTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\NotImplementedException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Query\Result;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use CFile;
use Korus\Main\Orm\Iblock\Model;
use Korus\Office\Profile\Employee;
use Korus\Office\Repository\Contract\ServiceWidgetRepositoryContract;
use Korus\Office\Service\MainDefaultService;
use Korus\Office\Service\Manager as ServiceManager;
use Korus\Office\Service\Widget\MainDefaultServiceWidget;
use KTeam\Main\EO_User;
use Psr\Container\NotFoundExceptionInterface;

class MainServiceWidgetRepository implements ServiceWidgetRepositoryContract
{
    private EO_User $employee;
    public array $defaultWidgets = [
        'vacation',
        'insurance',
        'mobile'
    ];
    private false|Model $iblock;
    private CommonElementTable|string $iblockProvider;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ObjectNotFoundException
     * @throws NotImplementedException
     * @throws ArgumentException
     */
    public function __construct()
    {
        $this->employee = Employee::getEmployee(CurrentUser::get()->getId());

        $this->iblock = Model::getByCode('MainServiceWidgets', 'Navigation');

        if (!$this->iblock) {
            throw new NotImplementedException('IBlock not found');
        }

        $this->iblockProvider = Iblock::wakeUp($this->iblock->getId())->getEntityDataClass();
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public function getActiveWidgets(): array
    {
        $serviceWidgets = [];

        $activeWidgetsParam = [
            'select' => [
                'ID',
                'NAME',
                'CODE',
                'ICON_BG_COLOR',
                'ICON',
                'PREVIEW_TEXT',
                "BTN",
            ],
            'filter' => ['ACTIVE' => 'Y', ...$this->getActiveDateArrayFilter()],
            'order' => [
                'SORT' => 'ASC'
            ],
        ];

        $activeWidgets = $this->getList($activeWidgetsParam);

        foreach ($activeWidgets->fetchCollection() as $widget) {
            if (in_array($widget->getCode(), $this->defaultWidgets, true)) {
                $defaultServiceWidget = ServiceManager::getWidget($widget->getCode(), $this->employee);
                $defaultServiceWidget->setTitle($widget->getName());

                if (!empty($widget->getIcon()->getValue())) {
                    $icon = CFile::GetPath($widget->getIcon()->getValue());
                    if (!empty($icon)) {
                        $defaultServiceWidget->setImage('img:' . $icon);
                    }
                }

                if (!empty($widget->getIconBgColor()->getValue())) {
                    $defaultServiceWidget->setColor($widget->getIconBgColor()->getValue());
                }

                $serviceWidgets[] = $defaultServiceWidget;
                continue;
            }

            $service = new MainDefaultService($widget, $this->employee);
            $serviceWidgets[] = new MainDefaultServiceWidget($service);
        }

        return $serviceWidgets;
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public function getList($params): Result
    {
        return $this->iblockProvider::getList($params);
    }

    public function getActiveDateArrayFilter(bool $negative = false): array
    {
        $now = new DateTime();

        if ($negative) {
            $filter = [
                'LOGIC' => 'OR',
                '<ACTIVE_TO' => $now,
                '>ACTIVE_FROM' => $now,
            ];
        } else {
            $filter = [
                [
                    'LOGIC' => 'OR',
                    '>=ACTIVE_TO' => $now,
                    'ACTIVE_TO' => null
                ],
                [
                    'LOGIC' => 'OR',
                    '<=ACTIVE_FROM' => $now,
                    'ACTIVE_FROM' => null
                ]
            ];
        }

        return $filter;
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public function isWidgetDefault(int $id): bool
    {
        $widget = $this->iblockProvider::getByPrimary($id, [
            'select' => ['ID', 'CODE'],
        ])->fetch();

        if (empty($widget['CODE'])) {
            return false;
        }

        return in_array($widget['CODE'], $this->defaultWidgets, true);
    }
}