<?php

namespace App\Models\Classes\Filters;

use DateTime;

    class DriverFilter {
        public ?int $driverId;
        public ?int $userId;
        public ?int $driverLicenseId;
        public ?bool $active;
        public ?DateTime $lastUpdate;
        public ?int $numRecords;
        public ?int $offset;

        public function __construct(?int $driverId, ?int $userId, ?int $driverLicenseId, ?bool $active, ?DateTime $lastUpdate, ?int $numRecords, ?int $offset) {
            $this->driverId = $driverId;
            $this->userId = $userId;
            $this->driverLicenseId = $driverLicenseId;
            $this->active = $active;
            $this->lastUpdate = $lastUpdate;
            $this->numRecords = $numRecords;
            $this->offset = $offset;
        }

        public function getSetArray() {
            return array_filter(get_object_vars($this), function ($value) {
                return !is_null($value);
            });
        }

    }