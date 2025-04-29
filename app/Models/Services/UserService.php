<?php

namespace App\Models\Services;

use CodeIgniter\Model;

class UserService extends Model
{
    protected $table = 't_user';
    protected $primaryKey = 'userId';
    protected $allowedFields = ['username', 'name', 'surname', 'birthDate', 'password', 'fiscalCode', 'email', 'active'];

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
