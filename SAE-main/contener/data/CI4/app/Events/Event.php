<?php

namespace App\Events;

/**
 * Interface Event - Représente un événement observable
 * 
 * Tous les événements implémentent cette interface pour pouvoir être
 * passés aux observateurs de manière cohérente.
 */
interface Event
{
    /**
     * Retourne le type d'événement (AchatTerminé, etc.)
     */
    public function getType(): string;

    /**
     * Retourne les données associées à l'événement
     */
    public function getData(): array;
}
