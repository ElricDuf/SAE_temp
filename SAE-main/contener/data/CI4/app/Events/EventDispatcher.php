<?php

namespace App\Events;

/**
 * EventDispatcher - Sujet (Subject) du pattern Observer
 * 
 * Responsabilités :
 * - Enregistrer les observateurs
 * - Supprimer les observateurs
 * - Notifier les observateurs quand un événement se produit
 * 
 * C'est le cœur du pattern Observer : il maintient la liste des observateurs
 * et les informe en cas d'événement.
 */
class EventDispatcher
{
    /** @var Observer[] */
    private array $observers = [];

    /**
     * Enregistre un observateur pour qu'il soit notifié des événements
     */
    public function attach(Observer $observer): void
    {
        if (!in_array($observer, $this->observers, true)) {
            $this->observers[] = $observer;
        }
    }

    /**
     * Désenregistre un observateur
     */
    public function detach(Observer $observer): void
    {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    /**
     * Notifie tous les observateurs d'un événement
     * 
     * Chaque observateur reçoit l'événement et peut réagir de manière indépendante.
     * Les exceptions d'un observateur ne bloquent pas les autres.
     */
    public function notify(Event $event): void
    {
        foreach ($this->observers as $observer) {
            try {
                $observer->update($event);
            } catch (\Throwable $e) {
                // Log l'erreur mais continue la notification des autres observateurs
                log_message('error', "Observer failed for event {$event->getType()}: " . $e->getMessage());
            }
        }
    }

    /**
     * Retourne tous les observateurs enregistrés
     */
    public function getObservers(): array
    {
        return $this->observers;
    }

    /**
     * Compte le nombre d'observateurs
     */
    public function countObservers(): int
    {
        return count($this->observers);
    }
}
