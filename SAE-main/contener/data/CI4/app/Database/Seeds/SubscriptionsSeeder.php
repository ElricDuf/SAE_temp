<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubscriptionsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        if ($this->db->table('subscriptions')->countAllResults() > 0) {
            return;
        }

        $subs = [
            // alice : abonnement beatmaker (0% commission)
            [
                'user_id' => 2,
                'type' => 'beatmaker',
                'status' => 'active',
                'commission_percent' => 0,
                'buyer_discount_percent' => 0,
                'monthly_credit_cents' => 0,
                'started_at' => $now,
                'ends_at' => null,
            ],
            // bob : abonnement interprète (10% réduction + 20€ crédit / mois)
            [
                'user_id' => 3,
                'type' => 'interpreter',
                'status' => 'active',
                'commission_percent' => 20,
                'buyer_discount_percent' => 10,
                'monthly_credit_cents' => 2000,
                'started_at' => $now,
                'ends_at' => null,
            ],
        ];

        $this->db->table('subscriptions')->insertBatch($subs);
    }
}
