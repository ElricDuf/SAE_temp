<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CartsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        if ($this->db->table('carts')->countAllResults() > 0) {
            return;
        }

        // bob a un panier (user_id=3)
        $carts = [
            [
                'user_id' => 3,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('carts')->insertBatch($carts);
    }
}
