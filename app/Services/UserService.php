<?php

namespace App\Services;

interface UserService
{
    public function findAll();
    public function findByParams($params);
    public function getCount($role_id = null, $search = '');
    public function findById($id);
    public function findByEmail($email);
    public function save($data);
    public function update($id, $data);
}