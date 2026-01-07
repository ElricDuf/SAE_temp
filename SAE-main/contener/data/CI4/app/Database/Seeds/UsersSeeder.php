<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $users = [
            [
                'email' => 'admin@tempo.test',
                'username' => 'admin',
                'password_hash' => password_hash('admin0', PASSWORD_DEFAULT),
                'role' => 'admin',
                'avatar' => null,
                'artist_genre' => null,
                'created_at' => $now,
            ],
            [
                'email' => 'buyer@tempo.test',
                'username' => 'buyer',
                'password_hash' => password_hash('buyer0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => null,
                'artist_genre' => null,
                'created_at' => $now,
            ],
            [
                'email' => 'beatmaker1@tempo.test',
                'username' => 'beatmaker1',
                'password_hash' => password_hash('test0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'prod1.jpg',
                'artist_genre' => 'Trap',
                'created_at' => $now,
            ],
            [
                'email' => 'beatmaker2@tempo.test',
                'username' => 'beatmaker2',
                'password_hash' => password_hash('test0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'prod2.jpg',
                'artist_genre' => 'Drill',
                'created_at' => $now,
            ],
            [
                'email' => 'beatmaker3@tempo.test',
                'username' => 'beatmaker3',
                'password_hash' => password_hash('test0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'prod3.jpg',
                'artist_genre' => null,
                'created_at' => $now,
            ],
        ];

        foreach ($users as $u) {
            $exists = $this->db->table('users')
                ->where('email', $u['email'])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('users')->insert($u);
            }
        }
    }
}
