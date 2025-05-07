<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\ApplicationConstants;
use App\Models\Services\RequestService;
use App\Models\Services\TripService;
use Config\Database;

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
    public function endTrip() {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user, true);

        $tripId = $this->request->getPost("tripId") ?? null;

        if (!$tripId) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_PARAMETERS);
            return redirect()->back();
        }

        //mi accerto che non ci siano passeggeri altrimenti il viaggio non finsice
        $trip = model(TripService::class)->get(["tripId" => $tripId]);

        if (!$trip) {
            return $this->response->setJSON(["error" => "Invalid trip id"])->setStatusCode(400);
        }
        if ($trip["passengersOnBoard"] > 0) {
            return $this->response->setJSON(["error" => "There are passengers on the trip"])->setStatusCode(400);
        }
        //uso db perche php non mi permette di fare bulk update
        //rifiuto coloro che stanno aspettando il viaggio
        $db = Database::connect();
        $db->transBegin();

        //rifiuto automaticamente tutte le richieste
        $resultRequest = model(RequestService::class)->where(["tripId" => $tripId, "status" => "PENDING"])->update(null, ["status" => 'REJECTED']);
        if (!$resultRequest) {
            $db->transRollback();
            return $this->response->setJSON(["error" => "Error in updating requests"])->setStatusCode(400);
        }

        $resultWaiting = model(TripService::class)->tableTripUser()->set(["userStatus" => "REJECTED"])->where(["tripId" => $tripId, "userStatus" => "WAITING"])->update();
        if (!$resultWaiting) {
            $db->transRollback();
            return $this->response->setJSON(["error" => "Error in updating people waiting the trip"])->setStatusCode(400);
        }

        $tripStatus = $db->table("t_trip")->set(["status" => "ENDED"])->where(['tripId' => $tripId, "status" => "STARTED"])->update();

        if (!$tripStatus) {
            $db->transRollback();
            return $this->response->setJSON(["error" => "Error in updating trip status"])->setStatusCode(400);
        }

        $db->transCommit();

        session()->setFlashdata("toastMessage", ApplicationConstants::$TRIP_TERMINATED);
        return redirect()->to("/homepage/");
    }

}
