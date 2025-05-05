<?php

namespace App\Controllers;
use App\Models\Services\AccessesService;
use App\Models\Services\CarService;
use App\Models\Services\DriverService;
use App\Models\Services\TripService;
use App\Models\Services\UserService;

class Api extends BaseController {
    public function getTripsNearUser()
    {
        $data = json_decode(file_get_contents('php://input'), true);

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
            ->select('t_step.*')->where('t_step.active', true);

        $steps = $query->get()->getResultArray();

        $tripMatches = [];

        foreach ($steps as $step) {
            $points = $this->decodePolyline($step['polyline']);
            if (empty($points)) continue;

            $userPoint = ['lat' => $userLat, 'lng' => $userLng];
            $distToUser = $this->pointToLineDistance($userPoint, $points);

            if ($distToUser <= 200) {
                $trip = $tripService->get(['tripId' => $step['tripId'], 'active' => true, 'status' => 'STARTED']);
                $tripMatches[] = $trip;
            }
        }

        $matchingTrips = array_unique($tripMatches);
        return $this->response->setJSON($matchingTrips);
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
