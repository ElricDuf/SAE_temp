<?php

namespace App\Events\Observers;

use App\Events\Event;
use App\Events\Observer;

/**
 * InventoryManager - Observateur du système de stock
 * 
 * Responsabilités :
 * - Gérer l'inventaire après une vente
 * - Marquer les beats comme vendus
 * 
 * Réagit à : AchatTermineEvent
 */
class InventoryManager implements Observer
{
    /**
     * Réagit à un événement d'achat terminé
     * 
     * Ici on gère tout ce qui concerne le stock et l'inventaire.
     */
    public function update(Event $event): void
    {
        if ($event->getType() !== 'achat_termine') {
            return;
        }

        $data = $event->getData();
        $orderItems = $data['orderItems'];
        $buyerId = $data['buyerId'];
        $orderId = $data['orderId'];

        // Marquer les beats comme vendus (si ce n'était pas déjà fait dans le CartController)
        
        foreach ($orderItems as $item) {
            $this->markBeatAsSold(
                (int)$item['beatId'],
                $buyerId,
                $orderId
            );
        }

        log_message('info', "InventoryManager: {$buyerId} a acheté " . count($orderItems) . " beat(s) - Commande #{$orderId}");
    }

    /**
     * Marque un beat comme vendu
     */
    private function markBeatAsSold(int $beatId, int $buyerId, int $orderId): void
    {
        $db = db_connect();
        
        // Vérification supplémentaire que le beat n'est pas déjà vendu
        $beat = $db->table('beats')
            ->select('id, buyer_id')
            ->where('id', $beatId)
            ->get()
            ->getRowArray();

        if ($beat && empty($beat['buyer_id'])) {
            $db->table('beats')->where('id', $beatId)->update([
                'buyer_id' => $buyerId,
                'sold_at'  => date('Y-m-d H:i:s'),
            ]);

            log_message('info', "Beat #{$beatId} marqué comme vendu par buyer #{$buyerId}");
        }
    }
}
