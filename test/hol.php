<?php

declare(strict_types=1);

$url = 'https://calendar.kuzyak.in/api/calendar/2027/holidays';
$outputFile = __DIR__ . '/holidays-2027.json';

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
    ],
    CURLOPT_USERAGENT => 'PHP Calendar API Client/1.0',
]);

$response = curl_exec($ch);

if ($response === false) {
    throw new RuntimeException(
        'Ошибка cURL: ' . curl_error($ch)
    );
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($httpCode < 200 || $httpCode >= 300) {
    throw new RuntimeException(
        "API вернул HTTP-код {$httpCode}"
    );
}

$data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

$json = json_encode(
    $data,
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
);

if (file_put_contents($outputFile, $json) === false) {
    throw new RuntimeException(
        "Не удалось сохранить файл {$outputFile}"
    );
}

echo "JSON сохранён: {$outputFile}\n";
