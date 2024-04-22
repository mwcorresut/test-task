<?php

class DataProvider
{
    protected $data;

    //  Конструктор класса, запускается в момент создания объекта.
    public function __construct(string $dataPath)
    {
        $cars = $this->readJsonFile($dataPath . "/data_cars.json");
        $attempts = $this->readJsonFile($dataPath . "/data_attempts.json");

        $this->data = $this->mergeData($cars, $attempts);

        usort($this->data, function ($a, $b) {
            return $b["sum"] <=> $a["sum"];
        });

        for ($i = 0; $i < count($this->data); $i++) {
            $this->data[$i]["place"] = $i + 1;
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    private function readJsonFile(string $filename): array
    {
        $json = file_get_contents($filename);
        $data = json_decode($json, true);

        if ($data === null) {
            throw new Exception("Не удалось прочитать файл JSON: $filename");
        }

        return $data;
    }

    private function mergeData(array $cars, array $attempts): array
    {
        $result = [];

        foreach ($cars as $key => $car) {
            $carAttempts = array_filter($attempts, function ($attempt) use (
                $car
            ) {
                return $attempt["id"] == $car["id"];
            });
            $result[$key] = $car;
            $result[$key]["attempts"] = array_map(function ($attempt) {
                return $attempt["result"];
            }, array_values($carAttempts));
            $result[$key]["sum"] = array_sum($result[$key]["attempts"]);
        }

        return $result;
    }
}

header("Content-Type: application/json; charset=utf-8");
$dataProvider = new DataProvider("../data");
echo json_encode($dataProvider->getData());