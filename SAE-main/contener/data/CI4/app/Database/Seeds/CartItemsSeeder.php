<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CartItemsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        if ($this->db->table('cart_items')->countAllResults() > 0) {
            return;
        }

        $cart = $this->db->table('carts')->where('user_id', 3)->get()->getRowArray();
        if (!$cart) {
            return;
        }

        $cartId = (int) $cart['id'];

        // bob met beat 1 et 2 dans son panier (beat 3 est déjà vendu)
        $items = [
            ['cart_id' => $cartId, 'beat_id' => 1, 'created_at' => $now],
            ['cart_id' => $cartId, 'beat_id' => 2, 'created_at' => $now],
        ];

        $this->db->table('cart_items')->insertBatch($items);
    }
}
