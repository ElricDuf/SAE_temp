<?php

namespace App\Events;

/**
 * Événement : AchatTerminé
 * 
 * Déclenché après qu'un achat soit complété avec succès.
 * Contient toutes les informations nécessaires pour que les observateurs
 * puissent réagir (notification, stock, etc.).
 */
class AchatTermineEvent implements Event
{
    private int $orderId;
    private int $buyerId;
    private array $orderItems; // [{beatId, sellerId, beat_title, price_cents}, ...]

    public function __construct(int $orderId, int $buyerId, array $orderItems)
    {
        $this->orderId = $orderId;
        $this->buyerId = $buyerId;
        $this->orderItems = $orderItems;
    }

    public function getType(): string
    {
        return 'achat_termine';
    }

    public function getData(): array
    {
        return [
            'orderId'     => $this->orderId,
            'buyerId'     => $this->buyerId,
            'orderItems'  => $this->orderItems,
        ];
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getBuyerId(): int
    {
        return $this->buyerId;
    }

    public function getOrderItems(): array
    {
        return $this->orderItems;
    }
}
