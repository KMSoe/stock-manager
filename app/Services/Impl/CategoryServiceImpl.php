<?php
namespace App\Services\Impl;

use App\Repositories\CategoryRepository;
use App\Repositories\RoleRepository;
use App\Services\CategoryService;
use App\Services\RoleService;

class CategoryServiceImpl implements CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function findAll()
    {
        return $this->categoryRepository->findAll();
    }
}
