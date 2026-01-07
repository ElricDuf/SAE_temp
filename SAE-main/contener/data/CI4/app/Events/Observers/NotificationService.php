<?php

namespace App\Events\Observers;

use App\Events\Event;
use App\Events\Observer;

/**
 * NotificationService - Observateur du système de notifications
 * 
 * Responsabilités :
 * - Envoyer des notifications aux vendeurs
 * - Envoyer des confirmations aux acheteurs
 * - Créer des messages dans les conversations
 * 
 * Réagit à : AchatTermineEvent
 */
class NotificationService implements Observer
{
    /**
     * Réagit à un événement d'achat terminé
     * 
     * Envoie les notifications appropriées aux parties impliquées.
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

        // Notifier chaque vendeur
        foreach ($orderItems as $item) {
            $this->notifySeller(
                (int)$item['sellerId'],
                $buyerId,
                $item['beat_title'],
                $orderId
            );
        }

        // Notifier l'acheteur
        $this->notifyBuyer($buyerId, count($orderItems), $orderId);

        log_message('info', "NotificationService: Notifications envoyées pour commande #{$orderId}");
    }

    /**
     * Notifie un vendeur qu'un de ses beats a été vendu
     */
    private function notifySeller(int $sellerId, int $buyerId, string $beatTitle, int $orderId): void
    {
        $db = db_connect();
        $buyerInfo = $db->table('users')->select('username, email')->where('id', $buyerId)->get()->getRowArray();

        if (!$buyerInfo) {
            log_message('warning', "Buyer #{$buyerId} not found for seller notification");
            return;
        }

        $message = "Un acheteur ({$buyerInfo['username']}) vient d'acheter votre beat : \"{$beatTitle}\" - Commande #{$orderId}";

        // Créer une conversation de notification (ou l'utiliser existante)
        $this->createNotificationMessage($sellerId, $buyerId, $message);

        log_message('info', "Seller #{$sellerId} notified about beat \"{$beatTitle}\" sold");
    }

    /**
     * Notifie un acheteur que son achat est confirmé
     */
    private function notifyBuyer(int $buyerId, int $beatCount, int $orderId): void
    {
        $db = db_connect();
        $buyer = $db->table('users')->select('username, email')->where('id', $buyerId)->get()->getRowArray();

        if (!$buyer) {
            log_message('warning', "Buyer #{$buyerId} not found");
            return;
        }

        $message = "Votre achat de {$beatCount} beat(s) est confirmé ! Commande #{$orderId}. Vous pouvez désormais les télécharger.";

        // Vous pourriez aussi envoyer un email
        // $this->sendEmailConfirmation($buyer['email'], $message);

        log_message('info', "Buyer #{$buyerId} notified about purchase - Order #{$orderId}");
    }

    /**
     * Crée un message de notification dans les conversations
     */
    private function createNotificationMessage(int $senderId, int $recipientId, string $text): void
    {
        try {
            $db = db_connect();

            // Chercher ou créer une conversation
            $conversation = $db->table('conversations')
                ->where('(user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)', 
                    [$senderId, $recipientId, $recipientId, $senderId])
                ->get()
                ->getRowArray();

            if (!$conversation) {
                $db->table('conversations')->insert([
                    'user1_id'   => $senderId,
                    'user2_id'   => $recipientId,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $conversation = $db->table('conversations')
                    ->where('(user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)', 
                        [$senderId, $recipientId, $recipientId, $senderId])
                    ->get()
                    ->getRowArray();
            }

            if ($conversation) {
                $db->table('messages')->insert([
                    'conversation_id' => (int)$conversation['id'],
                    'sender_id'       => $senderId,
                    'content'         => $text,
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
            }
        } catch (\Throwable $e) {
            log_message('error', "Failed to create notification message: " . $e->getMessage());
        }
    }
}
