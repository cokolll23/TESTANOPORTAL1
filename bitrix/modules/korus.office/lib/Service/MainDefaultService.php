<?php

declare(strict_types=1);

namespace Korus\Office\Service;

use Bitrix\Main\EO_User;
use CFile;
use KTeam\Main\EO_User as KEO_User;

class MainDefaultService extends Service
{
    private mixed $widget;

    public function __construct($widget, EO_User|KEO_User $user)
    {
        parent::__construct($user);
        $this->widget = $widget;
    }

    public function getWidgetTitle(): string
    {
        return $this->widget->getName();
    }

    public function getWidgetImage(): string
    {
        $imgId = $this->widget->getIcon()->getValue();

        if (empty($imgId)) {
            return 'kt:star';
        }

        return 'img:' . CFile::GetPath($imgId);
    }

    public function getWidgetColor(): string
    {
        if (empty((string) $this->widget->getIconBgColor()->getValue())) {
            return '#7949f4';
        }

        return (string) $this->widget->getIconBgColor()->getValue();
    }

    public function getWidgetButtons(): array
    {
        $result = [];

        $buttons = $this->widget->getBtn();
        if (!empty($buttons)) {
            foreach ($buttons as $button) {
                $result[] = [
                    'LABEL' => $button->getDescription(),
                    'URL' => $button->getValue(),
                    // 'ICON' => 'kt:arrow-circle',
                ];
            }
        }

        return $result;
    }

    public function getWidgetDetails(): string
    {
        return $this->widget->getPreviewText();
    }
}
