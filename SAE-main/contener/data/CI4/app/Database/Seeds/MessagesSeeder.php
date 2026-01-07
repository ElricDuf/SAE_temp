<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MessagesSeeder extends Seeder
{
    public function run()
    {
        if ($this->db->table('messages')->countAllResults() > 0) {
            return;
        }

        $conversations = $this->db->table('conversations')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($conversations)) {
            return;
        }

        $now = time();

        $rows = [];

        // conv 1 : bob -> alice
        if (isset($conversations[0])) {
            $convId = (int) $conversations[0]['id'];

            $rows[] = [
                'conversation_id' => $convId,
                'sender_id' => 3,
                'content' => 'Salut, ton beat est dispo ? Je suis très chaud.',
                'created_at' => date('Y-m-d H:i:s', $now - 600),
                'read_at' => null,
            ];
            $rows[] = [
                'conversation_id' => $convId,
                'sender_id' => 2,
                'content' => 'Oui dispo ! Tu veux une offre ou tu passes par le panier ?',
                'created_at' => date('Y-m-d H:i:s', $now - 300),
                'read_at' => null,
            ];
        }

        // conv 2 : alice -> bob
        if (isset($conversations[1])) {
            $convId = (int) $conversations[1]['id'];

            $rows[] = [
                'conversation_id' => $convId,
                'sender_id' => 2,
                'content' => 'Salut, je voulais te demander une collab sur un beat.',
                'created_at' => date('Y-m-d H:i:s', $now - 900),
                'read_at' => null,
            ];
            $rows[] = [
                'conversation_id' => $convId,
                'sender_id' => 3,
                'content' => 'Yes carrément ! Envoie les infos.',
                'created_at' => date('Y-m-d H:i:s', $now - 450),
                'read_at' => null,
            ];
        }

        if (!empty($rows)) {
            $this->db->table('messages')->insertBatch($rows);
        }
    }
}
