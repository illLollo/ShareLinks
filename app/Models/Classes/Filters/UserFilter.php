<?php

namespace App\Models\Classes\Filters;

use DateTime;

    class UserFilter {
        public ?int $userId;
        public ?string $username;
        public ?string $name;
        public ?string $surname;
        public ?DateTime $birthDate;
        public ?bool $active;
        public ?string $password;
        public ?string $fiscalCode;
        public ?DateTime $lastUpdate;
        public ?string $email;

        public function __construct(?int $userId, ?string $username, ?string $name, ?string $surname, ?DateTime $birthDate, ?bool $active, ?string $password, ?string $fiscalCode, ?DateTime $lastUpdate, ?string $email) {
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