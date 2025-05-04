<?php

namespace App\Models;

use App\Models\Services\AccessesService;
use App\Models\Services\DriverService;
use App\Models\Services\UserService;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class ApplicationUtilities
{
    public static function generateToken()
    {
        $timestamp = microtime(true);
        $randomString = bin2hex(random_bytes(16));
        return substr($timestamp . $randomString, 0, 32);
    }

    public static function verifyAuth(): ?object
    {
        $session = session();

        if ($session->has("userId")) {
            $access = model(AccessesService::class)->select("t_accesses.*")->where([
                'userId' => $session->get("userId"),
                'active' => true,
                'expiryDate >' => date("Y-m-d H:i:s")
            ])->orderBy("loginDate", "DESC")->first();

            if ($access) {
                $user = model(UserService::class)->get([
                    'userId' => $session->get("userId"),
                    'active' => true
                ]);

                if ($user) {
                    $user->token = $access["token"];
                    return $user;
                }
            }
        }

        return null;
    }

    public static function verifyDriver($userArg = null): ?object{

        try {
            $user = $userArg ?? self::verifyAuth();

            if (!$user) {
                throw PageNotFoundException::forPageNotFound();
            }

            $driver = model(DriverService::class)->get(["userId" => $user->userId, "active" => true]);

            if ($driver) {
                return $driver;
            }
        } catch (PageNotFoundException $e) {}

        throw PageNotFoundException::forPageNotFound();
    }

    public static function authenticate(?object $user, string $view)
    {
        if ($user) {
            session()->set("user", $user);
            return view($view) . view("footer");
        }

        session()->destroy();
        return redirect()->to("/login/");
    }

    public static function logout()
    {
        $session = session();

        try {
            $user = static::verifyAuth();

            if ($user) {
                model(AccessesService::class)->delete(['userId' => $user->userId, 'active' => true]);
                $session->setFlashdata("toastMessage", ApplicationConstants::$LOGOUT);
            }
        } catch (DatabaseException $e) {
            $session->setFlashdata("toastMessage", ApplicationConstants::$FORM_UPLOAD_FAILED);
        }

        $session->destroy();
        return redirect()->to("/");
    }
}