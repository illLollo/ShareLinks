<?php

namespace App\Controllers;

use App\Models\ApplicationConstants;
use App\Models\ApplicationUtilities;
use App\Models\Services\CarService;
use App\Models\Services\DriverLicenseService;
use App\Models\Services\DriverService;
use App\Models\Services\TripService;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Helpers\AuthHelper;
use DateTime;

class Trip extends BaseController {
    public function index() {
        $val = model(TripService::class)->table()
            ->select('t_trip.*, t_step.*')
            ->where('t_trip.active', true)
            ->where("t_trip.tripId", 1)
            ->get()->getResultArray();

        echo var_dump($val[2]);
        die;
    }
}
