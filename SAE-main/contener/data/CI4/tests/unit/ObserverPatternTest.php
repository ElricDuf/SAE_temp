<?php

namespace Tests\Unit;

use App\Events\EventDispatcher;
use App\Events\AchatTermineEvent;
use App\Events\Observers\InventoryManager;
use App\Events\Observers\NotificationService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * Tests du Pattern Observer
 * 
 * Démontre comment tester les observateurs indépendamment
 */
class ObserverPatternTest extends CIUnitTestCase
{
    /**
     * Test : Le dispatcher appelle les observateurs
     */
    public function testDispatcherNotifiesAllObservers()
    {
        $dispatcher = new EventDispatcher();

        // Mock observer
        $mockObserver = $this->createMock(Observer::class);
        $mockObserver->expects($this->once())
                     ->method('update');

        $dispatcher->attach($mockObserver);

        $event = new AchatTermineEvent(1, 1, []);
        $dispatcher->notify($event);
    }

    /**
     * Test : EventDispatcher enregistre les observateurs
     */
    public function testDispatcherAttachesObservers()
    {
        $dispatcher = new EventDispatcher();
        $observer1 = new InventoryManager();
        $observer2 = new NotificationService();

        $dispatcher->attach($observer1);
        $dispatcher->attach($observer2);

        $this->assertEquals(2, $dispatcher->countObservers());
    }

    /**
     * Test : EventDispatcher supprime les observateurs
     */
    public function testDispatcherDetachesObservers()
    {
        $dispatcher = new EventDispatcher();
        $observer = new InventoryManager();

        $dispatcher->attach($observer);
        $this->assertEquals(1, $dispatcher->countObservers());

        $dispatcher->detach($observer);
        $this->assertEquals(0, $dispatcher->countObservers());
    }

    /**
     * Test : AchatTermineEvent contient les bonnes données
     */
    public function testAchatTermineEventCarriesData()
    {
        $orderId = 123;
        $buyerId = 456;
        $orderItems = [
            ['beatId' => 1, 'sellerId' => 10, 'beat_title' => 'Beat 1', 'price_cents' => 9999],
            ['beatId' => 2, 'sellerId' => 20, 'beat_title' => 'Beat 2', 'price_cents' => 4999],
        ];

        $event = new AchatTermineEvent($orderId, $buyerId, $orderItems);

        $this->assertEquals('achat_termine', $event->getType());
        $this->assertEquals($orderId, $event->getOrderId());
        $this->assertEquals($buyerId, $event->getBuyerId());
        $this->assertCount(2, $event->getOrderItems());
    }

    /**
     * Test : InventoryManager peut être testé isolément
     */
    public function testInventoryManagerProcessesEvent()
    {
        // Créer un événement avec des données de test
        $event = new AchatTermineEvent(
            1,
            1,
            [
                ['beatId' => 1, 'sellerId' => 10, 'beat_title' => 'Test Beat', 'price_cents' => 5000],
            ]
        );

        $manager = new InventoryManager();

        // Ceci appelerait la logique métier du manager
        // Si vous utilisez des mocks de DB, vous pouvez vérifier les appels
        $manager->update($event);

        // Assertions spécifiques selon votre implémentation
    }

    /**
     * Test : NotificationService peut être testé isolément
     */
    public function testNotificationServiceProcessesEvent()
    {
        $event = new AchatTermineEvent(
            1,
            1,
            [
                ['beatId' => 1, 'sellerId' => 10, 'beat_title' => 'Test Beat', 'price_cents' => 5000],
            ]
        );

        $service = new NotificationService();

        // Ceci appelerait la logique métier du service
        // Si vous utilisez des mocks de DB, vous pouvez vérifier les appels
        $service->update($event);

        // Assertions spécifiques selon votre implémentation
    }

    /**
     * Test : Les exceptions d'un observateur n'affectent pas les autres
     */
    public function testExceptionInOneObserverDoesntBlockOthers()
    {
        $dispatcher = new EventDispatcher();

        // Observer qui lève une exception
        $throwingObserver = $this->createMock(Observer::class);
        $throwingObserver->method('update')
                        ->will($this->throwException(new \Exception('Test error')));

        // Observer normal
        $normalObserver = $this->createMock(Observer::class);
        $normalObserver->expects($this->once())
                       ->method('update');

        $dispatcher->attach($throwingObserver);
        $dispatcher->attach($normalObserver);

        $event = new AchatTermineEvent(1, 1, []);

        // Aucune exception ne devrait être levée
        $dispatcher->notify($event);

        // Le deuxième observateur devrait avoir été appelé
        // même si le premier a levé une exception
    }
}
