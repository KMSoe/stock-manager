<?php
namespace App\Services\Impl;

use App\Enums\TransactionType;
use App\Helpers\Database;
use App\Models\Transaction;
use App\Repositories\ItemRepository;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;

class TransactionServiceImpl implements TransactionService
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function findAll()
    {
        return $this->transactionRepository->findAll();
    }

    public function findByParams($params)
    {
        return $this->transactionRepository->findByParams($params);
    }

    public function getCount($transaction_type, $search = '')
    {
        return $this->transactionRepository->getCount($transaction_type, $search);
    }

    public function findById($id)
    {
        return $this->transactionRepository->findById($id);
    }

    public function save($data)
    {
        $db = Database::getInstance()->getConnection();
        try {
            $transaction = new Transaction(
                null,
                $data['item_id'],
                $data['author_id'],
                $data['transaction_type'],
                $data['quantity'] ?? 0,
                $data['remark'],
            );

            $db->beginTransaction();

            $id = $this->transactionRepository->save($transaction);
            $transaction = $this->transactionRepository->findById($id);

            if (!$transaction) {
                throw new \Exception("Transaction not found");
            }
  
            $quantity = $transaction['quantity'];

            // Not requrie always
            $itemRepository = new ItemRepository();
            
            if($transaction['transaction_type'] == TransactionType::IN->name) {
                $itemRepository->addQuantity($transaction['item_id'], $quantity);
            } else if($transaction['transaction_type'] == TransactionType::OUT->name) {
                $itemRepository->subQuantity($transaction['item_id'], $quantity);
            }

            $db->commit();

        } catch (\Throwable $th) {
            $db->rollBack();
            throw $th;
        }
    }

    public function delete($id)
    {
        $db = Database::getInstance()->getConnection();
        try {
            $transaction = $this->transactionRepository->findById($id);
            if (!$transaction) {
                throw new \Exception("Transaction not found");
            }

            $quantity = $transaction['quantity'];

            
            $db->beginTransaction();

            // Not requrie always
            $itemRepository = new ItemRepository();
            
            if($transaction['transaction_type'] == TransactionType::IN->name) {
                $itemRepository->subQuantity($transaction['item_id'], $quantity);
            } else if($transaction['transaction_type'] == TransactionType::OUT->name) {
                $itemRepository->addQuantity($transaction['item_id'], $quantity);
            }

            $this->transactionRepository->delete($id);

            $db->commit();

        } catch (\Throwable $th) {
            $db->rollBack();
            throw $th;
        }
        
    }

    public function deleteByItemId($item_id)
    {
        return $this->transactionRepository->deleteByItemsId($item_id);
    }
}
