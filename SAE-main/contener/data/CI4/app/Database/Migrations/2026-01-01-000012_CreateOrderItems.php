<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'order_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'beat_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'seller_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'beat_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
            ],

            'price_cents' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        // Index utiles
        $this->forge->addKey('order_id');
        $this->forge->addKey('seller_id');
        $this->forge->addUniqueKey('beat_id');

        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('beat_id', 'beats', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('seller_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('order_items', true);
    }

    public function down()
    {
        $this->forge->dropTable('order_items', true);
    }
}
