<?php

namespace App\Models\Classes;

use CodeIgniter\Model;
use DateTime;
use ReflectionClass;

    class User {
        private ?string $userId;
        private string $username;
        private string $name;
        private string $surname;
        private DateTime $birthDate;
        private ?bool $active;
        private string $password;
        private string $fiscalCode;
        private ?DateTime $lastUpdate;
        private string $email;

        public function __construct(?string $userId, string $username, string $name, string $surname, DateTime $birthDate, ?bool $active, string $password, string $fiscalCode, ?DateTime $lastUpdate, string $email) {
        	$this->userId = $userId;
        	$this->username = $username;
        	$this->name = $name;
        	$this->surname = $surname;
        	$this->birthDate = $birthDate;
        	$this->active = $active;
        	$this->password = $password;
        	$this->fiscalCode = $fiscalCode;
        	$this->lastUpdate = $lastUpdate;
        	$this->email = $email;

        }

        /**
         * Get the value of userId
         */
        public function getUserId()
        {
                return $this->userId;
        }

        /**
         * Get the value of username
         */
        public function getUsername()
        {
                return $this->username;
        }

        /**
         * Get the value of name
         */
        public function getName()
        {
                return $this->name;
        }

        /**
         * Get the value of surname
         */
        public function getSurname()
        {
                return $this->surname;
        }

        /**
         * Get the value of birthDate
         */
        public function getBirthDate()
        {
                return $this->birthDate;
        }

        /**
         * Get the value of active
         */
        public function getActive()
        {
                return $this->active;
        }

        /**
         * Get the value of password
         */
        public function getPassword()
        {
                return $this->password;
        }

        /**
         * Get the value of fiscalCode
         */
        public function getFiscalCode()
        {
                return $this->fiscalCode;
        }

        /**
         * Get the value of lastUpdate
         */
        public function getLastUpdate()
        {
                return $this->lastUpdate;
        }

        /**
         * Get the value of email
         */
        public function getEmail()
        {
                return $this->email;
        }
    }