<?php

namespace App\Models\Classes;

use DateTime;

    class Car {
        private ?int $carId;
        private string $name;
        private string $plateNumber;
        private ?bool $active;
        private DateTime $productionDate;
        private string $model;
        private float $euroPerKilometer;
        private float $co2PerKilometer;
        private ?DateTime $lastUpdate;
        private int $driverId;

        public function __construct(?int $carId, string $name, string $plateNumber, ?bool $active, DateTime $productionDate, string $model, float $euroPerKilometer, float $co2PerKilometer, ?DateTime $lastUpdate, int $driverId) {
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

        /**
         * Get the value of carId
         */
        public function getCarId()
        {
                return $this->carId;
        }

        /**
         * Get the value of name
         */
        public function getName()
        {
                return $this->name;
        }

        /**
         * Get the value of plateNumber
         */
        public function getPlateNumber()
        {
                return $this->plateNumber;
        }

        /**
         * Get the value of active
         */
        public function getActive()
        {
                return $this->active;
        }

        /**
         * Get the value of productionDate
         */
        public function getProductionDate()
        {
                return $this->productionDate;
        }

        /**
         * Get the value of model
         */
        public function getModel()
        {
                return $this->model;
        }

        /**
         * Get the value of euroPerKilometer
         */
        public function getEuroPerKilometer()
        {
                return $this->euroPerKilometer;
        }

        /**
         * Get the value of co2PerKilometer
         */
        public function getCo2PerKilometer()
        {
                return $this->co2PerKilometer;
        }

        /**
         * Get the value of lastUpdate
         */
        public function getLastUpdate()
        {
                return $this->lastUpdate;
        }

        /**
         * Get the value of driverId
         */
        public function getDriverId()
        {
                return $this->driverId;
        }
    }