<?php

namespace Korus\Office\Service;

use KTeam\Main\EO_User;
use Bitrix\Main\Loader;
use Korus\Office\Service\Widget\Widget;
use Korus\Vult\Helpers\Strings;

require_once 'notfoundexception.php';

class Manager
{
    public static function getService(string $service, EO_User $user) : Service
    {
        $serviceClass = static::getServiceClassName($service);

        return new $serviceClass($user);
    }

    public static function getWidget(string $type, EO_User $user) : Widget
    {
        $widgetClass = static::getWidgetClassName($type);
        return new $widgetClass(static::getService($type, $user));
    }

    public static function getClassName($class) : string
    {
        Loader::requireModule('korus.vult');

        $result = __NAMESPACE__;
        foreach (explode("\\", $class) as $part) {
            $result .= "\\" . Strings::mb_ucfirst($part);
        }

        return $result;
    }

    public static function getServiceClassName(string $service) : string
    {
        return static::getClassName($service);
    }

    public static function getWidgetClassName(string $widget) : string
    {
        return static::getClassName("widget\\$widget");
    }
}
