<?php

namespace App\Models\Services;

use CodeIgniter\Model;

class CarService extends Model
{
    protected $table = 't_car';
    protected $primaryKey = 'carId';
    protected $allowedFields = ['name', 'plateNumber', 'productionDate', 'model', 'euroPerKilometer', 'co2PerKilometer', 'driverId', 'active'];

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
