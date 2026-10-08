<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>
<?php
$json = file_get_contents(__DIR__ . '/holidays-2027.json');


// $json — строка с вашим JSON.
// Например: $json = file_get_contents(__DIR__ . '/calendar.json');

$data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

// Преобразование списка в массив вида:
// '2027-01-01' => ['title' => '...']
function indexByDate(array $items): array
{
    $result = [];

    foreach ($items as $item) {
        $date = substr($item['date'], 0, 10);

        $result[$date] = [
            'title' => $item['name'],
        ];
    }

    return $result;
}

// Праздники.
$holidays = indexByDate($data['holidays'] ?? []);

// Перенесённые выходные.
$transferredHolidays = indexByDate($data['transferredHolidays'] ?? []);

// Сокращённые рабочие дни.
$shortDays = indexByDate($data['shortDays'] ?? []);

foreach ($shortDays as &$day) {
    $day['hours'] = 7;
}
unset($day);

// Рабочие выходные.
$movedWorkdays = indexByDate($data['workingWeekends'] ?? []);

foreach ($movedWorkdays as $date => &$day) {
    $day['hours'] = $shortDays[$date]['hours'] ?? 8;
}
unset($day);

/*$holidays = [
    '2026-01-01' => [
        'title' => 'Новогодние каникулы',
    ],
    '2026-01-02' => [
        'title' => 'Новогодние каникулы',
    ],
    '2026-01-07' => [
        'title' => 'Рождество',
    ],
    '2026-02-23' => [
        'title' => 'День защитника Отечества',
    ],
    '2026-03-08' => [
        'title' => 'Международный женский день',
    ],
    '2026-05-01' => [
        'title' => 'Праздник Весны и Труда',
    ],
    '2026-05-09' => [
        'title' => 'День Победы',
    ],
    '2026-06-12' => [
        'title' => 'День России',
    ],
    '2026-11-04' => [
        'title' => 'День народного единства',
    ],
];*/

/*$movedWorkdays = [
    '2026-02-21' => [
        'title' => 'Рабочий день за перенос',
        'hours' => 8,
    ],
];*/
$holidays = array_merge_recursive($holidays, $transferredHolidays);
pretty_print($holidays, '$holidays');
pretty_print($movedWorkdays, 'Рабочие выходные');
pretty_print($transferredHolidays, 'Перенесённые выходные');
pretty_print($shortDays, 'Сокращённые рабочие дни');

$APPLICATION->IncludeComponent(
    'lab:production.calendar',
    '',
    [
        'YEAR' => 2027,
        'WEEK_HOURS' => 40,

        'MIN_DATE' => date('Y-m-d'),
        'MAX_DATE' => '2027-12-31',

        'HOLIDAYS' => $holidays,
        'MOVED_WORKDAYS' => $movedWorkdays,

        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 3600,
    ]
);
?>



<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");