<?php
namespace App\Models;

class Item
{
    private $id;
    private $name;
    private $sku;
    private $quantity;
    private $price;
    private $category_id;
    private $author_id;
    private $created_at;
    private $updated_at;

    public function __construct($id, $name, $sku, $quantity, $price, $category_id, $author_id, $created_at = null, $updated_at = null)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->sku         = $sku;
        $this->quantity    = $quantity ?? 0;
        $this->price       = $price;
        $this->category_id = $category_id;
        $this->author_id   = $author_id;
        $this->created_at  = $created_at ?? null;
        $this->updated_at  = $updated_at ?? null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function getAuthorId()
    {
        return $this->author_id;
    }
}
