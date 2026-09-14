<?php

declare(strict_types=1);

namespace Korus\Office\Service;

use Bitrix\Main\LoaderException;
use Korus\Office\Repository\FavoriteServiceWidgetRepository;

class FavoriteWidgetService
{
    private mixed $repository;

    public function __construct($repository = new FavoriteServiceWidgetRepository())
    {
        $this->repository = $repository;
    }

    /**
     * @throws LoaderException
     */
    public function getActiveWidgets(): array
    {
        return $this->repository->getActiveWidgets();
    }
}