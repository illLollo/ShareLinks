<?php

namespace App\Models\Classes\Filters;

use DateTime;

    class DriverLicenseFilter {
        public ?int $driverLicenseId;
        public ?DateTime $emissionDate;
        public ?DateTime $expiryDate;
        public ?string $code;
        public ?string $type;
        public ?bool $active;
        public ?DateTime $lastUpdate;

        public function __construct(?int $driverLicenseId, ?DateTime $emissionDate, ?DateTime $expiryDate, ?string $code, ?string $type, ?bool $active, ?DateTime $lastUpdate) {
            $this->driverLicenseId = $driverLicenseId;
            $this->emissionDate = $emissionDate;
            $this->expiryDate = $expiryDate;
            $this->code = $code;
            $this->type = $type;
            $this->active = $active;
            $this->lastUpdate = $lastUpdate;
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