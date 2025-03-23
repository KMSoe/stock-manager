<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ItemController;
use App\Controllers\TransactionController;
use App\Controllers\UserController;
use App\Router;

$router = new Router();

$router->get('/', HomeController::class, 'index');

// Auth
$router->get('/auth/login', AuthController::class, 'renderLogin');
$router->post('/auth/login', AuthController::class, 'login');
$router->post('/auth/logout', AuthController::class, 'logout');

// Users
$router->get('/users', UserController::class, 'index');
$router->get('/users/create', UserController::class, 'create');
$router->post('/users', UserController::class, 'store');
$router->get('/users/edit', UserController::class, 'edit');
$router->post('/users/update', UserController::class, 'update');

// Items
$router->get('/items', ItemController::class, 'index');
$router->get('/items/create', ItemController::class, 'create');
$router->post('/items', ItemController::class, 'store');
$router->get('/items/edit', ItemController::class, 'edit');
$router->post('/items/update', ItemController::class, 'update');
$router->post('/item-destroy', ItemController::class, 'destroy');

// Transactions
$router->get('/transactions', TransactionController::class, 'index');
$router->get('/transactions/create', TransactionController::class, 'create');
$router->post('/transactions', TransactionController::class, 'store');
$router->post('/transaction-destroy', TransactionController::class, 'destroy');

$router->dispatch();
