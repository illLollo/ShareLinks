<?php

namespace App\Controllers;

use App\Models\ApplicationConstants;
use App\Models\ApplicationUtilities;
use App\Models\Services\CarService;
use App\Models\Services\DriverLicenseService;
use App\Models\Services\DriverService;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Helpers\AuthHelper;
use DateTime;

class Driver extends BaseController {
    public function index() {
        $user = ApplicationUtilities::verifyAuth();

        $driver = ApplicationUtilities::verifyDriver($user);

        if (is_null($driver)) {
            return view("header", ['user' => $user, 'driver' => $driver]) . view("missingDriver");
        }

        $driverLicenseService = model(DriverLicenseService::class);
        $carService = model(CarService::class);

        $driverLicense = $driverLicenseService->get(['driverLicenseId' => $driver->driverLicenseId, 'active' => true]);
        $nCars = $carService->count(['driverId' => $driver->driverId, 'active' => true]);

        echo view("header", ["user" => $user, 'driver' => $driver]);
        return view("manageDriver", [
            'user' => $user,
            'driver' => $driver,
            'driverLicense' => $driverLicense,
            'nCars' => $nCars,
            'toastMessage' => session()->getFlashdata("toastMessage")
        ]);
    }

    public function updateDriverLicense() {
        $session = session();
        $driver = ApplicationUtilities::verifyDriver();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_METHOD);
            return redirect()->to("/driver/");
        }

        $fields = [
            "emissionDate",
            "expiryDate",
            "code",
            "type"
        ];

        foreach ($fields as $field) {
            if (empty($_POST[$field])) {
                $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_PARAMETERS);
                return redirect()->to("/driver/");
            }
        }

        $driverLicenseService = model(DriverLicenseService::class);
        $currentData = $driverLicenseService->find($driver->driverLicenseId);

        $updateData = [
            'emissionDate' => (new DateTime($_POST["emissionDate"]))->format('Y-m-d'),
            'expiryDate' => (new DateTime($_POST["expiryDate"]))->format('Y-m-d'),
            'code' => $_POST["code"],
            'type' => $_POST["type"]
        ];

        $updateData = array_filter($updateData, function ($value, $key) use ($currentData) {
            return $currentData[$key] !== $value;
        }, ARRAY_FILTER_USE_BOTH);

        if (empty($updateData)) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$NO_CHANGES_MADE);
            return redirect()->to("/driver/");
        }

        try {
            $update = $driverLicenseService->update(
                ['driverLicenseId' => $driver->driverLicenseId, 'active' => true],
                $updateData
            );

        } catch (DatabaseException $e) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$FORM_UPLOAD_FAILED);
            return redirect()->to("/driver/");
        }


        $session->setFlashdata("toastMessage", $update ? ApplicationConstants::$FORM_UPLOAD_SUCCESSFULLY : ApplicationConstants::$FORM_UPLOAD_FAILED);
        return redirect()->to("/driver/");
    }
    public function registerDriverLicense() {

        $session = session();
        $user = AuthHelper::getAuthenticatedUser();

        $driver = AuthHelper::getAuthenticatedDriver($user, false);

            if ($driver) {
                $session->setFlashdata("toastMessage", ApplicationConstants::$EXISTING_DRIVERLICENSE);
                return redirect()->to("/driver/");
            }

        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view("registerDriverLicense");
    }
    public function driverLicense() {
        $session = session();

        $fields = [
            "emissionDate",
            "expiryDate",
            "code",
            "type",
        ];

        foreach ($fields as $field) {
            if (empty($_POST[$field])) {
                $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_PARAMETERS);
                return redirect()->to("/homepage/registerDriverLicense/");
            }
        }

        $driverLicenseService = model(DriverLicenseService::class);
        $driverService = model(DriverService::class);

        $db = \Config\Database::connect();
        $db->transStart();

        $driverLicenseData = [
            'emissionDate' => (new DateTime($_POST["emissionDate"]))->format('Y-m-d'),
            'expiryDate' => (new DateTime($_POST["expiryDate"]))->format('Y-m-d'),
            'code' => $_POST["code"],
            'type' => $_POST["type"],
            'active' => true
        ];

        $driverLicenseId = $driverLicenseService->insert($driverLicenseData);

        $user = AuthHelper::getAuthenticatedUser();

        if (!$driverLicenseId) {
            $db->transRollback();
            $session->setFlashdata("toastMessage", ApplicationConstants::$DRIVER_LICENSE_CREATION_FAILED);
            return redirect()->to("/driver/registerDriverLicense/");
        }

        $driverData = [
            'userId' => $user->userId,
            'driverLicenseId' => $driverLicenseId,
            'active' => true
        ];

        $driverId = $driverService->insert($driverData);

        if (!$driverId) {
            $db->transRollback();
            $session->setFlashdata("toastMessage", ApplicationConstants::$DRIVER_LICENSE_CREATION_FAILED);
            return redirect()->to("/driver/registerDriverLicense/");
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$DRIVER_LICENSE_CREATION_FAILED);
            return redirect()->to("/driver/registerDriverLicense/");
        }

        $session->set("driverId", $driverId);
        $session->setFlashdata("toastMessage", ApplicationConstants::$DRIVER_LICENSE_CREATED);
        return redirect()->to("/driver/");
    }
        public function vehicle() {
        $session = session();
        $driver = AuthHelper::getAuthenticatedDriver();

        $fields = [
            'name',
            'plateNumber',
            'productionDate',
            'model',
            'euroPerKilometer',
            'co2PerKilometer',
        ];

        foreach ($fields as $field) {
            if (empty($_POST[$field])) {
                $session->setFlashdata('toastMessage', ApplicationConstants::$APPLICATION_INCORRECT_PARAMETERS);
                return redirect()->to('/homepage/registerVehicle');
            }
        }

        $carService = model(CarService::class);

        $car = [
            'name' => $_POST['name'],
            'plateNumber' => $_POST['plateNumber'],
            'productionDate' => new DateTime($_POST['productionDate']),
            'model' => $_POST['model'],
            'euroPerKilometer' => $_POST['euroPerKilometer'],
            'co2PerKilometer' => $_POST['co2PerKilometer'],
            'driverId' => $session->driver->driverId,
            'active' => true
        ];

        $res = $carService->createCar($car);

        if (!$res) {
            $session->setFlashdata('toastMessage', ApplicationConstants::$APPLICATION_DATABASE_FAILED);
            return redirect()->to('/homepage/registerVehicle');
        }

        return redirect()->to('/homepage/profile');
    }
}
