<?php
namespace App\Repositories;

use App\Models\Item;
use PDO;
use PDOException;

class ItemRepository extends BaseRepository
{
    public function findByParams($params)
    {
        try {
            $category_id = $params['category_id'] ?? null;
            $search      = $params['search'] ?? null;
            $page        = $params['page'] ?? null;
            $limit       = $params['limit'] ?? null;

            $offset = ($page - 1) * $limit;
            $sql    = "SELECT items.*, categories.name AS category_name, users.name AS author_name FROM items
                        LEFT JOIN categories ON items.category_id=categories.id
                        LEFT JOIN users ON items.author_id=users.id WHERE 1";

            if ($category_id) {
                $sql .= " AND items.category_id = :category_id";
            }

            if ($search) {
                $sql .= " AND items.name LIKE :search";
            }

            $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);

            if ($category_id) {
                $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
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

    public function getCount($category_id = null, $search = '')
    {
        $sql = "SELECT COUNT(*) FROM items WHERE 1";

        if ($category_id) {
            $sql .= " AND category_id = :category_id";
        }

        if ($search) {
            $sql .= " AND name LIKE :search";
        }

        $stmt = $this->db->prepare($sql);

        if ($category_id) {
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
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
            $query = "SELECT items.*, categories.name AS category_name, users.name AS author_name FROM items
                        JOIN categories ON items.category_id=categories.id
                        JOIN users ON items.author_id=users.id";
            $statement = $this->db->query($query);

            return $statement->fetchAll();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function findById($id)
    {
        try {
            $query = "SELECT items.*, categories.name AS category_name, users.name AS author_name FROM items
                        LEFT JOIN categories ON items.category_id=categories.id
                        LEFT JOIN users ON items.author_id=users.id
                        WHERE items.id=:item_id";
            $statement = $this->db->prepare($query);
            $statement->execute([
                'item_id' => $id,
            ]);

            return $statement->fetch();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function save(Item $item)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO items (name, sku, quantity, price, category_id, author_id)  VALUES (:name, :sku, :quantity, :price, :category_id, :author_id)");
            $stmt->execute([
                'name'        => $item->getName(),
                'sku'         => $item->getSku(),
                'quantity'    => $item->getQuantity(),
                'price'       => $item->getPrice(),
                'category_id' => $item->getCategoryId(),
                'author_id'   => $item->getAuthorId(),
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function update(Item $item)
    {

        try {
            $sql = "UPDATE items SET
                        name = :name,
                        sku = :sku,
                        price = :price,
                        category_id = :category_id,
                        author_id = :author_id
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([
                ':name'        => $item->getName(),
                ':sku'         => $item->getSku(),
                ':price'       => $item->getPrice(),
                ':category_id' => $item->getCategoryId(),
                ':author_id'   => $item->getAuthorId(),
                ':id'          => $item->getId(),
            ]);

            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addQuantity($id, $quantity)
    {
        try {
            $statement = $this->db->prepare("SELECT * FROM items WHERE id=:id");
            $statement->execute([
                'id' => $id,
            ]);

            $item = $statement->fetch();

            if (! $item) {
                throw new \Exception("Not found");
            }

            $updated_quantity = $item['quantity'] + $quantity;

            $sql = "UPDATE items SET
                        quantity = :quantity
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([
                ':quantity' => $updated_quantity,
                ':id'       => $id,
            ]);

            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function subQuantity($id, $quantity)
    {
        try {
            $statement = $this->db->prepare("SELECT * FROM items WHERE id=:id");
            $statement->execute([
                'id' => $id,
            ]);

            $item = $statement->fetch();

            if (! $item) {
                throw new \Exception("Not found");
            }

            $updated_quantity = $item['quantity'] - $quantity;

            $sql = "UPDATE items SET
                        quantity = :quantity
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([
                ':quantity' => $updated_quantity,
                ':id'       => $id,
            ]);

            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($id)
    {
        try {
            $query     = "DELETE FROM items WHERE id=:id";
            $statement = $this->db->prepare($query);
            $statement->execute([
                "id" => $id,
            ]);

            return $statement->rowCount();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
