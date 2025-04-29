<?php

namespace App\Models\Classes;

use DateTime;

    class Driver {
        private ?int $driverId;
        private int $userId;
        private int $driverLicenseId;
        private ?bool $active;
        private ?DateTime $lastUpdate;


        public function __construct(?int $driverId, int $userId, int $driverLicenseId, bool $active, ?DateTime $lastUpdate) {
        	$this->driverId = $driverId;
        	$this->userId = $userId;
        	$this->driverLicenseId = $driverLicenseId;
        	$this->active = $active;
        	$this->lastUpdate = $lastUpdate;

        }

        /**
         * Get the value of driverId
         */
        public function getDriverId()
        {
                return $this->driverId;
        }

        /**
         * Get the value of userId
         */
        public function getUserId()
        {
                return $this->userId;
        }

        /**
         * Get the value of driverLicenseId
         */
        public function getDriverLicenseId()
        {
                return $this->driverLicenseId;
        }

        /**
         * Get the value of active
         */
        public function getActive()
        {
                return $this->active;
        }

        /**
         * Get the value of lastUpdate
         */
        public function getLastUpdate()
        {
                return $this->lastUpdate;
        }
    }