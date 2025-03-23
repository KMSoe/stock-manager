<?php
namespace App\Models;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $role_id;
    private $is_active;
    private $created_at;
    private $updated_at;

    public function __construct($id, $name, $email, $password, $role_id, $is_active, $created_at = null, $updated_at = null)
    {
        $this->id       = $id;
        $this->name       = $name;
        $this->email      = $email;
        $this->password   = $password;
        $this->role_id    = $role_id;
        $this->is_active  = $is_active;
        $this->created_at = $created_at ?? null;
        $this->updated_at = $updated_at ?? null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoleId()
    {
        return $this->role_id;
    }

    public function getIsActive()
    {
        return $this->is_active;
    }
}
