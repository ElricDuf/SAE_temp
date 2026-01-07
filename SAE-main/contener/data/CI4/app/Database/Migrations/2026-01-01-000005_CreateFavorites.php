<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFavorites extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id'    => ['type'=>'INT','unsigned'=>true],
            'beat_id'    => ['type'=>'INT','unsigned'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
        ]);

        $this->forge->addKey(['user_id','beat_id'], true);
        $this->forge->addKey('beat_id');

        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('beat_id','beats','id','CASCADE','CASCADE');

        $this->forge->createTable('favorites', true);
    }

    public function down()
    {
        $this->forge->dropTable('favorites', true);
    }
}
