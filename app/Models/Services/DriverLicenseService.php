<?php

namespace App\Models\Services;

use CodeIgniter\Model;

class DriverLicenseService extends Model
{
    protected $table = 't_driverlicense';
    protected $primaryKey = 'driverLicenseId';
    protected $allowedFields = ['emissionDate', 'expiryDate', 'code', 'type', 'active'];

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
