<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$apiKey = "652fcbad5fbe0c4a4a9eab1bcb827587";
$cityId = "479561"; 
$url = "http://api.openweathermap.org/data/2.5/weather?id={$cityId}&units=metric&lang=ru&appid={$apiKey}";

$response = file_get_contents($url);

if ($response === FALSE) {
    echo json_encode(["error" => "Ошибка при получении данных от API."]);
} else {
    echo $response;
}
?>