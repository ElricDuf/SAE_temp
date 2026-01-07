<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BeatsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // On évite d’insérer deux fois
        if ($this->db->table('beats')->countAllResults() > 0) {
            return;
        }

        /**
         * Hypothèses simples et stables (TD) :
         * - user_id 2 = vendeur principal
         * - user_id 3 = autre vendeur / acheteur pour beat vendu
         * - category_id 1 existe (si pas sûr: mettre null)
         */
        $defaultCategoryId = 1;

        $beats = [
            // ----- Tes 3 beats de base -----
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 140,
                'musical_key'  => 'Am',
                'tags'         => 'trap,dark,808',
                'title'        => 'Dark Trap Beat',
                'description'  => 'Beat trap sombre, parfait pour une topline agressive.',
                'price'        => 19.99,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 95,
                'musical_key'  => 'C#m',
                'tags'         => 'lofi,chill,study',
                'title'        => 'Lo-Fi Chill Beat',
                'description'  => 'Ambiance lo-fi, douce et relax.',
                'price'        => 14.50,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                // Exemple beat déjà vendu
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 128,
                'musical_key'  => 'Gm',
                'tags'         => 'club,energy,groove',
                'title'        => 'Club Anthem Beat',
                'description'  => 'Beat énergique, vibes club.',
                'price'        => 25.00,
                'status'       => 'sold',
                'buyer_id'     => 3,
                'sold_at'      => $now,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Midnight 808 (Trap)
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 140,
                'musical_key'  => 'Am',
                'tags'         => 'dark,808,aggressive',
                'title'        => 'Midnight 808',
                'description'  => 'Trap sombre, grosse 808, idéal pour topline.',
                'price'        => 49.99,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Dusty Crate (Boom-bap)
            [
                'user_id'      => 3,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 92,
                'musical_key'  => 'F#m',
                'tags'         => 'boom-bap,vinyl,chill',
                'title'        => 'Dusty Crate',
                'description'  => 'Boom bap old school, vibe vinyle.',
                'price'        => 39.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Coffee Break (Lo-fi) - vendu
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 78,
                'musical_key'  => 'Cmaj',
                'tags'         => 'lofi,study,soft',
                'title'        => 'Coffee Break',
                'description'  => 'Lo-fi doux, parfait pour une ambiance chill.',
                'price'        => 25.00,
                'status'       => 'sold',
                'buyer_id'     => 3,
                'sold_at'      => $now,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Drill
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 142,
                'musical_key'  => 'Cm',
                'tags'         => 'drill,uk,sliding-808',
                'title'        => 'London Fog',
                'description'  => 'Drill UK percutante avec des basses glissantes.',
                'price'        => 55.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // R&B
            [
                'user_id'      => 3,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 110,
                'musical_key'  => 'Bb',
                'tags'         => 'rnb,smooth,love',
                'title'        => 'Late Night Call',
                'description'  => 'Ambiance R&B moderne (vibe The Weeknd).',
                'price'        => 45.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Afrobeat
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 100,
                'musical_key'  => 'Gmaj',
                'tags'         => 'afro,dancehall,summer',
                'title'        => 'Lagos Vibes',
                'description'  => 'Rythmes afro entraînants pour l\'été.',
                'price'        => 40.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Synthwave
            [
                'user_id'      => 3,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 120,
                'musical_key'  => 'Dm',
                'tags'         => 'retro,80s,synth',
                'title'        => 'Neon Highway',
                'description'  => 'Synthwave rétro-futuriste style années 80.',
                'price'        => 35.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Pop
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 128,
                'musical_key'  => 'Emaj',
                'tags'         => 'pop,radio,happy',
                'title'        => 'Sunny Day',
                'description'  => 'Pop commerciale calibrée pour la radio.',
                'price'        => 60.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Grime
            [
                'user_id'      => 3,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 140,
                'musical_key'  => 'Fm',
                'tags'         => 'grime,fast,aggressive',
                'title'        => 'East End',
                'description'  => 'Grime rapide et agressif.',
                'price'        => 30.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Trap mélodique
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 135,
                'musical_key'  => 'Bm',
                'tags'         => 'melodic,guitar,emo',
                'title'        => 'Broken Strings',
                'description'  => 'Trap mélodique avec guitare acoustique.',
                'price'        => 50.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Old school 90s
            [
                'user_id'      => 3,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 95,
                'musical_key'  => 'Gm',
                'tags'         => '90s,ny,jazz-rap',
                'title'        => 'Queensbridge',
                'description'  => 'Hommage au son New-Yorkais des années 90.',
                'price'        => 42.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        $this->db->table('beats')->insertBatch($beats);
    }
}
