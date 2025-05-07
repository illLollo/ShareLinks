<?php

namespace App\Controllers;
use App\Models\Services\AccessesService;
use App\Models\Services\CarService;
use App\Models\Services\DriverService;
use App\Models\Services\RequestService;
use App\Models\Services\TripService;
use Config\Database;

class Api extends BaseController {
    public function getTripsNearUser()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['lat']) || !isset($data['lng'])) {
            return $this->response->setJSON([
                'error' => 'Invalid input. Please provide user coordinates.'
            ])->setStatusCode(400);
        }
        $userLat = (float)$data["lat"];
        $userLng = (float)$data['lng'];
        $token = $data['token'] ?? null;

        $userId = model(AccessesService::class)
            ->select("t_accesses.userId")
            ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
            ->orderBy("loginDate", "DESC")
            ->first();

        if (!$userId) {
            return $this->response->setJSON([
                'error' => 'Invalid token.'
            ])->setStatusCode(401);
        }

        if (!$userLat || !$userLng) {
            return $this->response->setJSON([
                'error' => 'Invalid input. Please provide user coordinates.'
            ])->setStatusCode(400);
        }

        $tripService = model(TripService::class);

        $query = $tripService->table()
            ->distinct()->select('t.tripId, polyline')->where(['t.active' => true, "t.remainingSlots >" => 0]);

        $pairs = $query->get()->getResultArray();

        $tripMatches = [];

        foreach ($pairs as $pair) {
            $points = $this->decodePolyline($pair['polyline']);
            if (empty($points)) continue;

            $userPoint = ['lat' => $userLat, 'lng' => $userLng];
            $distToUser = $this->pointToLineDistance($userPoint, $points);

            if ($distToUser <= 1000) { // 1 km
                $trip = $tripService->get(['tripId' => $pair['tripId'], 'active' => true, 'status' => 'STARTED', 'remainingSlots >' => 0]);
                $tripMatches[] = $trip;
            }
        }

        return $this->response->setJSON($tripMatches);
    }
    public function getCars() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'] ?? null;

        $userId = model(AccessesService::class)
            ->select("t_accesses.userId")
            ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
            ->orderBy("loginDate", "DESC")
            ->first();

        if (!$userId) {
            return $this->response->setJSON([
                'error' => 'Invalid token.'
            ])->setStatusCode(401);
        }

        $driver = model(DriverService::class)->get(['userId' => $userId, 'active' => true]);

        if (!$driver) {
            return $this->response->setJSON([
                'error' => 'Driver not found.'
            ])->setStatusCode(404);
        }
        $cars = model(CarService::class)->select('t_car.*')
            ->where(['driverId' => $driver->driverId, 'active' => true])->findAll();

        return $this->response->setJSON($cars);
    }
    public function acceptPassenger() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'] ?? null;
        $requestId = $data['requestId'] ?? null;
        $tripId = $data['tripId'] ?? null;

        $userId = model(AccessesService::class)
            ->select("t_accesses.userId")
            ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
            ->orderBy("loginDate", "DESC")
            ->first();

        if (!$userId) {
            return $this->response->setJSON([
                'error' => 'Invalid token.'
            ])->setStatusCode(401);
        }

        if (!$requestId) {
            return $this->response->setJSON([
                'error' => 'Invalid input. Please provide request ID.'
            ])->setStatusCode(400);
        }

        $request = model(RequestService::class)->where(['requestId' => $requestId, 'active' => true, 'status' => 'PENDING'])->first();
        $driver = model(DriverService::class)->get(['userId' => $userId, 'active' => true]);
        $trip = model(TripService::class)->get(['tripId' => $tripId, 'active' => true, 'status' => 'STARTED', 'remainingSlots >' => 0, "driverId" => $driver->driverId]);

        if (!$trip) {
            return $this->response->setJSON([
                'error' => 'Trip not found or no remaining slots.'
            ])->setStatusCode(404);
        }

        if (!$request) {
            return $this->response->setJSON([
                'error' => 'Richiesta non in attesa'
            ])->setStatusCode(404);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $updateRequest = model(RequestService::class)->update($requestId, ['status' => 'ACCEPTED']);
        $insertPassenger = model(TripService::class)->registerPassenger($tripId, ['userId' => $request["userId"], 'active' => true, 'status' => 'STARTED', "enterLatitude" => $request["enterLatitude"], "enterLongitude" => $request["enterLongitude"]]);

        if ($updateRequest && $insertPassenger) {
            $db->transCommit();
            return $this->response->setJSON(true)->setStatusCode(200);
        } else {
            $db->transRollback();
            return $this->response->setJSON([
                'error' => 'Failed to accept passenger.'
            ])->setStatusCode(500);
        }
    }
    public function rejectPassenger() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'] ?? null;
        $requestId = $data['requestId'] ?? null;
        $tripId = $data['tripId'] ?? null;

        $userId = model(AccessesService::class)
            ->select("t_accesses.userId")
            ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
            ->orderBy("loginDate", "DESC")
            ->first();

        if (!$userId) {
            return $this->response->setJSON([
                'error' => 'Invalid token.'
            ])->setStatusCode(401);
        }

        if (!$requestId) {
            return $this->response->setJSON([
                'error' => 'Invalid input. Please provide request ID.'
            ])->setStatusCode(400);
        }

        $request = model(RequestService::class)->where(['requestId' => $requestId, 'active' => true, 'status' => 'PENDING'])->first();
        $driver = model(DriverService::class)->get(['userId' => $userId, 'active' => true]);
        $trip = model(TripService::class)->get(['tripId' => $tripId, 'active' => true, 'status' => 'STARTED', 'remainingSlots >' => 0, "driverId" => $driver->driverId]);

        if (!$trip) {
            return $this->response->setJSON([
                'error' => 'Trip not found or no remaining slots.'
            ])->setStatusCode(404);
        }

        if (!$request) {
            return $this->response->setJSON([
                'error' => 'Richiesta non in attesa'
            ])->setStatusCode(404);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $updateRequest = model(RequestService::class)->update($requestId, ['status' => 'REJECTED']);

        if ($updateRequest) {
            $db->transCommit();
            return $this->response->setJSON(true)->setStatusCode(200);
        } else {
            $db->transRollback();
            return $this->response->setJSON([
                'error' => 'Failed to reject passenger.'
            ])->setStatusCode(500);
        }
    }
    public function setOnBoard() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'];
        $tripId = $data['tripId'] ?? null;

        $userId = model(AccessesService::class)
            ->select("t_accesses.userId")
            ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
            ->orderBy("loginDate", "DESC")
            ->first();

        if (!$userId) {
            return $this->response->setJSON(["error" => "Invalid token"])->setStatusCode(400);
        }


        $response = model(TripService::class)->changePassengerState($tripId, (int) $userId, "ON BOARD");

        if ($response) {
            return $this->response->setJSON(true)->setStatusCode(200);
        } else {
            return $this->response->setJSON([
                'error' => 'Failed to change passenger status.'
            ])->setStatusCode(500);
        }
    }
    public function leaveTrip() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'];
        $tripId = $data['tripId'] ?? null;

        $userId = model(AccessesService::class)
            ->select("t_accesses.userId")
            ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
            ->orderBy("loginDate", "DESC")
            ->first();

        if (!$userId) {
            return $this->response->setJSON(["error" => "Invalid token"])->setStatusCode(400);
        }


        $response = model(TripService::class)->changePassengerState($tripId, (int) $userId, "LEFT");

        if ($response) {
            return $this->response->setJSON(true)->setStatusCode(200);
        } else {
            return $this->response->setJSON([
                'error' => 'Failed to change passenger status.'
            ])->setStatusCode(500);
        }

    }
    public function getOffTrip() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'];
        $tripId = $data['tripId'] ?? null;
        $latitude = $data['latitude'] ?? null;
        $longitude = $data['longitude'] ?? null;
        $polyline = $data['polyline'] ?? null;

        $userId = model(AccessesService::class)
            ->select("t_accesses.userId")
            ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
            ->orderBy("loginDate", "DESC")
            ->first();

        if (!$userId) {
            return $this->response->setJSON(["error" => "Invalid token"])->setStatusCode(400);
        }

        $db = \Config\Database::connect();

        $db->transStart();

        $response = model(TripService::class)->changePassengerState($tripId, (int) $userId, "GOT OFF");
        $setOff = model(TripService::class)->updateUserInTrip(["userId" => $userId, "tripId" => $tripId], ["exitLatitude" => $latitude,"exitLongitude" => $longitude, "polyline" => $polyline]);

        if ($response) {
            $db->transCommit();
            return $this->response->setJSON(true)->setStatusCode(200);
        } else {
            $db->transRollback();
            return $this->response->setJSON([
                'error' => 'Failed to change passenger status.'
            ])->setStatusCode(500);
        }

    }
    public function getRequestsForTrip() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'] ?? null;
        $tripId = $data['tripId'] ?? null;

        $userId = model(AccessesService::class)
            ->select("t_accesses.userId")
            ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
            ->orderBy("loginDate", "DESC")
            ->first();

        if (!$userId) {
            return $this->response->setJSON([
                'error' => 'Invalid token.'
            ])->setStatusCode(400);
        }

        if (!$tripId) {
            return $this->response->setJSON([
                'error' => 'Invalid input. Please provide trip ID.'
            ])->setStatusCode(400);
        }

        $requests = model(RequestService::class)->select('t_request.*, t_user.*')->join('t_user', 't_request.userId = t_user.userId')
            ->where(['tripId' => $tripId, 't_request.active' => true, 'status' => 'PENDING'])->findAll();

        return $this->response->setJSON($requests);
    }
    public function getRequest() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'] ?? null;
        $requestId = $data["requestId"] ?? null;

        $userId = model(AccessesService::class)
        ->select("t_accesses.userId")
        ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
        ->orderBy("loginDate", "DESC")
        ->first();

        if (!$userId) {
            return $this->response->setJSON([
                'error' => 'Invalid token.'
            ])->setStatusCode(401);
        }

        $request = model(RequestService::class)->select("t_request.*")->first();

        if ($request) {
            return $this->response->setJSON(true)->setStatusCode(200);
        }

        return $this->response->setJSON([
            'error' => 'Invalid request id.'
        ])->setStatusCode(401);
    }
    public function getAnalyticsForTrip() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'] ?? null;
        $tripId = $data['tripId'] ?? null;

        $userId = model(AccessesService::class)
            ->select("t_accesses.userId")
            ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
            ->orderBy("loginDate", "DESC")
            ->first();

        if (!$userId) {
            return $this->response->setJSON([
                'error' => 'Invalid token.'
            ])->setStatusCode(401);
        }

        if (!$tripId) {
            return $this->response->setJSON([
                'error' => 'Invalid input. Please provide trip ID.'
            ])->setStatusCode(400);
        }

        $analyticTrip = model(TripService::class)->getAnalytics(["tripId" => $tripId]);

        if ($analyticTrip) {
            return $this->response->setJSON($analyticTrip)->setStatusCode(200);
        }
        return $this->response->setJSON(["error" => "Invalid TripId"])->setStatusCode(200);
    }
    public function getPassengersOnBoardForTrip() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'] ?? null;
        $tripId = $data['tripId'] ?? null;

        $userId = model(AccessesService::class)
            ->select("t_accesses.userId")
            ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
            ->orderBy("loginDate", "DESC")
            ->first();

        if (!$userId) {
            return $this->response->setJSON([
                'error' => 'Invalid token.'
            ])->setStatusCode(401);
        }

        if (!$tripId) {
            return $this->response->setJSON([
                'error' => 'Invalid input. Please provide trip ID.'
            ])->setStatusCode(400);
        }

        $passengers = model(TripService::class)->getPassengersForTrip(['tripId' => $tripId, 'active' => true, 'status' => 'STARTED', "userStatus" => "ON BOARD"]);

        return $this->response->setJSON($passengers);
    }
    public function getPassengersForTrip() {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'] ?? null;
        $tripId = $data['tripId'] ?? null;

        $userId = model(AccessesService::class)
            ->select("t_accesses.userId")
            ->where(['token' => $token, 'active' => true, "expiryDate >" => date("Y-m-d H:i:s")])
            ->orderBy("loginDate", "DESC")
            ->first();

        if (!$userId) {
            return $this->response->setJSON([
                'error' => 'Invalid token.'
            ])->setStatusCode(400);
        }

        if (!$tripId) {
            return $this->response->setJSON([
                'error' => 'Invalid input. Please provide trip ID.'
            ])->setStatusCode(400);
        }

        $passengers = model(TripService::class)->getPassengersForTrip(['tripId' => $tripId, 'active' => true, 'status' => 'STARTED', "userStatus" => "WAITING"]);

        return $this->response->setJSON($passengers);
    }

    private function decodePolyline($polyline)
    {
        $points = [];
        $index = 0;
        $len = strlen($polyline);
        $lat = 0;
        $lng = 0;

        while ($index < $len) {
            $b = 0;
            $shift = 0;
            $result = 0;
            do {
                $b = ord($polyline[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lat += $dlat;

            $shift = 0;
            $result = 0;
            do {
                $b = ord($polyline[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lng += $dlng;

            $points[] = ['lat' => $lat * 1e-5, 'lng' => $lng * 1e-5];
        }

        return $points;
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in metri

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function pointToLineDistance($point, $linePoints)
    {
        $minDistance = INF;
        $count = count($linePoints);

        for ($i = 0; $i < $count - 1; $i++) {
            $a = $linePoints[$i];
            $b = $linePoints[$i + 1];
            $dist = $this->pointToSegmentDistance($point, $a, $b);
            if ($dist < $minDistance) {
                $minDistance = $dist;
            }
        }

        return $minDistance;
    }

    private function pointToSegmentDistance($p, $a, $b)
    {
        $lat = deg2rad($p['lat']);
        $lng = deg2rad($p['lng']);
        $alat = deg2rad($a['lat']);
        $alng = deg2rad($a['lng']);
        $blat = deg2rad($b['lat']);
        $blng = deg2rad($b['lng']);

        $dx = $blng - $alng;
        $dy = $blat - $alat;

        if ($dx == 0 && $dy == 0) {
            return $this->haversineDistance($p['lat'], $p['lng'], $a['lat'], $a['lng']);
        }

        $t = ((($lng - $alng) * $dx + ($lat - $alat) * $dy) / ($dx * $dx + $dy * $dy));
        $t = max(0, min(1, $t));
        $projLat = rad2deg($alat + $t * $dy);
        $projLng = rad2deg($alng + $t * $dx);

        return $this->haversineDistance($p['lat'], $p['lng'], $projLat, $projLng);
    }
}
