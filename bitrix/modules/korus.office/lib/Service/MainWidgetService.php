<?php

declare(strict_types=1);

namespace Korus\Office\Service;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Korus\Office\Repository\MainServiceWidgetRepository;

class MainWidgetService
{
    private mixed $repository;

    public function __construct($repository = new MainServiceWidgetRepository())
    {
        $this->repository = $repository;
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public function getActiveWidgets(): array
    {
        return $this->repository->getActiveWidgets();
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public function isWidgetDefault(int $id): bool
    {
        return $this->repository->isWidgetDefault($id);
    }

    public function getDefaultWidgetsCode(): array
    {
        return $this->repository->defaultWidgets;
    }
}