<?php

namespace App\Controllers;
use App\Models\Services\TripService;

class Api extends BaseController {
    public function getTripsOnRoute()
    {
        $startLat = $this->request->getPost('startLat');
        $startLng = $this->request->getPost('startLng');
        $endLat = $this->request->getPost('endLat');
        $endLng = $this->request->getPost('endLng');

        if (!$startLat || !$startLng || !$endLat || !$endLng) {
            return $this->response->setJSON([
                'error' => 'Invalid input. Please provide start and end coordinates.'
            ])->setStatusCode(400);
        }

        $tripService = model(TripService::class);

        // Retrieve all trips with their polylines
        $trips = $tripService->table()->select('t_trip.*, t_step.*')->get()->getResultArray();

        $matchingTrips = [];
        foreach ($trips as $trip) {
            $polyline = $trip['polyline'];

            // Check if the start and end points are on the route
            $startPoint = ['lat' => $startLat, 'lng' => $startLng];
            $endPoint = ['lat' => $endLat, 'lng' => $endLng];

            if ($this->isPointOnRoute($startPoint, $polyline) && $this->isPointOnRoute($endPoint, $polyline)) {
                $matchingTrips[] = $trip;
            }
        }

        return $this->response->setJSON($matchingTrips);
    }

    private function isPointOnRoute($point, $polyline, $tolerance = 100)
    {
        $decodedPath = $this->decodePolyline($polyline);

        foreach ($decodedPath as $pathPoint) {
            $distance = $this->haversineDistance($point, $pathPoint);
            if ($distance <= $tolerance) {
                return true;
            }
        }

        return false;
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

    private function haversineDistance($point1, $point2)
    {
        $earthRadius = 6371000; // Raggio della Terra in metri

        $lat1 = deg2rad($point1['lat']);
        $lng1 = deg2rad($point1['lng']);
        $lat2 = deg2rad($point2['lat']);
        $lng2 = deg2rad($point2['lng']);

        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distanza in metri
    }
}
