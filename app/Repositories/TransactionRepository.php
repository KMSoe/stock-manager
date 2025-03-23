<?php
namespace App\Repositories;

use App\Models\Transaction;
use PDO;
use PDOException;

class TransactionRepository extends BaseRepository
{
    public function findByParams($params)
    {
        try {
            $transaction_type = $params['transaction_type'] ?? null;
            $search           = $params['search'] ?? null;
            $page             = $params['page'] ?? null;
            $limit            = $params['limit'] ?? null;

            $offset = ($page - 1) * $limit;
            $sql    = "SELECT transactions.*, items.name AS item_name, users.name AS author_name FROM transactions
                JOIN items ON transactions.item_id=items.id
                JOIN users ON transactions.author_id=users.id WHERE 1";

            if ($transaction_type) {
                $sql .= " AND transactions.transaction_type = :transaction_type";
            }

            if ($search) {
                $sql .= " AND items.name LIKE :search";
            }

            $sql .= " ORDER BY transactions.id DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);

            if ($transaction_type) {
                $stmt->bindParam(':transaction_type', $transaction_type);
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

    public function getCount($transaction_type = null, $search = '')
    {
        $sql = "SELECT COUNT(*) FROM transactions
                JOIN items ON transactions.item_id=items.id WHERE 1";

        if ($transaction_type) {
            $sql .= " AND transactions.transaction_type = :transaction_type";
        }

        if ($search) {
            $sql .= " AND items.name LIKE :search";
        }

        $stmt = $this->db->prepare($sql);

        if ($transaction_type) {
            $stmt->bindParam(':transaction_type', $transaction_type);
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
            $query = "SELECT transactions.*, items.name AS item_name, users.name AS author_name FROM transactions
                JOIN items ON transactions.item_id=items.id
                JOIN users ON transactions.author_id=users.id";
            $statement = $this->db->query($query);

            return $statement->fetch();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function findById($id)
    {
        try {
            $query = "SELECT transactions.*, items.name AS item_name, users.name AS author_name FROM transactions
                JOIN items ON transactions.item_id=items.id
                JOIN users ON transactions.author_id=users.id
                WHERE transactions.id=:id";
            $statement = $this->db->prepare($query);
            $statement->execute([
                'id' => $id,
            ]);

            return $statement->fetch();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function save(Transaction $transaction)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO transactions (item_id, author_id, transaction_type, quantity, remark)  VALUES (:item_id, :author_id, :transaction_type, :quantity, :remark)");
            $stmt->execute([
                'item_id'          => $transaction->getItemId(),
                'author_id'        => $transaction->getAuthorId(),
                'transaction_type' => $transaction->getTransactionType(),
                'quantity'         => $transaction->getQuantity(),
                'remark'           => $transaction->getRemark(),
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function update($id, $data)
    {

    }

    public function delete($id)
    {
        try {
            $query     = "DELETE FROM transactions WHERE id=:id";
            $statement = $this->db->prepare($query);
            $statement->execute([
                "id" => $id,
            ]);

            return $statement->rowCount();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function deleteByItemsId($item_id)
    {
        try {
            $query     = "DELETE FROM transactions WHERE item_id=:item_id";
            $statement = $this->db->prepare($query);
            $statement->execute([
                "item_id" => $item_id,
            ]);

            return $statement->rowCount();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
