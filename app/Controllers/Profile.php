<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\AuthHelper;
use App\Models\ApplicationConstants;
use App\Models\ApplicationUtilities;
use App\Models\Services\UserService;
use DateTime;

class Profile extends BaseController
{
    public function index(): string
    {
        $user = AuthHelper::getAuthenticatedUser();
        $driver = AuthHelper::getAuthenticatedDriver($user, false);

        echo view("header", ['user' => $user, 'driver' => $driver]);
        return view("profile", ['user' => $user, 'driver' => null]);
    }
    public function updateProfile() {

        $user = AuthHelper::getAuthenticatedUser();
        $session = session();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_METHOD);
            return redirect()->to("/profile/");
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
                return redirect()->to("/profile/");
            }
        }

        if ($_POST["password"] != $_POST["confirm_password"]) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_PASSWORD_MISMATCH);
            return redirect()->to("/profile/");
        }

        $update = model(UserService::class)->update(
            [
                'username' => $_POST["username"],
                'name' => $_POST["name"],
                'surname' => $_POST["surname"],
                'birthDate' => (new DateTime($_POST["birthDate"]))->format("Y-m-d"),
                'password' => password_hash($_POST["password"], PASSWORD_BCRYPT),
                'fiscalCode' => $_POST["fiscalCode"],
                'email' => $_POST["email"]
            ],
            ['userId' => $user->userId, 'active' => true]
        );

        $session->setFlashdata("toastMessage", $update ? ApplicationConstants::$FORM_UPLOAD_SUCCESSFULLY : ApplicationConstants::$FORM_UPLOAD_FAILED);

        return redirect()->to("/profile/");
    }
}
