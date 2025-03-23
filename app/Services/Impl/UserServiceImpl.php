<?php
namespace App\Services\Impl;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;

class UserServiceImpl implements UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findAll()
    {
        return $this->userRepository->findAll();
    }

    public function findByParams($params)
    {
        return $this->userRepository->findByParams($params);
    }

    public function getCount($role_id = null, $search = '')
    {
        return $this->userRepository->getCount($role_id, $search);
    }

    public function findById($id)
    {
        return $this->userRepository->findById($id);
    }

    public function findByEmail($email)
    {
        return $this->userRepository->findByEmail($email);
    }

    public function save($data)
    {
        $user = new User(
            null,
            $data['name'],
            $data['email'],
            password_hash($_POST["password"], PASSWORD_BCRYPT),
            $data['role_id'],
            $data['is_active'] == 'on' ? true : false,
            date("Y-m-d H:i:s"),
            date("Y-m-d H:i:s"),
        );

        return $this->userRepository->save($user);
    }

    public function update($id, $data)
    {
        $user = new User(
            $id,
            $data['name'],
            $data['email'],
            password_hash($_POST["password"], PASSWORD_BCRYPT),
            $data['role_id'],
            $data['is_active'] == 'on' ? true : false,
        );

        return $this->userRepository->update($user);
    }
}
