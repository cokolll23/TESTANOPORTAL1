<?php

namespace Korus\Office\Service\Widget;

use Korus\Office\Service\IblockService;

class IblockServiceWidget extends Widget
{
    public function __construct(IblockService $service)
    {
        parent::__construct($service);
    }

    public function getTitle() : string
    {
        /** @var IblockService $service */
        $service = $this->service;
        return $service->getWidgetTitle();
    }

    public function getImage() : string
    {
        /** @var IblockService $service */
        $service = $this->service;
        return $service->getWidgetImage();
    }

    public function getColor() : string
    {
        /** @var IblockService $service */
        $service = $this->service;
        return $service->getWidgetColor();
    }

    public function getButtons() : array
    {
        /** @var IblockService $service */
        $service = $this->service;
        return $service->getWidgetButtons();
    }
}
