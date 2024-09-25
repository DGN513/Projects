<?php
// Ваш API-ключ
$apiKey = "652fcbad5fbe0c4a4a9eab1bcb827587";

// ID города Уфа (у OpenWeatherMap у каждого города есть свой ID)
$cityId = "479561"; // ID для Уфы

// Формируем URL для запроса к API OpenWeatherMap
$url = "http://api.openweathermap.org/data/2.5/weather?id={$cityId}&units=metric&lang=ru&appid={$apiKey}";

// Инициализация cURL
$ch = curl_init();

// Настройки cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true); // Вернет ошибку при 4xx/5xx ответах

// Выполнение запроса
$response = curl_exec($ch);

// Обработка ошибок
if ($response === false) {
    $error = curl_error($ch);
    echo "Ошибка при запросе: $error";
} else {
    // Выводим полученные данные
    echo $response;
}

// Закрываем cURL
curl_close($ch);
?>