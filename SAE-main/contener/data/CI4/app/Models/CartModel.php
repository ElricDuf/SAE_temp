<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table      = 'carts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['user_id','updated_at'];
    protected $useTimestamps = false;

    public function getOrCreateForUser(int $userId): array
    {
        $cart = $this->where('user_id', $userId)->first();
        if ($cart) return $cart;

        $this->insert([
            'user_id' => $userId,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->find((int)$this->getInsertID());
    }
    public function countItems(int $cartId): int
    {
        return $this->db->table('cart_items')
            ->where('cart_id', $cartId)
            ->countAllResults();
    }
}
