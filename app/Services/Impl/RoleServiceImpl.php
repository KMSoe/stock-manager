<?php
namespace App\Services\Impl;

use App\Repositories\RoleRepository;
use App\Services\RoleService;

class RoleServiceImpl implements RoleService
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function findAll()
    {
        return $this->roleRepository->findAll();
    }
}
