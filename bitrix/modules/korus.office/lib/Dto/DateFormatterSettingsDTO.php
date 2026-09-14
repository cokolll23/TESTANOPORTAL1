<?php

declare(strict_types=1);

namespace Korus\Office\Dto;

final class DateFormatterSettingsDTO
{
    public readonly string $showYearValue;
    public readonly string $longDateFormat;
    public readonly string $dayMonthFormat;

    public function __construct(string $showYearValue, string $longDateFormat, string $dayMonthFormat)
    {
        $this->showYearValue = $showYearValue;
        $this->longDateFormat = $longDateFormat;
        $this->dayMonthFormat = $dayMonthFormat;
    }
}
