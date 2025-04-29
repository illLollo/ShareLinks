<?php

namespace App\Models\Classes\Filters;

use DateTime;

    class CarFilter {
        public ?int $carId;
        public ?string $name;
        public ?string $plateNumber;
        public ?bool $active;
        public ?DateTime $productionDate;
        public ?string $model;
        public ?float $euroPerKilometer;
        public ?float $co2PerKilometer;
        public ?DateTime $lastUpdate;
        public ?int $driverId;

        public function __construct(?int $carId, ?string $name, ?string $plateNumber, ?bool $active, ?DateTime $productionDate, ?string $model, ?float $euroPerKilometer, ?float $co2PerKilometer, ?DateTime $lastUpdate, ?int $driverId) {
        	$this->carId = $carId;
        	$this->name = $name;
        	$this->plateNumber = $plateNumber;
        	$this->active = $active;
        	$this->productionDate = $productionDate;
        	$this->model = $model;
        	$this->euroPerKilometer = $euroPerKilometer;
        	$this->co2PerKilometer = $co2PerKilometer;
        	$this->lastUpdate = $lastUpdate;
        	$this->driverId = $driverId;

        }
        public function getSetArray() {
            return array_reduce(array_keys(get_object_vars($this)), function ($carry, $key) {
                $value = $this->$key;

                if (!is_null($value)) {
                    $carry[$key] = ($value instanceof DateTime) ? $value->format('Y-m-d H:i:s') : $value;
                }

                return $carry;
            }, []);
        }
    }