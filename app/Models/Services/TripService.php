<?php

namespace App\Models\Services;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Debug\Toolbar\Collectors\Database;
use CodeIgniter\View\Table;
use DateTime;

class TripService
{
    public array $tripAllowedFields = ['tripId', 'carId', 'driverId', 'startTime', 'estimatedEndTime', 'actualEndTime', "status", "active", "slots", "polyline", "time", "distance", "remainingSlots"];
    public array $stepAllowedFields = ['stepId', 'tripId', 'latitude', 'longitude', 'ordinal', 'name', 'active'];
    public array $tripUserAllowedFields = ['tripId', 'userId', 'active', 'enterLatitude', 'enterLongitude', 'exitLatitude', "exitLongitude", 'polyline', "userStatus"];

    public function get(array $filters = []): array
    {
        $db = \Config\Database::connect();

        // Query to fetch trip details
        $tripQuery = $db->table('v_trip_remaining_slots t')->join("v_trip_analytics a", "a.tripId = t.tripId");

        $fields = $this->filterTripFields($filters);

        foreach ($fields as $field => $value) {
            $tripQuery = $tripQuery->where("t.$field", $value);
        }

        $trip = $tripQuery->get()->getRowArray();

        if (!$trip) {
            return []; // Trip not found
        }

        $stepsQuery = $db->table('t_step')
        ->select('t_step.stepId, t_step.latitude, t_step.longitude, t_step.ordinal, t_step.name')
        ->where('t_step.tripId', $trip['tripId'])
        ->where('t_step.active', true)
        ->orderBy('t_step.ordinal', 'ASC')
        ->get();

        $driver = model(DriverService::class)->get(['driverId' => $trip['driverId'], 'active' => true]);
        $car = model(CarService::class)->get(['carId' => $trip['carId'], 'active' => true]);
        $steps = $stepsQuery->getResultArray();

        // Combine trip and steps data
        unset($trip['carId']);
        unset($trip['driverId']);

        $trip['steps'] = $steps;
        $trip['driver'] = model(UserService::class)->get(['userId' => $driver->userId, 'active' => true]);
        $trip['car'] = $car;

        return $trip;
    }
    public function tableTripUser() {
        $db = \Config\Database::connect();
        return $db->table("t_trip_user");
    }
    public function changePassengerState(int $tripId, int $userId, string $newStatus) {
        $db = \Config\Database::connect();
        return $db->table("t_trip_user")->update(["userStatus" => $newStatus], ["tripId" => $tripId, "userId" => $userId]);
    }
    public function registerPassenger(int $tripId, array $row): bool
    {
        $db = \Config\Database::connect();

        $tripData = $this->filterTripUserFields($row);

        $result = $db->table('t_trip_user')->insert([
            "tripId" => $tripId,
            "userId" => $tripData["userId"],
            "active" => $tripData["active"],
            "enterLatitude" => $tripData["enterLatitude"],
            "enterLongitude" => $tripData["enterLongitude"],
            "userStatus" => "WAITING"
        ]);

        return $result;
    }
    public function getAnalytics(array $filters = []): array
    {
        $db = \Config\Database::connect();

        // Query to fetch trip details
        $tripQuery = $db->table('v_trip_analytics t');

        $fields = $this->filterTripFields($filters);

        foreach ($fields as $field => $value) {
            $tripQuery = $tripQuery->where("t.$field", $value);
        }

        $trip = $tripQuery->get()->getRowArray();

        if (!$trip) {
            return []; // Trip not found
        }

        return $trip;
    }
    public function getPassengersForTrip(array $filters = []): array
    {
        $db = \Config\Database::connect();

        // Query to fetch trip details
        $tripQuery = $db->table('v_trip_remaining_slots t')->join("t_trip_user tu", "tu.tripId = t.tripId")->join("t_user u", "u.userId = tu.userId");

        $fields = $this->filterTripFields($filters);
        $userFields = $this->filterTripUserFields($filters);

        foreach ($fields as $field => $value) {
            $tripQuery = $tripQuery->where("t.$field", $value);
        }
        foreach ($userFields as $field => $value) {
            $tripQuery = $tripQuery->where("tu.$field", $value);
        }

        $trip = $tripQuery->get()->getResultArray();

        return $trip;
    }
    public function count(array $filters = []): int {
        $db = \Config\Database::connect();

        // Query to fetch trip details
        $tripQuery = $db->table('t_trip');

        $fields = $this->filterTripFields($filters);

        foreach ($fields as $field => $value) {
            $tripQuery = $tripQuery->where("t_trip.$field", $value);
        }

        $tripCount = $tripQuery->countAllResults();

        return $tripCount;
    }
    public function table(): BaseBuilder
    {
        $db = \Config\Database::connect();
        return $db->table('v_trip_remaining_slots t')
            ->join('t_step s', 's.tripId = t.tripId');
    }

    public function insert(array $row): bool
    {
        $db = \Config\Database::connect();

        $db->transBegin();

        $tripData = $this->filterTripFields($row);
        $tripData["startTime"] = (new DateTime())->format('Y-m-d H:i:s');

        $time = $tripData["time"];
        $tripData["time"] = $tripData["time"] * 60;

        $tripData["estimatedEndTime"] = (new DateTime())->modify("+{$time} minutes")->format('Y-m-d H:i:s');
        $tripData["actualEndTime"] = null;
        $tripData["status"] = "STARTED";
        $tripData["active"] = true;

        $insertTrip = $db->table('t_trip')->insert($tripData);

        if (!$insertTrip) {
            $db->transRollback();
            return false;
        }


        $tripId = $db->insertID();

        $steps = json_decode($row['steps']);

        foreach ($steps as $s) {
            $s->tripId = $tripId;
        }

        $insertSteps = $db->table('t_step')->insertBatch($steps);

        if (!$insertSteps) {
            $db->transRollback();
        }

        $db->transCommit();

        return $tripId && $insertSteps;
    }
    public function updateUserInTrip(array $filters, array $row) {
        $db = \Config\Database::connect();

        $db->transBegin();

        $tripData = $this->filterTripUserFields($row);
        $tripTable = $db->table('t_trip_user tu');

        foreach ($filters as $field => $value) {
            $tripTable->where("tu.$field", $value);
        }

        $updateTrip = $tripTable->update($tripData);

        if (!$updateTrip) {
            $db->transRollback();
        }
        else {
            $db->transCommit();
        }

        return $updateTrip;
    }

    public function update(array $filters, array $row): bool
    {
        $db = \Config\Database::connect();

        $db->transBegin();

        $tripData = $this->filterTripFields($row);
        $tripTable = $db->table('t_trip');

        foreach ($filters as $field => $value) {
            $tripTable->where("t_trip.$field", $value);
        }

        $updateTrip = $tripTable->update($tripData);

        if (!$updateTrip) {
            $db->transRollback();
            return false;
        }
        $stepFields = $this->filterStepFields($row);


        $stepRes = $db->table('t_step')->where(array_keys($stepFields))->update($stepFields);

        if (!$stepRes) {
            $db->transRollback();
        }
        $db->transCommit();

        return $updateTrip && $stepRes;
    }
    public function delete(array $filters): bool
    {
        return $this->update($filters, ['active' => false]);
    }
    private function filterTripFields(array $input): array
    {
        return array_filter($input, function($key) {
            return in_array($key, $this->tripAllowedFields);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function filterStepFields(array $input): array
    {
        return array_filter($input, function($key) {
            return in_array($key, $this->stepAllowedFields);
        }, ARRAY_FILTER_USE_KEY);
    }
    private function filterTripUserFields(array $input): array
    {
        return array_filter($input, function($key) {
            return in_array($key, $this->tripUserAllowedFields);
        }, ARRAY_FILTER_USE_KEY);
    }
}
