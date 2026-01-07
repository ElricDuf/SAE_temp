<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // base
        $this->call(UsersSeeder::class);
        $this->call(CategoriesSeeder::class);

        // beats + fichiers
        $this->call(BeatsSeeder::class);
        $this->call(BeatFilesSeeder::class);

        // features
        $this->call(FavoritesSeeder::class);

        // chat
        $this->call(ConversationsSeeder::class);
        $this->call(MessagesSeeder::class);

        // moderation
        $this->call(ModerationSeeder::class);

        // panier + achats
        $this->call(CartsSeeder::class);
        $this->call(CartItemsSeeder::class);
        $this->call(OrdersSeeder::class);
        $this->call(OrderItemsSeeder::class);

        // abonnements + wallet
        $this->call(SubscriptionsSeeder::class);
        $this->call(WalletsSeeder::class);
        $this->call(WalletTransactionsSeeder::class);
    }
}
