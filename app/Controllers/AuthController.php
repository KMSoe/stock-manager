<?php
namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\HTTP;
use App\Helpers\SessionHelper;
use App\Helpers\Validator;
use App\Services\UserService;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function renderLogin()
    {
        // $user = Auth::check();

        // if ($user) {
        //     HTTP::redirect("/");
        // }

        $this->render('auth/login');
    }

    public function login()
    {
        $validator = new Validator($_POST);

        $validator->required('email')->email('email')
            ->required('password');

        SessionHelper::startSession();
        SessionHelper::setOldValues($_POST);

        if ($validator->fails()) {
            SessionHelper::setValidationErrors($validator->errors());

            HTTP::redirect("/auth/login");
        }

        $email    = $_POST["email"];
        $password = $_POST["password"];

        $user = $this->userService->findByEmail($email);

        unset($_SESSION['errors']);

        SessionHelper::startSession();

        if (! $user) {
            SessionHelper::setFlashMessage('error', 'Incorrect Email And/Or Password!');
            HTTP::redirect("/auth/login");
        }

        if (! $user['is_active']) {
            SessionHelper::setFlashMessage('error', 'Not Activated. Contact Admin!');
            HTTP::redirect("/auth/login");
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION["user"] = $user;
            HTTP::redirect("/");
        } else {
            SessionHelper::setFlashMessage('error', 'Incorrect Email And/Or Password!');
            HTTP::redirect("/auth/login");
        }

    }

    public function logout()
    {
        session_start();
        session_unset();   // Unset all session variables
        session_destroy(); // Destroy the session

        HTTP::redirect("/auth/login");
    }
}
