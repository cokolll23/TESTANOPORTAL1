<?php

declare(strict_types=1);

namespace Korus\Office\Service\Widget;

use Korus\Office\Service\MainDefaultService;

class MainDefaultServiceWidget extends Widget
{
    public function __construct(MainDefaultService $service)
    {
        parent::__construct($service);
    }

    public function getTitle(): string
    {
        /** @var MainDefaultService $service */
        $service = $this->service;

        return $service->getWidgetTitle();
    }

    public function getImage(): string
    {
        /** @var MainDefaultService $service */
        $service = $this->service;

        return $service->getWidgetImage();
    }

    public function getColor(): string
    {
        /** @var MainDefaultService $service */
        $service = $this->service;

        return $service->getWidgetColor();
    }

    public function getButtons(): array
    {
        /** @var MainDefaultService $service */
        $service = $this->service;

        return $service->getWidgetButtons();
    }
}
