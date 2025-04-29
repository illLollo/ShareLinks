<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\ApplicationUtilities;

class Homepage extends BaseController
{
    public function index(): string
    {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user, false);

        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view("homepage", ['user' => $user, 'driver' => $driver]);
    }



}
