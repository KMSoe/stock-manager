<?php
namespace App\Repositories;

use App\Helpers\Database;
use App\Models\User;
use PDOException;

class BaseRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
}