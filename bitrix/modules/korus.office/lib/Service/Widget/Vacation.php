<?php

declare(strict_types=1);

namespace Korus\Office\Service\Widget;

use Bitrix\Main\Localization\Loc;

class Vacation extends Widget
{
    public function getTitle(): string
    {
        return $this->title ?? Loc::getMessage("VACATION_TITLE");
    }

    protected function getTitleSuffix(): string
    {
        $rest = $this->service->getRestVacationDays();

        if ($rest > 0) {
            return Loc::getMessage('VACATION_TITLE_SUFFIX', ['#DAYS#' => $rest]);
        }

        return '';
    }

    public function getImage(): string
    {
        return $this->image ?? 'palm';
    }

    public function getColor(): string
    {
        return $this->color ?? '#FCB64D';
    }

    public function getButtons(): array
    {
        return [
            [
                'LABEL' => Loc::getMessage("VACATION_BTN_LABEL"),
                'ICON' => 'document',
                'URL' => '/lk/planner/'
            ]
        ];
    }
}
