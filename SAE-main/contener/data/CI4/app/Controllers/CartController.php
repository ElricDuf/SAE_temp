<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Events\EventDispatcher;
use App\Events\AchatTermineEvent;
use App\Events\Observers\InventoryManager;
use App\Events\Observers\NotificationService;

class CartController extends BaseController
{
    private const COOKIE_NAME = 'tempo_cart';
    private const COOKIE_DAYS = 30;

    public function show()
    {
        [$cartId, $isLoggedIn] = $this->getOrCreateCartId();

        $itemModel = new CartItemModel();

        $itemModel->removeSoldItems($cartId);

        $rows = $itemModel->getDetailedItems($cartId);

        $items = [];
        $total = 0.0;
        $hasUnavailable = false;

        foreach ($rows as $r) {
            $qty = (int)$r['quantite'];
            $price = (float)$r['price'];
            $line = $qty * $price;

            $isAvailable = ($r['status'] === 'active' && empty($r['buyer_id']));
            if (!$isAvailable) $hasUnavailable = true;

            // total uniquement sur les beats achetables
            if ($isAvailable) {
                $total += $line;
            }

            $items[] = $r;
        }

        return view('cart/show', [
            'items'          => $items,
            'total'          => $total,
            'hasUnavailable' => $hasUnavailable,
            'isLoggedIn'     => $isLoggedIn,
        ]);
    }

    public function add(int $beatId)
    {
        [$cartId] = $this->getOrCreateCartId();

        // Vérif disponibilité du beat
        $beat = db_connect()->table('beats')
            ->select('id, status, buyer_id')
            ->where('id', $beatId)
            ->get()->getRowArray();

        if (!$beat) {
            return redirect()->to('/cart')->with('error', 'Beat introuvable.');
        }

        $isAvailable = ($beat['status'] === 'active' && empty($beat['buyer_id']));
        if (!$isAvailable) {
            return redirect()->to('/cart')->with('error', 'Ce beat n’est plus disponible.');
        }

        $itemModel = new CartItemModel();
        $itemModel->upsertIncrement($cartId, $beatId, 1);

        db_connect()->table('carts')->where('id', $cartId)->update(['updated_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('/cart')->with('success', 'Ajouté au panier.');
    }

    public function remove(int $beatId)
    {
        [$cartId] = $this->getOrCreateCartId();

        $itemModel = new CartItemModel();
        $itemModel->upsertIncrement($cartId, $beatId, -1);

        db_connect()->table('carts')->where('id', $cartId)->update(['updated_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('/cart');
    }

    public function removeLine(int $beatId)
    {
        [$cartId] = $this->getOrCreateCartId();

        $itemModel = new CartItemModel();
        $itemModel->removeLine($cartId, $beatId);

        db_connect()->table('carts')->where('id', $cartId)->update(['updated_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('/cart');
    }

    public function checkoutForm()
    {
        $isLoggedIn = (bool)session()->get('isLoggedIn');
        if (!$isLoggedIn) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour finaliser l’achat.');
        }

        [$cartId] = $this->getOrCreateCartId();

        $itemModel = new CartItemModel();
        $rows = $itemModel->getDetailedItems($cartId);

        $total = 0.0;
        $hasUnavailable = false;

        foreach ($rows as $r) {
            $isAvailable = ($r['status'] === 'active' && empty($r['buyer_id']));
            if (!$isAvailable) $hasUnavailable = true;
            if ($isAvailable) $total += (float)$r['price'] * (int)$r['quantite'];
        }

        return view('cart/checkout', [
            'total'          => $total,
            'hasUnavailable' => $hasUnavailable,
        ]);
    }

    public function checkout()
    {
        $userId = (int)(session()->get('user_id') ?? 0);
        $isLoggedIn = (bool)session()->get('isLoggedIn');

        if (!$isLoggedIn || $userId <= 0) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour finaliser l’achat.');
        }

        [$cartId] = $this->getOrCreateCartId();

        $db = db_connect();
        $itemModel = new CartItemModel();
        $rows = $itemModel->getDetailedItems($cartId);

        if (empty($rows)) {
            return redirect()->to('/cart')->with('error', 'Panier vide.');
        }

        // Vérif dispo
        foreach ($rows as $r) {
            $isAvailable = ($r['status'] === 'active' && empty($r['buyer_id']));
            if (!$isAvailable) {
                return redirect()->to('/cart')->with('error', 'Ton panier contient un beat indisponible. Retire-le avant de payer.');
            }
        }

        $db->transBegin();
        try {
            $totalCents = 0;
            $orderItems = [];

            foreach ($rows as $r) {
                $totalCents += (int)round(((float)$r['price']) * 100) * (int)$r['quantite'];
            }

            // Create order
            $db->table('orders')->insert([
                'user_id'     => $userId,
                'guest_email' => null,
                'guest_token' => null,
                'total_cents' => $totalCents,
                'status'      => 'paid',
                'created_at'  => date('Y-m-d H:i:s'),
                'paid_at'     => date('Y-m-d H:i:s'),
            ]);
            $orderId = (int)$db->insertID();

            // Insert order_items + mark beats sold (1 vente unique)
            foreach ($rows as $r) {
                $beatId = (int)$r['beat_id'];

                $fresh = $db->table('beats')->select('id, user_id, title, status, buyer_id, price')
                    ->where('id', $beatId)->get()->getRowArray();

                if (!$fresh || $fresh['status'] !== 'active' || !empty($fresh['buyer_id'])) {
                    throw new \RuntimeException("Le beat #$beatId vient d’être vendu/retiré.");
                }

                $priceCents = (int)round(((float)$fresh['price']) * 100);

                $db->table('order_items')->insert([
                    'order_id'    => $orderId,
                    'beat_id'     => $beatId,
                    'seller_id'   => (int)$fresh['user_id'],
                    'beat_title'  => $fresh['title'],
                    'price_cents' => $priceCents,
                    'created_at'  => date('Y-m-d H:i:s'),
                ]);

                // Ajouter aux infos de l'événement
                $orderItems[] = [
                    'beatId'      => $beatId,
                    'sellerId'    => (int)$fresh['user_id'],
                    'beat_title'  => $fresh['title'],
                    'price_cents' => $priceCents,
                ];
            }

            // Clear cart
            $itemModel->clearCart($cartId);
            $db->table('carts')->where('id', $cartId)->update(['updated_at' => date('Y-m-d H:i:s')]);

            $db->transCommit();

            // ===== PATTERN OBSERVER =====
            // Créer l'événement d'achat terminé
            $event = new AchatTermineEvent($orderId, $userId, $orderItems);

            // Créer le dispatcher et enregistrer les observateurs
            $dispatcher = new EventDispatcher();
            $dispatcher->attach(new InventoryManager());
            $dispatcher->attach(new NotificationService());

            // Notifier tous les observateurs
            $dispatcher->notify($event);
            // ===== FIN PATTERN OBSERVER =====

        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/cart')->with('error', 'Checkout impossible : ' . $e->getMessage());
        }

        return redirect()->to('/cart')->with('success', 'Achat effectué !');
    }


    /**
     * Retourne [cartId, isLoggedIn]
     */
    private function getOrCreateCartId(): array
    {
        $session = session();
        $isLoggedIn = (bool)$session->get('isLoggedIn');
        $userId = (int)($session->get('user_id') ?? 0);

        $db = db_connect();

        // guest token cookie
        $guestToken = (string)($this->request->getCookie(self::COOKIE_NAME) ?? '');

        if ($isLoggedIn && $userId > 0) {
            // cart user
            $userCart = $db->table('carts')->where('user_id', $userId)->get()->getRowArray();

            if (!$userCart) {
                $db->table('carts')->insert([
                    'user_id'     => $userId,
                    'guest_token' => null,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
                $userCart = $db->table('carts')->where('user_id', $userId)->get()->getRowArray();
            }

            // merge guest cart dans user cart 
            if (!empty($guestToken)) {
                $guestCart = $db->table('carts')->where('guest_token', $guestToken)->get()->getRowArray();
                if ($guestCart && (int)$guestCart['id'] !== (int)$userCart['id']) {
                    $itemModel = new CartItemModel();
                    $guestItems = $itemModel->where('cart_id', (int)$guestCart['id'])->findAll();

                    foreach ($guestItems as $gi) {
                        $itemModel->upsertIncrement((int)$userCart['id'], (int)$gi['beat_id'], (int)$gi['quantite']);
                    }

                    // delete guest cart + items
                    $itemModel->clearCart((int)$guestCart['id']);
                    $db->table('carts')->where('id', (int)$guestCart['id'])->delete();
                }
            }

            return [(int)$userCart['id'], true];
        }

        // Guest flow
        if (empty($guestToken)) {
            $guestToken = bin2hex(random_bytes(16));
            $this->response->setCookie([
                'name'   => self::COOKIE_NAME,
                'value'  => $guestToken,
                'expire' => self::COOKIE_DAYS * 24 * 60 * 60,
                'path'   => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }

        $guestCart = $db->table('carts')->where('guest_token', $guestToken)->get()->getRowArray();

        if (!$guestCart) {
            $db->table('carts')->insert([
                'user_id'     => null,
                'guest_token' => $guestToken,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $guestCart = $db->table('carts')->where('guest_token', $guestToken)->get()->getRowArray();
        }

        return [(int)$guestCart['id'], false];
    }
}
