<?php
namespace App\Controllers;

class Controller
{
    protected function render($view, $data = [])
    {
        extract($data);
        
        if(file_exists("../views/$view.php")) {
            require_once "../views/$view.php";
        }
    }
}