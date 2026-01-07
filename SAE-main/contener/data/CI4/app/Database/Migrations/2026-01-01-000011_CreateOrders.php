<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrders extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            // Achat connecté (nullable si invité)
            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            // Achat invité : email (optionnel selon ton checkout)
            'guest_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
                'null'       => true,
            ],

            // Optionnel : token panier invité lié au checkout
            'guest_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
            ],

            // Montant total
            'total_cents' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],

            // pending | paid | cancelled
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pending',
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('guest_email');
        $this->forge->addKey('guest_token');
        $this->forge->addKey('status');

        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('orders', true);
    }

    public function down()
    {
        $this->forge->dropTable('orders', true);
    }
}
