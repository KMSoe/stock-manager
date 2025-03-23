<?php
namespace App\Repositories;

use PDOException;

class RoleRepository extends BaseRepository
{
    public function findAll()
    {
        try {
            $statement = $this->db->query("SELECT * FROM roles");

            return $statement->fetchAll();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
