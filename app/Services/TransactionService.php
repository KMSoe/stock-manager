<?php

namespace App\Services;

interface TransactionService
{
    public function findAll();
    public function findByParams($params);
    public function getCount($transaction_type, $search = '');
    public function findById($id);
    public function save($data);
    public function delete($id);
    public function deleteByItemId($item_id);
}