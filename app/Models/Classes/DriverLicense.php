<?php

namespace App\Models\Classes;

use DateTime;

    class DriverLicense {
        private ?int $driverLicenseId;
        private DateTime $emissionDate;
        private DateTime $expiryDate;
        private string $code;
        private string $type;
        private ?bool $active;
        private ?DateTime $lastUpdate;

        public function __construct(?int $driverLicenseId, DateTime $emissionDate, DateTime $expiryDate, string $code, string $type, ?bool $active, ?DateTime $lastUpdate) {
        	$this->driverLicenseId = $driverLicenseId;
        	$this->emissionDate = $emissionDate;
        	$this->expiryDate = $expiryDate;
        	$this->code = $code;
        	$this->type = $type;
        	$this->active = $active;
        	$this->lastUpdate = $lastUpdate;

        }

        /**
         * Get the value of driverLicenseId
         */
        public function getDriverLicenseId()
        {
                return $this->driverLicenseId;
        }

        /**
         * Get the value of emissionDate
         */
        public function getEmissionDate()
        {
                return $this->emissionDate;
        }

        /**
         * Get the value of expiryDate
         */
        public function getExpiryDate()
        {
                return $this->expiryDate;
        }

        /**
         * Get the value of code
         */
        public function getCode()
        {
                return $this->code;
        }

        /**
         * Get the value of type
         */
        public function getType()
        {
                return $this->type;
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