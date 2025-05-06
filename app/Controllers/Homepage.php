<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\ApplicationConstants;
use App\Models\ApplicationUtilities;
use App\Models\Services\RequestService;
use App\Models\Services\TripService;

class Homepage extends BaseController
{
    public function index()
    {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user, false);

        $activeTrip = model(TripService::class)->get(["driverId" => $driver->driverId, "active" => true, "status" => ApplicationConstants::$TRIP_STATUS["STARTED"], "actualEndTime" => null]);
        $request = model(RequestService::class)->select("t_request.*")->where([
            'userId' => $user->userId,
            'active' => true,
            'status' => "PENDING",
        ])->first();

        if ($request) {
            return redirect()->to("/homepage/waiting/");
        }

        if ($activeTrip) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$TRIP_ALREADY_STARTED);
            return redirect()->to("/drive/driving/" . $activeTrip["tripId"]);
        }

        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view("homepage", ['user' => $user, 'driver' => $driver]);
    }
    public function registerOnBoard() {
        $result = model(RequestService::class)->insert($_POST);

        if ($result) {
            return redirect()->to("/homepage/waiting/");
        } else {
            session()->setFlashdata("toastMessage", ApplicationConstants::$CREATE_FAILED);
            return redirect()->to("/homepage/");
        }
    }
    public function cancelRequest() {
        $user = AuthHelper::getAuthenticatedUser();

        $request = model(RequestService::class)->select("t_request.*")->where([
            'userId' => $user->userId,
            'active' => true,
            'status' => "PENDING",
        ])->first();

        $result = model(RequestService::class)->update($request->requestId, ["active" => false]);

        if ($result) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$DELETE_SUCCESSFULLY);
        } else {
            session()->setFlashdata("toastMessage", ApplicationConstants::$DELETE_FAILED);
        }

        return redirect()->to("/homepage/");
    }
    public function waiting() {

        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user, false);

        $request = model(RequestService::class)->select("t_request.*")->where([
            'userId' => $user->userId,
            'active' => true,
            'status' => "PENDING",
        ])->first();


        if (!$request) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$CANNOT_VIEW_PAGE);
            return redirect()->to("/homepage/");
        }

        $trip = model(TripService::class)->get([
            'tripId' => $request["tripId"],
            'active' => true,
            'status' => ApplicationConstants::$TRIP_STATUS["STARTED"],
            'actualEndTime' => null,
        ]);


        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view("waiting", ['user' => $user, 'driver' => $driver, "request" => $request, 'trip' => $trip]);
    }
    public function onBoard() {

    }


}
