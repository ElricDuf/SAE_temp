<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateModerationCases extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'beat_id'     => ['type'=>'INT','unsigned'=>true],
            'reporter_id' => ['type'=>'INT','unsigned'=>true],
            'reason'      => ['type'=>'VARCHAR','constraint'=>255],
            'status'      => ['type'=>'VARCHAR','constraint'=>20,'default'=>'open'], // open|closed
            'created_at'  => ['type'=>'DATETIME','null'=>true],
            'closed_at'   => ['type'=>'DATETIME','null'=>true],
            'closed_by'   => ['type'=>'INT','unsigned'=>true,'null'=>true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('beat_id');
        $this->forge->addKey('reporter_id');
        $this->forge->addKey('status');

        $this->forge->addForeignKey('beat_id','beats','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('reporter_id','users','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('closed_by','users','id','SET NULL','CASCADE');

        $this->forge->createTable('moderation_cases', true);
    }

    public function down()
    {
        $this->forge->dropTable('moderation_cases', true);
    }
}
