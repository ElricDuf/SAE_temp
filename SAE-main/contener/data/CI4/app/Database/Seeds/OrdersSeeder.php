<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrdersSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        
        $this->db->table('orders')->emptyTable();

        $orders = [
            [
                'id' => 1,
                'user_id' => 2, // buyer
                'guest_email' => null,
                'guest_token' => null,
                'total_cents' => 2500,
                'status' => 'paid',
                'created_at' => $now,
                'paid_at' => $now,
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'guest_email' => null,
                'guest_token' => null,
                'total_cents' => 3000,
                'status' => 'paid',
                'created_at' => $now,
                'paid_at' => $now,
            ],
        ];

        $this->db->table('orders')->insertBatch($orders);
    }
}
