<?php
namespace App\Providers;

use App\Containers\ServiceContainer;
use App\Controllers\AuthController;
use App\Controllers\ItemController;
use App\Controllers\TransactionController;
use App\Controllers\UserController;
use App\Repositories\CategoryRepository;
use App\Repositories\ItemRepository;
use App\Repositories\RoleRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Services\Impl\AuthServiceImpl;
use App\Services\Impl\CategoryServiceImpl;
use App\Services\Impl\ItemServiceImpl;
use App\Services\Impl\RoleServiceImpl;
use App\Services\Impl\TransactionServiceImpl;
use App\Services\Impl\UserServiceImpl;
use App\Services\ItemService;
use App\Services\TransactionService;
use App\Services\UserService;

class AppServiceProvider
{
    public static function register(ServiceContainer $container): void
    {
        $container->bind(UserController::class, function () {
            return new UserController(
                new UserServiceImpl(new UserRepository),
                new RoleServiceImpl(new RoleRepository)
             );
        });

        $container->bind(UserService::class, function () {
            return new UserServiceImpl(new UserRepository);
        });

        $container->bind(AuthController::class, function () {
            return new AuthController(new UserServiceImpl(new UserRepository));
        });

        $container->bind(ItemController::class, function () {
            return new ItemController(
                new ItemServiceImpl(new ItemRepository, new TransactionServiceImpl(new TransactionRepository)),
                new CategoryServiceImpl(new CategoryRepository));
        });

        $container->bind(ItemService::class, function () {
            return new ItemServiceImpl(new ItemRepository, new TransactionServiceImpl(new TransactionRepository));
        });

        $container->bind(TransactionController::class, function () {
            return new TransactionController(
                new TransactionServiceImpl(new TransactionRepository),
                new ItemServiceImpl(new ItemRepository, new TransactionServiceImpl(new TransactionRepository))
            );
        });

        $container->bind(TransactionService::class, function () {
            return new TransactionServiceImpl(new TransactionRepository);
        });
    }
}
