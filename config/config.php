<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

define('SITE_NAME', 'your-site-name');

//App Root
define('APP_ROOT', dirname(dirname(__FILE__)));

