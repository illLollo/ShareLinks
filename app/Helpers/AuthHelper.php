<?php

namespace App\Helpers;

use App\Models\ApplicationUtilities;

class AuthHelper
{
    /**
     * Verifies if the user is authenticated and returns the user object.
     * If not authenticated, redirects to the login page.
     *
     * @return object The authenticated user object.
     */
    public static function getAuthenticatedUser($redirect = true): ?object
    {
            $auth = ApplicationUtilities::verifyAuth() ?? null;
            if (!$auth && $redirect) {
                redirect()->to('/login')->send();
                exit;
            }
            return $auth;
    }
    public static function getAuthenticatedDriver($user = null, $redirect = true): ?object
    {
        try {
            return ApplicationUtilities::verifyDriver($user);
        } catch (\CodeIgniter\Exceptions\PageNotFoundException $e) {
            if ($redirect) {
                return redirect()->to('/login')->send();
            }
        }
        return null;
    }
    public static function setAuthenticatedUser(object $user, string $token): void
    {
        session()->set("token", $token);
        session()->set("userId", $user->userId);
    }
}
