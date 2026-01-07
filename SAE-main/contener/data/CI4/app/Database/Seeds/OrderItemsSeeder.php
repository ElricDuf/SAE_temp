<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrderItemsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('order_items')->emptyTable();

        $items = [
            [
                'order_id' => 1,
                'beat_id' => 1,
                'seller_id' => 3,
                'beat_title' => 'Trap Night',
                'price_cents' => 2500,
                'created_at' => $now,
            ],
            [
                'order_id' => 2,
                'beat_id' => 2,
                'seller_id' => 4,
                'beat_title' => 'Drill Street',
                'price_cents' => 3000,
                'created_at' => $now,
            ],
        ];

        $this->db->table('order_items')->insertBatch($items);
    }
}
