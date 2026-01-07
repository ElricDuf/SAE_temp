<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WalletsSeeder extends Seeder
{
    public function run()
    {
        $wallets = [
            [
                'user_id' => 3, // beatmaker1
                'balance_cents' => 2500,
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 4, // beatmaker2
                'balance_cents' => 3000,
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('wallets')->truncate();
        $this->db->table('wallets')->insertBatch($wallets);
    }
}
