<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        // si déjà seedé, on sort (même logique que tes seeders)
        if ($this->db->table('categories')->countAllResults() > 0) {
            return;
        }

        $categories = [
            ['name' => 'Hip-Hop',     'slug' => 'hip-hop'],
            ['name' => 'Trap',        'slug' => 'trap'],
            ['name' => 'Drill',       'slug' => 'drill'],
            ['name' => 'R&B',         'slug' => 'rnb'],
            ['name' => 'Pop',         'slug' => 'pop'],
            ['name' => 'Afrobeat',    'slug' => 'afrobeat'],
            ['name' => 'House',       'slug' => 'house'],
            ['name' => 'Techno',      'slug' => 'techno'],
            ['name' => 'Lo-Fi',       'slug' => 'lo-fi'],
            ['name' => 'Reggaeton',   'slug' => 'reggaeton'],
        ];

        $this->db->table('categories')->insertBatch($categories);
    }
}
