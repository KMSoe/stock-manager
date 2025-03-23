<?php
namespace App\Models;

class Transaction
{
    private $id;
    private $item_id;
    private $author_id;
    private $transaction_type;
    private $quantity;
    private $remark;
    private $created_at;
    private $updated_at;

    public function __construct($id, $item_id, $author_id, $transaction_type, $quantity, $remark, $created_at = null, $updated_at = null)
    {
        $this->id               = $id;
        $this->item_id          = $item_id;
        $this->author_id        = $author_id;
        $this->transaction_type = $transaction_type;
        $this->quantity         = $quantity;
        $this->remark           = $remark;
        $this->created_at       = $created_at ?? null;
        $this->updated_at       = $updated_at ?? null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getItemId()
    {
        return $this->item_id;
    }

    public function getAuthorId()
    {
        return $this->author_id;
    }

    public function getTransactionType()
    {
        return $this->transaction_type;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getRemark()
    {
        return $this->remark;
    }
}
