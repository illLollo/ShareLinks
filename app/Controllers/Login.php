<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\ApplicationConstants;
use App\Models\ApplicationUtilities;
use App\Models\Services\AccessesService;
use App\Models\Services\UserService;
use CodeIgniter\Database\Exceptions\DatabaseException;
use DateTime;

class Login extends BaseController
{
    public function index()
    {
        $user = AuthHelper::getAuthenticatedUser(false);

        if ($user) {
            redirect()->to("/homepage/");
        }

        echo view("basicNav");
        return view("login", ['toastMessage' => session()->getFlashdata('toastMessage')]);
    }

    public function auth()
    {
        $session = session();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return redirect()->to("/login/");
        }

        $email = $_POST["email"] ?? null;
        $password = $_POST["password"] ?? null;

        if (!$email || !$password) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_DATA_ERROR);
            return redirect()->to("/login/");
        }

        try {
            $userService = model(UserService::class);
            $user = $userService->get(['email' => $email, 'active' => true]);

            if ($user && password_verify($password, $user->password)) {
                $token = ApplicationUtilities::generateToken();

                model(AccessesService::class)->insert([
                    'userId' => $user->userId,
                    'token' => $token,
                    'loginDate' => (new DateTime())->format('Y-m-d H:i:s'),
                    'expiryDate' => (new DateTime())->modify("+" . ApplicationConstants::$TOKEN_EXPIRY_SECONDS . " seconds")->format('Y-m-d H:i:s'),
                    'active' => true
                ]);

                AuthHelper::setAuthenticatedUser($user, $token);

                return redirect()->to("/homepage/");
            }

            $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_INCORRECT_PARAMETERS);
        } catch (DatabaseException $e) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_DATABASE_FAILED);
        }

        return redirect()->to("/login/");
    }
    public function logout()
    {
        try {
            ApplicationUtilities::logout();
        } catch (DatabaseException $e) {
            session()->setFlashdata("toastMessage", ApplicationConstants::$APPLICATION_DATABASE_FAILED);
        }

        return redirect()->to("/login/");
    }
}
