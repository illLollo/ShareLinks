<?php

namespace App\Models\Application;

use CodeIgniter\Database\BaseResult;
use CodeIgniter\Database\Query;
use CodeIgniter\Model;

class Data extends Model {
    private static $instance = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = db_connect();
        }
        return self::$instance;
    }
}
