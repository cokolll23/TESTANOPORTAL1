<?php

declare(strict_types=1);

namespace Korus\Office\Service\Widget;

use Bitrix\Main\Localization\Loc;
use Korus\Office\Service\Widget\Insurance\Button\Clinics;
use Korus\Office\Service\Widget\Insurance\Button\Links;
use Korus\Office\Service\Widget\Insurance\Button\Service;

class Insurance extends Widget
{
    public function getTitle() : string
    {
        return $this->title ?? Loc::getMessage("INSURANCE_TITLE");
    }

    public function getImage() : string
    {
        return $this->image ?? 'pulse';
    }

    public function getColor() : string
    {
        return $this->color ?? '#F9423A';
    }

    public function getButtons(): array
    {
        $buttons = [];

        // TODO: Сделать отдельный метод проверки наличия полиса
        if ($this->service->getWidgetDetails() === 'Не найден закрепленный полис') {
            return $buttons;
        }

        foreach (static::collectButtons() as $button) {
            $description = $button::get();
            if (!empty($description)) {
                $buttons[] = $description;
            }
        }

        return $buttons;
    }

    /**
     * @return Button[]
     */
    protected static function collectButtons() : array
    {
        return [Clinics::class, Service::class, Links::class];
    }
}
