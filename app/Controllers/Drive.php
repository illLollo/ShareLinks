<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Services\TripService;

class Drive extends BaseController
{
    public function index(): string
    {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user, false);

        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view("drive", ['user' => $user, 'driver' => $driver]);
    }
    public function driving() {

        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user, true);
        $result = model(TripService::class)->insert($_POST);


        //fare pagina monitoaggio del viaggio
    }

}
