<?php

namespace App\Events;

/**
 * Interface Observer - Patron Observateur
 * 
 * Les observateurs écoutent les événements et réagissent de manière indépendante.
 * Permet un couplage faible entre les modules.
 */
interface Observer
{
    /**
     * Appelé quand un événement observé se produit
     * 
     * @param Event $event L'événement qui s'est produit
     */
    public function update(Event $event): void;
}
