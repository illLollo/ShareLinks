<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\ApplicationConstants;
use App\Models\Services\UserService;
use DateTime;

class Register extends BaseController
{
    public function index()
    {
        if (session()->has("userId")) {
            return redirect()->to('/homepage/'); // Redirect if already logged in
        }

        echo view("basicNav");
        return view('register', ['toastMessage' => session()->getFlashdata('toastMessage')]);
    }

    public function register() {
        $session = session();
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return redirect()->withInput()->with("error", []);
        }

        $fields = [
            "username",
            "name",
            "surname",
            "birthDate",
            "password",
            "confirm_password",
            "fiscalCode",
            "email"
        ];

        foreach ($fields as $field) {
            if (empty($_POST[$field])) {
                $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_PARAMETERS);
                return redirect()->to('/register/');
            }
        }

        if ($_POST["password"] != $_POST["confirm_password"]) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_PASSWORD_MISMATCH);
            return redirect()->to('/register/');
        }

        $userService = model(UserService::class);

        if ($userService->get(['email' => $_POST["email"], 'active' => true])) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$USER_ALREADY_EXISTS);
            return redirect()->to('/register/');
        }

        $user = [
            'username' => $_POST["username"],
            'name' => $_POST["name"],
            'surname' => $_POST["surname"],
            'birthDate' => (new DateTime($_POST["birthDate"]))->format('Y-m-d'),
            'password' => password_hash($_POST["password"], PASSWORD_BCRYPT),
            'fiscalCode' => $_POST["fiscalCode"],
            'email' => $_POST["email"],
            'active' => true
        ];

        if ($userService->insert($user)) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$USER_REGISTERED);
            return redirect()->to('/login/');
        }

        $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_DATABASE_FAILED);
        return redirect()->to('/register/');
    }
}
