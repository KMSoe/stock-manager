<?php
namespace App\Repositories;

use App\Models\User;
use PDO;
use PDOException;

class UserRepository extends BaseRepository
{
    public function findByParams($params)
    {
        try {
            $role_id = $params['role_id'] ?? null;
            $search  = $params['search'] ?? null;
            $page    = $params['page'] ?? null;
            $limit   = $params['limit'] ?? null;

            $offset = ($page - 1) * $limit;
            $sql    = "SELECT users.*, roles.name AS role_name FROM users JOIN roles ON users.role_id=roles.id";

            if ($role_id) {
                $sql .= " AND users.role_id = :role_id";
            }

            if ($search) {
                $sql .= " AND (users.name LIKE :search OR users.email LIKE :search)";
            }

            $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);

            if ($role_id) {
                $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
            }

            if ($search) {
                $searchTerm = "%" . $search . "%";
                $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
            }

            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getCount($role_id = null, $search = '')
    {
        $sql = "SELECT COUNT(*) FROM users WHERE 1";

        if ($role_id) {
            $sql .= " AND role_id = :role_id";
        }

        if ($search) {
            $sql .= " AND (name LIKE :search OR email LIKE :search)";
        }

        $stmt = $this->db->prepare($sql);

        if ($role_id) {
            $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
        }

        if ($search) {
            $searchTerm = "%" . $search . "%";
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchColumn();
    }
    public function findAll()
    {
        try {
            $query     = "SELECT users.*, roles.name AS role_name FROM users LEFT JOIN roles ON users.role_id=roles.id";
            $statement = $this->db->prepare($query);
            $statement->execute([

            ]);

            return $statement->fetchAll();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function findById($id)
    {
        try {
            $query     = "SELECT users.*, roles.name AS role_name FROM users LEFT JOIN roles ON users.role_id=roles.id WHERE users.id=:user_id";
            $statement = $this->db->prepare($query);
            $statement->execute([
                'user_id' => $id,
            ]);

            return $statement->fetch();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function findByEmail($email)
    {
        try {
            $query     = "SELECT users.*, roles.name AS role_name FROM users LEFT JOIN roles ON users.role_id=roles.id WHERE users.email=:email";
            $statement = $this->db->prepare($query);
            $statement->execute([
                'email' => $email,
            ]);

            return $statement->fetch();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function save(User $user)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role_id, is_active)  VALUES (:name, :email, :password, :role_id, :is_active)");
            $stmt->execute([
                'name'      => $user->getName(),
                'email'     => $user->getEmail(),
                'password'  => $user->getPassword(),
                'role_id'   => $user->getRoleId(),
                'is_active' => $user->getIsActive(),
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function update(User $user)
    {
        try {
            $sql = "UPDATE users SET
                name = :name,
                email = :email,
                password = :password,
                role_id = :role_id,
                is_active = :is_active,
                updated_at = :updated_at
            WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([
                ':name'       => $user->getName(),
                ':email'      => $user->getEmail(),
                ':password'   => $user->getPassword(), // already hashed ideally
                ':role_id'    => $user->getRoleId(),
                ':is_active'  => $user->getIsActive(),
                ':updated_at' => date('Y-m-d H:i:s'),
                ':id'         => $user->getId(),
            ]);

            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($id)
    {

    }
}
