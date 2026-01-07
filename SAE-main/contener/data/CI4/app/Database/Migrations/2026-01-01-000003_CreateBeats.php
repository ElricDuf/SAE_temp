<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBeats extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            // vendeur
            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            // optionnel
            'category_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            // métadonnées
            'bpm' => [
                'type'     => 'SMALLINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'musical_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
                'null'       => true,
            ],
            'tags' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            // produit
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 180,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
            ],

            // état vente
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active', // active|sold|hidden|deleted
            ],
            'buyer_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'sold_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'is_featured' => [
                'type'    => 'TINYINT',
                'default' => 0,
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

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('buyer_id', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('beats', true);
    }

    public function down()
    {
        $this->forge->dropTable('beats', true);
    }
}
