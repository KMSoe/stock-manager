<?php

use App\Providers\AppServiceProvider;
use App\Containers\ServiceContainer;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Autoloader
require_once '../vendor/autoload.php';

// Load Config
require_once '../config/config.php';

$container = ServiceContainer::getInstance();
AppServiceProvider::register($container);

// Routes
require_once '../app/Router.php';
require_once '../routes/web.php';
