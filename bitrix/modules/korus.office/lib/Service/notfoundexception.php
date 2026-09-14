<?php

namespace Korus\Office\Service;

use Bitrix\Main\ObjectNotFoundException;
use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends ObjectNotFoundException implements NotFoundExceptionInterface
{
    public function __construct(string $service = "", \Exception $previous = null)
    {
        parent::__construct(sprintf('Service %s not found.', Manager::getServiceClassName($service)), $previous);
    }
}

class WidgetNotFoundException extends ObjectNotFoundException implements NotFoundExceptionInterface
{
    public function __construct(string $widget = "", \Exception $previous = null)
    {
        parent::__construct(sprintf('Widget %s not found.', Manager::getWidgetClassName($widget)), $previous);
    }
}
