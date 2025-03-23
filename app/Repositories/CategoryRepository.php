<?php
namespace App\Repositories;

use PDOException;

class CategoryRepository extends BaseRepository
{
    public function findAll()
    {
        try {
            $statement = $this->db->query("SELECT * FROM categories");

            return $statement->fetchAll();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
