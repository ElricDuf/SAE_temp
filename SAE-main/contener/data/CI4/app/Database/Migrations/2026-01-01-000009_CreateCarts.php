<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCarts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            // Panier connecté (nullable si invité)
            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            // Panier invité (token stocké en cookie)
            'guest_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('user_id');
        $this->forge->addUniqueKey('guest_token');

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('carts', true);
    }

    public function down()
    {
        $this->forge->dropTable('carts', true);
    }
}
