<?php

namespace App\Models\Classes\Filters;

use DateTime;

    class AccessesFilter {
        public ?int $accessId;
        public ?int $userId;
        public ?string $token;
        public ?DateTime $loginDate;
        public ?DateTime $expiryDate;
        public ?bool $active;

        public function __construct(?int $accessId, ?int $userId, ?string $token, ?DateTime $loginDate, ?DateTime $expiryDate, ?bool $active) {
            $this->accessId = $accessId;
            $this->userId = $userId;
            $this->token = $token;
            $this->loginDate = $loginDate;
            $this->expiryDate = $expiryDate;
            $this->active = $active;

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