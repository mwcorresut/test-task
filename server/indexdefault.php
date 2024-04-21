<?php
// Функция для чтения данных из json файлов
function readJsonFile($filename) {
    $json = file_get_contents($filename);
    return json_decode($json, true);
}

// Чтение данных из файлов data_cars.json и data_attempts.json
function readData() {
    $data_cars = readJsonFile('../data/data_cars.json');
    $data_attempts = readJsonFile('../data/data_attempts.json');

    return [
        'cars' => $data_cars,
        'attempts' => $data_attempts
    ];
}

// мерджим по id (как сохранить ключи?)
function mergeData($cars, $attempts) {
    $result = [];

    foreach($cars as $key => $car) {
        $carAttempts = array_filter($attempts, function($attempt) use($car) {return $attempt['id'] == $car['id'];} ); // фильтруем по машине (по сути массив из 4 попыток с полями id и result) 
        $result[$key] = $car; // добавляем машину
        $result[$key]['attempts'] = array_map(function($attempt) { 
            return $attempt['result'];
        }, array_values($carAttempts)); // тут мы получаем просто массив из 4 попыток по машине
        $result[$key]['sum'] = array_sum($result[$key]['attempts']); // суммируем все попытки
    }

    return $result;
}

function sortBySum ($data) {
    usort($data, function($a, $b) {
        return $b['sum'] <=> $a['sum'];
    });
    
    return $data;
}

function setPlaces($data) {
    for ($i = 0; $i < count($data); $i++) {
        $data[$i]['place'] = $i + 1;
    }
    
    return $data;
}

header('Content-Type: application/json; charset=utf-8');
$data = readData(); // возвращаем массив данных
$mergedData = mergeData($data['cars'], $data['attempts']);
$sortedData = sortBySum($mergedData);
$setedPlaces = setPlaces($sortedData);
echo(json_encode($setedPlaces));
?>