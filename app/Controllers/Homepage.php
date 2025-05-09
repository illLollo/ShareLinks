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

        $request = model(RequestService::class)->select("t_request.*")->where([
            'userId' => $user->userId,
            'active' => true,
        ])->orderBy("date", "DESC")->first();

        if ($request) {
            $trip = model(TripService::class)->get(["tripId" => $request["tripId"], 'STATUS' => ApplicationConstants::$TRIP_STATUS["ENDED"]]);
            $tripUserInfoArr = model(TripService::class)->getPassengersForTrip(["tripId" => $trip["tripId"], "userId" => $user->userId, "active" => true]);

            if ($request["status"] === "ACCEPTED" && count($tripUserInfoArr) > 0 && $trip && $tripUserInfoArr[0]["userStatus"] != "GOT OFF") {
                return redirect()->to("/homepage/onBoard/" . $request["tripId"]);
            }
            if ($request["status"] === "PENDING") {
                return redirect()->to("/homepage/waiting");
            }

        }
        if ($driver) {
            $activeTrip = model(TripService::class)->get(["driverId" => $driver->driverId, "active" => true, "status" => ApplicationConstants::$TRIP_STATUS["STARTED"], "actualEndTime" => null]);

            if ($activeTrip) {
                session()->setFlashdata("toastMessage", ApplicationConstants::$TRIP_ALREADY_STARTED);
                return redirect()->to("/drive/driving/" . $activeTrip["tripId"]);
            }
        }

        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view("homepage", ['user' => $user, 'driver' => $driver]);
    }
    public function registerOnBoard() {

        $user = AuthHelper::getAuthenticatedUser();

        $count = model(RequestService::class)->where([
            "userId" => $user->userId,
            'tripId' => $_POST["tripId"],
            'active' => true,
        ])->countAllResults();

        if ($count > 0) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$ALREADY_REGISTERED);
            return redirect()->to("/homepage/");
        }
        $result = model(RequestService::class)->insert($_POST);

        if ($result) {
            return redirect()->to("/homepage/waiting/");
        } else {
            session()->setFlashdata("toastMessage", ApplicationConstants::$CREATE_FAILED);
            return redirect()->to("/homepage/");
        }
    }
    public function onBoard($tripId) {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver();

        $trip = model(TripService::class)->get(["tripId" => $tripId, "status" => "STARTED"]);

        if (!$trip) {
            return redirect()->to("/homepage/");
        }

        $tripUserInfo = model(TripService::class)->getPassengersForTrip(["userId" => $user->userId]);

        if (!$tripUserInfo) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$CANNOT_VIEW_PAGE);
            return redirect()->to("/homepage/");
        }


        if ($tripUserInfo[0]["userStatus"] == "GOT OFF") {
            session()->setFlashdata("toastMessage", ApplicationConstants::$ALREADY_GOT_OFF);
            return redirect()->to("/homepage/");
        }

        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view("userTripView", ['user' => $user, 'driver' => $driver, "trip" => $trip, "tripUserInfo" => $tripUserInfo[0]]);
    }
    public function cancelRequest() {
        $user = AuthHelper::getAuthenticatedUser();

        $request = model(RequestService::class)->select("t_request.*")->where([
            'userId' => $user->userId,
            'active' => true,
            'status' => "PENDING",
        ])->first();

        $result = model(RequestService::class)->update($request["requestId"], ["active" => false]);

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

}
