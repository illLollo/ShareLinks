<?php

namespace App\Models\Services;

use CodeIgniter\Model;

class AccessesService extends Model
{
    protected $table = 't_accesses';
    protected $primaryKey = 'accessId';
    protected $allowedFields = ['userId', 'token', 'loginDate', 'expiryDate', 'active'];

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
