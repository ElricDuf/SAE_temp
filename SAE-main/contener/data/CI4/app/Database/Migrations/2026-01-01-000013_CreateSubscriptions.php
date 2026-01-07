<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubscriptions extends Migration
{
    public function up()
    {
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

            // ex: beatmaker | interpreter | premium | buyer ...
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],

            // active | cancelled | expired
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active',
            ],

            // Date de début / fin
            'started_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'ends_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            // Réduction pour l'acheteur (si ce type d'abonnement donne une remise)
            'buyer_discount_percent' => [
                'type'     => 'TINYINT',
                'unsigned' => true,
                'default'  => 0,
            ],

            // Commission prélevée sur les ventes (si abonnement vendeur)
            'commission_percent' => [
                'type'     => 'TINYINT',
                'unsigned' => true,
                'default'  => 0,
            ],

            // Crédit mensuel (si abonnement donne un crédit)
            'monthly_credit_cents' => [
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
        $this->forge->addKey('user_id');
        $this->forge->addKey('type');
        $this->forge->addKey('status');

        // Un abonnement par type/user à la fois
        $this->forge->addUniqueKey(['user_id', 'type']);

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('subscriptions', true);
    }

    public function down()
    {
        $this->forge->dropTable('subscriptions', true);
    }
}
