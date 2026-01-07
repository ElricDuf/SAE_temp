<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBeatFiles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'beat_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            // preview_mp3 | master_wav | original_mp3 | stems_zip | cover_image ...
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],

            // Chemin relatif :
            // - preview_mp3 : relatif à public/ (ex: uploads/previews/12/x.mp3)
            // - master_wav  : relatif à writable/ (ex: uploads/masters/12/x.wav)
            'path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'mime_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
            ],

            'size_bytes' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'sha256' => [
                'type'       => 'CHAR',
                'constraint' => 64,
                'null'       => true,
            ],

            'duration_sec' => [
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

        // 1 seul fichier par type et par beat
        $this->forge->addUniqueKey(['beat_id', 'type']);

        $this->forge->addForeignKey('beat_id', 'beats', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('beat_files', true);
    }

    public function down()
    {
        $this->forge->dropTable('beat_files', true);
    }
}
