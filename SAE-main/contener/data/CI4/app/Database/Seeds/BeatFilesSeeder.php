<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BeatFilesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        if ($this->db->table('beat_files')->countAllResults() > 0) {
            return;
        }

        /**
         * Important :
         * Ces chemins sont cohérents avec le nouveau modèle.
         * Les fichiers physiques peuvent ne pas exister (seed = DB),
         * mais la structure est prête.
         */
        $files = [
            // Beat 1
            [
                'beat_id'       => 1,
                'type'          => 'preview_mp3',
                'path'          => 'uploads/previews/1/preview.mp3', // relatif à public/
                'mime_type'     => 'audio/mpeg',
                'size_bytes'    => 1500000,
                'sha256'        => null,
                'duration_sec'  => 190,
                'created_at'    => $now,
            ],
            [
                'beat_id'       => 1,
                'type'          => 'master_wav',
                'path'          => 'uploads/masters/1/master.wav', // relatif à writable/
                'mime_type'     => 'audio/wav',
                'size_bytes'    => 22000000,
                'sha256'        => null,
                'duration_sec'  => 190,
                'created_at'    => $now,
            ],

            // Beat 2
            [
                'beat_id'       => 2,
                'type'          => 'preview_mp3',
                'path'          => 'uploads/previews/2/preview.mp3',
                'mime_type'     => 'audio/mpeg',
                'size_bytes'    => 1200000,
                'sha256'        => null,
                'duration_sec'  => 175,
                'created_at'    => $now,
            ],
            [
                'beat_id'       => 2,
                'type'          => 'master_wav',
                'path'          => 'uploads/masters/2/master.wav',
                'mime_type'     => 'audio/wav',
                'size_bytes'    => 21000000,
                'sha256'        => null,
                'duration_sec'  => 175,
                'created_at'    => $now,
            ],

            // Beat 3 (vendu)
            [
                'beat_id'       => 3,
                'type'          => 'preview_mp3',
                'path'          => 'uploads/previews/3/preview.mp3',
                'mime_type'     => 'audio/mpeg',
                'size_bytes'    => 1600000,
                'sha256'        => null,
                'duration_sec'  => 200,
                'created_at'    => $now,
            ],
            [
                'beat_id'       => 3,
                'type'          => 'master_wav',
                'path'          => 'uploads/masters/3/master.wav',
                'mime_type'     => 'audio/wav',
                'size_bytes'    => 23000000,
                'sha256'        => null,
                'duration_sec'  => 200,
                'created_at'    => $now,
            ],
        ];

        $this->db->table('beat_files')->insertBatch($files);
    }
}
