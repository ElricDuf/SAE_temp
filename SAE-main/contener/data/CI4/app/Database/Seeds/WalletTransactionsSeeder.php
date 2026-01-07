<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WalletTransactionsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $transactions = [
            [
                'user_id' => 3,
                'type' => 'credit',
                'amount_cents' => 2500,
                'description' => 'Vente du beat #1',
                'order_id' => 1,
                'created_at' => $now,
            ],
            [
                'user_id' => 4,
                'type' => 'credit',
                'amount_cents' => 3000,
                'description' => 'Vente du beat #2',
                'order_id' => 2,
                'created_at' => $now,
            ],
        ];

        $this->db->table('wallet_transactions')->truncate();
        $this->db->table('wallet_transactions')->insertBatch($transactions);
    }
}
