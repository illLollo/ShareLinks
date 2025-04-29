<?php

namespace App\Models\Services;

use CodeIgniter\Model;

class DriverService extends Model
{
    protected $table = 't_driver';
    protected $primaryKey = 'driverId';
    protected $allowedFields = ['userId', 'driverLicenseId', 'active'];

    public function count(array $filters = []): int
    {
        return $this->where($filters)->countAllResults();
    }

    public function get(array $filters = []): ?object
    {
        $result = $this->where($filters)->first();
        return $result ? (object) $result : null;
    }

}
