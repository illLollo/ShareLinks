<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\ApplicationConstants;
use App\Models\Services\TripService;

class Drive extends BaseController
{
    public function index()
    {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user, false);

        $activeTrip = model(TripService::class)->get(["driverId" => $driver->driverId, "active" => true, "status" => ApplicationConstants::$TRIP_STATUS["STARTED"], "actualEndTime" => null]);

        if ($activeTrip) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$TRIP_ALREADY_STARTED);
            return redirect()->to("/drive/driving/" . $activeTrip["tripId"]);
        }

        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view("drive", ['user' => $user, 'driver' => $driver]);
    }
    public function registerTrip() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            session()->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_METHOD);
            return redirect()->back();
        }

        $tripId = model(TripService::class)->insert($_POST);

        if ($tripId) {
            return redirect()->to("/drive/driving/$tripId" );
        }

        session()->setFlashdata("toastMessage", ApplicationConstants::$TRIP_REGISTRATION_ERROR);
        return redirect()->back();
    }
    public function driving($tripId) {

        if (!isset($tripId)) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_PARAMETERS);
            return redirect()->back();
        }
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user, true);

        $trip = model(TripService::class)->get([
            'tripId' => $tripId,
            'active' => true,
            "driverId" => $driver->driverId,
            'status' => ApplicationConstants::$TRIP_STATUS["STARTED"],
            'actualEndTime' => null,
            ]);

        if (!$trip) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$TRIP_NOT_FOUND);
            return redirect()->back();
        }

        $analytics = model(TripService::class)->getAnalytics(["tripId" => $trip["tripId"], "active" => true]);

        echo view("header", ['user' => $user, 'driver' => $driver]);
        echo view('showTrip', ["trip" => (object) $trip, 'user' => $user, 'driver' => $driver, "analytics" => $analytics]);
    }

}
