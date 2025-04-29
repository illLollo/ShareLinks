<?php

namespace App\Models\Classes;

use CodeIgniter\Model;
use DateTime;

    class Access {
        public ?int $accessId;
        public int $userId;
        public string $token;
        public DateTime $loginDate;
        public DateTime $expiryDate;
        public bool $active;

        public function __construct(?int $accessId, int $userId, string $token, DateTime $loginDate, DateTime $expiryDate, bool $active) {
            $this->accessId = $accessId;
            $this->userId = $userId;
            $this->token = $token;
            $this->loginDate = $loginDate;
            $this->expiryDate = $expiryDate;
            $this->active = $active;
        }

        /**
         * Get the value of accessId
         */
        public function getAccessId()
        {
                return $this->accessId;
        }

        /**
         * Get the value of userId
         */
        public function getUserId()
        {
                return $this->userId;
        }

        /**
         * Get the value of token
         */
        public function getToken()
        {
                return $this->token;
        }

        /**
         * Get the value of loginDate
         */
        public function getLoginDate()
        {
                return $this->loginDate;
        }

        /**
         * Get the value of expiryDate
         */
        public function getExpiryDate()
        {
                return $this->expiryDate;
        }

        /**
         * Get the value of active
         */
        public function getActive()
        {
                return $this->active;
        }
    }