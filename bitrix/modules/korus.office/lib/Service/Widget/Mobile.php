<?php

declare(strict_types=1);

namespace Korus\Office\Service\Widget;

use Bitrix\Main\Localization\Loc;

class Mobile extends Widget
{
    public function getTitle() : string
    {
        return $this->title ?? Loc::getMessage("MOBILE_TITLE");
    }

    public function getImage() : string
    {
        return $this->image ?? 'mobile-phone';
    }

    public function getColor() : string
    {
        return $this->color ?? '#ABCB57';
    }

    public function getButtons() : array
    {
        return [];
    }
}
