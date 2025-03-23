<?php
namespace App\Helpers;

class Auth
{
    static $loginUrl = '/auth/login';

    public static function check()
    {
        SessionHelper::startSession();
        if (isset($_SESSION["user"])) {
            return $_SESSION["user"];
        } else {
            HTTP::redirect(static::$loginUrl);
        }
    }

    static function can($permission)
    {
        $roles = include '../config/authorization.php';
        $user  = Auth::check();
        $role  = $user['role_name'];
 
        if (!$role || !isset($roles[$role])) {
            return false;
        }

        $permissions = $roles[$role]['can'];

        return in_array($permission, $permissions);
    }
}
