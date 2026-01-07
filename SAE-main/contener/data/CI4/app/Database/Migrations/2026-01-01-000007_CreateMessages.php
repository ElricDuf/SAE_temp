<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMessages extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'conversation_id' => ['type'=>'INT','unsigned'=>true],
            'sender_id'       => ['type'=>'INT','unsigned'=>true],
            'content'         => ['type'=>'TEXT'],
            'created_at'      => ['type'=>'DATETIME','null'=>true],
            'read_at'         => ['type'=>'DATETIME','null'=>true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('conversation_id');
        $this->forge->addKey('sender_id');
        $this->forge->addKey(['conversation_id','created_at']); // affichage chat

        $this->forge->addForeignKey('conversation_id','conversations','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('sender_id','users','id','CASCADE','CASCADE');

        $this->forge->createTable('messages', true);
    }

    public function down()
    {
        $this->forge->dropTable('messages', true);
    }
}
