<?php

namespace App\Models\Services;

use CodeIgniter\Model;

class RequestService extends Model
{
    protected $table = 't_request';
    protected $primaryKey = 'requestId';
    protected $allowedFields = ['requestId', 'tripId', 'userId', 'date', 'enterLatitude', "enterLongitude", 'exitLatitude', 'active', 'status'];

    public function count(array $filters = []): int
    {
        return $this->where($filters)->countAllResults();
    }
}
