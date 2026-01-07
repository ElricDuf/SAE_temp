<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
                'null'       => false,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => false,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => 'user',
            ],
            'avatar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'artist_genre' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('username');

        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
