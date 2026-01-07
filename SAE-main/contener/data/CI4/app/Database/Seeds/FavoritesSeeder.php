<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FavoritesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        if ($this->db->table('favorites')->countAllResults() > 0) {
            return;
        }

        // bob (3) met en favori beat 1 et 2
        $favorites = [
            ['user_id' => 3, 'beat_id' => 1, 'created_at' => $now],
            ['user_id' => 3, 'beat_id' => 2, 'created_at' => $now],
        ];

        $this->db->table('favorites')->insertBatch($favorites);
    }
}
