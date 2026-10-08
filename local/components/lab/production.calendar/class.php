<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Web\Json;

class ProductionCalendarComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $currentYear = (int)date('Y');

        $arParams['YEAR'] = (int)($arParams['YEAR'] ?? $currentYear);

        if ($arParams['YEAR'] < 2000 || $arParams['YEAR'] > 2100) {
            $arParams['YEAR'] = $currentYear;
        }

        $arParams['WEEK_HOURS'] = (float)($arParams['WEEK_HOURS'] ?? 40);
        $arParams['MIN_DATE'] = (string)($arParams['MIN_DATE'] ?? '');
        $arParams['MAX_DATE'] = (string)($arParams['MAX_DATE'] ?? '');

        $arParams['HOLIDAYS'] = is_array($arParams['HOLIDAYS'])
            ? $arParams['HOLIDAYS']
            : [];

        $arParams['MOVED_WORKDAYS'] = is_array($arParams['MOVED_WORKDAYS'])
            ? $arParams['MOVED_WORKDAYS']
            : [];

        return $arParams;
    }

    public function executeComponent()
    {
        $year = (int)$this->arParams['YEAR'];

        $calendar = $this->buildCalendar(
            $year,
            $this->arParams['HOLIDAYS'],
            $this->arParams['MOVED_WORKDAYS'],
            (float)$this->arParams['WEEK_HOURS']
        );

        $this->arResult = [
            'YEAR' => $year,
            'MONTHS' => $calendar['months'],
            'DAYS' => $calendar['days'],
            'DATA_JSON' => Json::encode($calendar['days']),
            'MIN_DATE' => $this->arParams['MIN_DATE'],
            'MAX_DATE' => $this->arParams['MAX_DATE'],
            'WEEK_HOURS' => $this->arParams['WEEK_HOURS'],
        ];

        $this->includeComponentTemplate();
    }

    private function buildCalendar(
        int $year,
        array $holidays,
        array $movedWorkdays,
        float $weekHours
    ): array {
        $months = [];
        $days = [];

        $dailyHours = $weekHours / 5;

        $monthNames = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        ];

        $date = new DateTimeImmutable($year . '-01-01');
        $lastDate = new DateTimeImmutable($year . '-12-31');

        while ($date <= $lastDate) {
            $dateString = $date->format('Y-m-d');
            $month = (int)$date->format('n');
            $weekday = (int)$date->format('N');

            $isRegularWorkday = $weekday <= 5;
            $isMovedWorkday = isset($movedWorkdays[$dateString]);
            $holidayData = $holidays[$dateString] ?? null;

            $type = 'weekend';
            $hours = 0;
            $title = 'Выходной день';

            if ($isRegularWorkday) {
                $type = 'workday';
                $hours = $dailyHours;
                $title = 'Рабочий день';
            }

            if ($isMovedWorkday) {
                $type = 'workday';
                $hours = isset($movedWorkdays[$dateString]['hours'])
                    ? (float)$movedWorkdays[$dateString]['hours']
                    : $dailyHours;

                $title = $movedWorkdays[$dateString]['title']
                    ?? 'Перенесённый рабочий день';
            }

            if ($holidayData !== null) {
                $type = 'holiday';
                $hours = 0;
                $title = is_array($holidayData)
                    ? ($holidayData['title'] ?? 'Праздничный день')
                    : (string)$holidayData;
            }

            if (
                $type === 'workday'
                && $hours > 0
                && $hours < $dailyHours
            ) {
                $type = 'reduced';
                $title = 'Сокращённый рабочий день';
            }

            $dayData = [
                'date' => $dateString,
                'day' => (int)$date->format('j'),
                'month' => $month,
                'weekday' => $weekday,
                'weekdayShort' => $this->getWeekdayShortName($weekday),
                'type' => $type,
                'hours' => $hours,
                'title' => $title,
            ];

            $days[$dateString] = $dayData;

            if (!isset($months[$month])) {
                $months[$month] = [
                    'number' => $month,
                    'title' => $monthNames[$month],
                    'days' => [],
                    'workDays' => 0,
                    'hours' => 0,
                ];
            }

            $months[$month]['days'][] = $dayData;

            if ($hours > 0) {
                $months[$month]['workDays']++;
                $months[$month]['hours'] += $hours;
            }

            $date = $date->modify('+1 day');
        }

        foreach ($months as &$monthData) {
            $monthData['hours'] = round($monthData['hours'], 2);
        }

        return [
            'months' => $months,
            'days' => $days,
        ];
    }

    private function getWeekdayShortName(int $weekday): string
    {
        $names = [
            1 => 'Пн',
            2 => 'Вт',
            3 => 'Ср',
            4 => 'Чт',
            5 => 'Пт',
            6 => 'Сб',
            7 => 'Вс',
        ];

        return $names[$weekday] ?? '';
    }
}
