<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class InitStorage extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'app:init-storage';
    protected $description = 'Crée les dossiers de stockage (uploads) nécessaires au projet.';

    public function run(array $params)
    {
        $paths = [
            // preview public
            FCPATH . 'uploads/previews',

            // masters privés
            WRITEPATH . 'uploads/masters',

            // avatars privés (si utilisé)
            WRITEPATH . 'uploads/avatars',
        ];

        foreach ($paths as $p) {
            if (!is_dir($p)) {
                mkdir($p, 0775, true);
                CLI::write("Created: $p", 'green');
            } else {
                CLI::write("OK: $p", 'yellow');
            }
        }
    }
}
