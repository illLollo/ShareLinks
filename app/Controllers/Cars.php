<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\ApplicationConstants;
use App\Models\ApplicationUtilities;
use App\Models\Services\CarService;
use CodeIgniter\Database\Exceptions\DatabaseException;
use DateTime;

class Cars extends BaseController
{
    public function index($page = 1)
    {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user);

        $carService = model(CarService::class);
        $carChunk = ApplicationConstants::$CAR_BASIC_CHUNK;
        $cars = $carService->where(['driverId' => $driver->driverId, 'active' => true])
            ->limit($carChunk, ($page - 1) * $carChunk)
            ->findAll();

        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view('car', [
            'nCars' => $carService->count(['driverId' => $driver->driverId, 'active' => true]),
            'carChunk' => $carChunk,
            'cars' => $cars,
            'page' => $page,
            'query' => null
        ]);
    }

    public function add()
    {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user);

        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view("registerCar");
    }

    public function searchRedirect()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return redirect()->to("/cars/");
        }

        return redirect()->to("/cars/search/" . $_POST["query"]);
    }

    public function search($query = '', $page = 1)
    {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user);

        $carService = model(CarService::class);

        $cars = $carService->where(['driverId' => $driver->driverId, 'active' => true])
            ->groupStart()
            ->like('name', $query)
            ->orLike('plateNumber', $query)
            ->orLike('model', $query)
            ->orLike('productionDate', $query)
            ->orLike('euroPerKilometer', $query)
            ->orLike('co2PerKilometer', $query)
            ->groupEnd()
            ->limit(ApplicationConstants::$CAR_BASIC_CHUNK, ($page - 1) * ApplicationConstants::$CAR_BASIC_CHUNK)
            ->findAll();

        echo view("header", ['user' => $user, 'driver' => $driver]);

        return view('car', [
            'nCars' => $carService->where(['driverId' => $driver->driverId, 'active' => true])
                ->groupStart()
                ->like('name', $query)
                ->orLike('plateNumber', $query)
                ->orLike('model', $query)
                ->orLike('productionDate', $query)
                ->orLike('euroPerKilometer', $query)
                ->orLike('co2PerKilometer', $query)
                ->groupEnd()
                ->countAllResults(),
            'carChunk' => ApplicationConstants::$CAR_BASIC_CHUNK,
            'cars' => $cars,
            'page' => $page,
            "query" => $query
        ]);
    }

    public function create()
    {
        $session = session();
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_METHOD);
            return redirect()->to("/cars/");
        }

        $fields = [
            "name",
            "plateNumber",
            "productionDate",
            "model",
            "euroPerKilometer",
            "co2PerKilometer",
        ];

        foreach ($fields as $field) {
            if (empty($_POST[$field])) {
                $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_PARAMETERS);
                return redirect()->to("/cars/add");
            }
        }

        $driver = AuthHelper::getAuthenticatedDriver();

        $carService = model(CarService::class);
        try {
            $res = $carService->insert([
                'name' => $_POST["name"],
                'plateNumber' => $_POST["plateNumber"],
                'productionDate' => (new DateTime($_POST["productionDate"]))->format("Y-m-d"),
                'model' => $_POST["model"],
                'euroPerKilometer' => $_POST["euroPerKilometer"],
                'co2PerKilometer' => $_POST["co2PerKilometer"],
                'driverId' => $driver->driverId,
                'active' => true
            ]);
        }
        catch (DatabaseException $e) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$CREATE_FAILED);
            return redirect()->to("/cars/add");
        }

        $session->setFlashdata("toastMessage", $res ? ApplicationConstants::$CREATE_SUCCESSFULLY : ApplicationConstants::$CREATE_FAILED);
        return redirect()->to($res ? "/cars/" : "/cars/add");

    }

    public function details($carId)
    {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user);

        $car = model(CarService::class)->get(["carId" => $carId, "driverId" => $driver->driverId, "active" => true]);
        if (!$car) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$CANNOT_EXECUTE);
            return redirect()->to('/cars');
        }
        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view('carDetails', [
            'car' => $car
        ]);
    }

    public function update()
    {
        $session = session();
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_METHOD);
            return redirect()->to("/cars/");
        }

        $fields = [
            "name",
            "plateNumber",
            "productionDate",
            "model",
            "euroPerKilometer",
            "co2PerKilometer",
        ];

        foreach ($fields as $field) {
            if (empty($_POST[$field])) {
                $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_PARAMETERS);
                return redirect()->to("/cars/details/");
            }
        }

        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user);

        $carService = model(CarService::class);
        $carId = $_POST["carId"];

        try {
            $res = $carService->where('driverId', $driver->driverId)->update(
                $carId,
                [
                    'name' => $_POST["name"],
                    'plateNumber' => $_POST["plateNumber"],
                    'productionDate' => (new DateTime($_POST["productionDate"]))->format("Y-m-d"),
                    'model' => $_POST["model"],
                    'euroPerKilometer' => $_POST["euroPerKilometer"],
                    'co2PerKilometer' => $_POST["co2PerKilometer"]
                ]
            );
            $session->setFlashdata("toastMessage", $res ? ApplicationConstants::$UPDATE_SUCCESSFULLY : ApplicationConstants::$UPDATE_FAILED);
        } catch (DatabaseException $e) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$UPDATE_FAILED);
        }

        return redirect()->to("/cars/details/$carId");
    }

    public function delete()
    {
        $carId = $_POST["carId"];

        if (!$carId) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_PARAMETERS);
            return redirect()->to("/cars/");
        }

        $session = session();
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user);

        $carService = model(CarService::class);

        $res = $carService->where(["driverId" => $driver->driverId])->update($carId, ["active" => false]);

        $session->setFlashdata("toastMessage", $res ? ApplicationConstants::$DELETE_SUCCESSFULLY : ApplicationConstants::$DELETE_FAILED);
        return redirect()->to("/cars/");
    }
}
