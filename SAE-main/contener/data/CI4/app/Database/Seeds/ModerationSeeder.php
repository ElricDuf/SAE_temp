<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ModerationSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        if ($this->db->table('moderation_cases')->countAllResults() > 0) {
            return;
        }

        $cases = [
            [
                'beat_id' => 2,
                'reporter_id' => 2,
                'reason' => 'Beat suspect (test modération)',
                'status' => 'open',
                'created_at' => $now,
                'closed_at' => null,
                'closed_by' => null,
            ],
        ];

        $this->db->table('moderation_cases')->insertBatch($cases);
    }
}
