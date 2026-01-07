<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ConversationsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        if ($this->db->table('conversations')->countAllResults() > 0) {
            return;
        }

        // bob contacte alice sur beat 1
        $conv1 = [
            'beat_id' => 1,
            'buyer_id' => 3,
            'seller_id' => 2,
            'created_at' => $now,
        ];

        // alice contacte bob sur beat 2 (exemple)
        $conv2 = [
            'beat_id' => 2,
            'buyer_id' => 2,
            'seller_id' => 3,
            'created_at' => $now,
        ];

        $this->db->table('conversations')->insertBatch([$conv1, $conv2]);
    }
}
