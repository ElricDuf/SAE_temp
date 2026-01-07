<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCartItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'cart_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'beat_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'quantite' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 1,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['cart_id', 'beat_id']);

        // index utiles
        $this->forge->addKey('cart_id');
        $this->forge->addKey('beat_id');

        $this->forge->addForeignKey('cart_id', 'carts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('beat_id', 'beats', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('cart_items', true);
    }

    public function down()
    {
        $this->forge->dropTable('cart_items', true);
    }
}
