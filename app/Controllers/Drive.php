<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;

class Drive extends BaseController
{
    public function index(): string
    {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user, false);

        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view("drive", ['user' => $user, 'driver' => $driver]);
    }
    public function selectCar() {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user, false);
    }

}
