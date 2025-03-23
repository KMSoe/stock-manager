<?php

namespace App\Controllers;

use App\Helpers\Auth;

class HomeController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = Auth::check();
    }
    public function index()
    {
        $this->render('home', ['user' => $this->user]);
    }
}