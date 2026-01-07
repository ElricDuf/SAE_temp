<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWallet extends Migration
{
    public function up()
    {
        // 1 wallet par user
        $this->forge->addField([
            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'balance_cents' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('wallets', true);

        // Transactions
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            // credit | debit
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],

            'amount_cents' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            // ex: "vente beat #12", "achat order #5"
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            // lien optionnel vers une commande
            'order_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('order_id');

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('wallet_transactions', true);
    }

    public function down()
    {
        $this->forge->dropTable('wallet_transactions', true);
        $this->forge->dropTable('wallets', true);
    }
}
