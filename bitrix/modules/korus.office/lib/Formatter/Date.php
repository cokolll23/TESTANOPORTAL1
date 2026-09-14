<?php

declare(strict_types=1);

namespace Korus\Office\Formatter;

use Korus\Office\Dto\DateFormatterSettingsDTO;

final class Date
{
    private string $showYearValue;

    private string $longDateFormat;

    private string $dayMonthFormat;

    public function __construct(DateFormatterSettingsDTO $dateSettingsDTO)
    {
        $this->showYearValue = $dateSettingsDTO->showYearValue;
        $this->longDateFormat = $dateSettingsDTO->longDateFormat;
        $this->dayMonthFormat = $dateSettingsDTO->dayMonthFormat;
    }

    public final function formatBirthdayByGender(\Bitrix\Main\Type\Date $date, string $gender): string
    {
        $currentFormat = $this->getBirthdayFormat($gender);

        return FormatDate($currentFormat, $date->getTimestamp());
    }

    protected final function getBirthdayFormat(string $gender): string
    {
        if ($this->needHideYear($gender)) {
            return $this->dayMonthFormat;
        }

        return $this->longDateFormat;
    }

    protected function needHideYear(string $gender): bool
    {
        if ($this->showYearValue === 'N') {
            return true;
        }

        if ($this->showYearValue === 'M' && $gender === 'F') {
            return true;
        }

        return false;
    }
}