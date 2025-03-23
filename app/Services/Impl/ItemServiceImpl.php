<?php
namespace App\Services\Impl;

use App\Helpers\Database;
use App\Models\Item;
use App\Repositories\ItemRepository;
use App\Services\ItemService;
use App\Services\TransactionService;

class ItemServiceImpl implements ItemService
{
    private ItemRepository $itemRepository;
    private TransactionService $transactionService;

    public function __construct(ItemRepository $itemRepository, TransactionService $transactionService)
    {
        $this->itemRepository = $itemRepository;
        $this->transactionService = $transactionService;
    }

    public function findAll()
    {
        return $this->itemRepository->findAll();
    }

    public function findByParams($params)
    {
        return $this->itemRepository->findByParams($params);
    }

    public function getCount($category_id = null, $search = '')
    {
        return $this->itemRepository->getCount($category_id, $search);
    }

    public function findById($id)
    {
        return $this->itemRepository->findById($id);
    }

   
    public function save($data)
    {
        $item = new Item(
            null,
            $data['name'],
            $data['sku'],
            $data['quantity'] ?? 0,
            $data['price'] ?? 0,
            $data['category_id'],
            $data['author_id'],
        );

        return $this->itemRepository->save($item);
    }

    public function update($id, $data)
    {
        $item = new Item(
            $id,
            $data['name'],
            $data['sku'],
            $data['quantity'] ?? 0,
            $data['price'] ?? 0,
            $data['category_id'],
            $data['author_id'],
        );

        return $this->itemRepository->update($item);
    }

    public function addQuantity($id, $quantity)
    {

    }

    public function subQuantity($id, $quantity)
    {

    }


    public function delete($id)
    {
        $db = Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();
            $this->transactionService->deleteByItemId($id);
            $this->itemRepository->delete($id);
            $db->commit();

        } catch (\Throwable $th) {
            $db->rollBack();
            throw $th;
        }
    }
}
