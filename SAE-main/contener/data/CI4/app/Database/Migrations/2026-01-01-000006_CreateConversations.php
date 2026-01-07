<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConversations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'beat_id'   => ['type'=>'INT','unsigned'=>true],
            'buyer_id'  => ['type'=>'INT','unsigned'=>true],
            'seller_id' => ['type'=>'INT','unsigned'=>true],
            'created_at'=> ['type'=>'DATETIME','null'=>true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('beat_id');
        $this->forge->addKey('buyer_id');
        $this->forge->addKey('seller_id');

        // Evite doublons (un acheteur ne crée pas 50 conv sur le même beat)
        $this->forge->addUniqueKey(['beat_id','buyer_id','seller_id']);

        $this->forge->addForeignKey('beat_id','beats','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('buyer_id','users','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('seller_id','users','id','CASCADE','CASCADE');

        $this->forge->createTable('conversations', true);
    }

    public function down()
    {
        $this->forge->dropTable('conversations', true);
    }
}
