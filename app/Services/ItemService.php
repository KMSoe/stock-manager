<?php

namespace App\Services;

interface ItemService
{
    public function findAll();
    public function findByParams($params);
    public function getCount($category_id = null, $search = '');
    public function findById($id);
    public function save($data);
    public function update($id, $data);
    public function addQuantity($id, $quantity);
    public function subQuantity($id, $quantity);
    public function delete($id);
}