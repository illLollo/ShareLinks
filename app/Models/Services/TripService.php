<?php

namespace App\Models\Services;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\View\Table;

class TripService
{
    public array $tripAllowedFields = ['tripId', 'carId', 'driverId', 'startTime', 'estimatedEndTime', 'actualEndTime', "status", "active"];
    public array $stepAllowedFields = ['stepId', 'tripId', 'latitude', 'longitude', 'ordinal', 'active'];

    public function get(array $filters = []): array
    {
        $db = \Config\Database::connect();

        // Query to fetch trip details
        $tripQuery = $db->table('t_trip');

        $fields = $this->filterTripFields($filters);

        foreach ($fields as $field => $value) {
            $tripQuery = $tripQuery->where("t_trip.$field", $value);
        }

        $trip = $tripQuery->get()->getRowArray();

        if (!$trip) {
            return []; // Trip not found
        }

        $stepsQuery = $db->table('t_step')
        ->select('t_step.stepId, t_step.latitude, t_step.longitude, t_step.polyline, t_step.ordinal')
        ->where('t_step.tripId', $trip['tripId'])
        ->where('t_step.active', true)
        ->orderBy('t_step.ordinal', 'ASC')
        ->get();

        $steps = $stepsQuery->getResultArray();

        // Combine trip and steps data
        $trip['steps'] = $steps;

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
        return $db->table('t_trip')
            ->join('t_step', 't_step.tripId = t_trip.tripId');
    }

    public function insert(array $row): bool
    {
        $db = \Config\Database::connect();

        $db->transBegin();

        $tripData = $this->filterTripFields($row);

        $insertTrip = $db->table('t_trip')->insert($tripData);

        if (!$insertTrip) {
            $db->transRollback();
            return false;
        }


        $insertSteps = $db->table('t_step')->insertBatch($row['steps']);

        if (!$insertSteps) {
            $db->transRollback();
        }

        $db->transCommit();

        return $insertTrip && $insertSteps;
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
        return array_filter($input, function($item) {
            return in_array($item, $this->stepAllowedFields);
        });
    }
    private function filterStepFields(array $input): array
    {
        return array_filter($input, function($item) {
            return in_array($item, $this->stepAllowedFields);
        });
    }
}
