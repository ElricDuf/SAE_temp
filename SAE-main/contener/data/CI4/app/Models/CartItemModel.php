<?php

namespace App\Models;

use CodeIgniter\Model;

class CartItemModel extends Model
{
    protected $table      = 'cart_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['cart_id', 'beat_id', 'quantite', 'created_at'];
    protected $useTimestamps = false;

    public function getDetailedItems(int $cartId): array
    {
        return $this->db->table('cart_items ci')
            ->select('ci.id, ci.cart_id, ci.beat_id, ci.quantite, ci.created_at, b.title, b.price, b.status, b.buyer_id')
            ->join('beats b', 'b.id = ci.beat_id')
            ->where('ci.cart_id', $cartId)
            ->orderBy('ci.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function removeSoldItems(int $cartId): int
    {
        $rows = $this->db->table('cart_items ci')
            ->select('ci.id')
            ->join('beats b', 'b.id = ci.beat_id', 'inner')
            ->where('ci.cart_id', $cartId)
            ->groupStart()
                ->where('b.status !=', 'active')
                ->orWhere('b.buyer_id IS NOT NULL', null, false)
            ->groupEnd()
            ->get()
            ->getResultArray();

        if (empty($rows)) {
            return 0;
        }

        $ids = array_map(static fn($r) => (int)$r['id'], $rows);

        $this->whereIn('id', $ids)->delete();

        return $this->db->affectedRows();
    }

    public function countItems(int $cartId): int
    {
        $result = $this->selectSum('quantite')->where('cart_id', $cartId)->first();
        return (int) ($result['quantite'] ?? 0);
    }

    public function upsertIncrement(int $cartId, int $beatId, int $delta = 1): void
    {
        $row = $this->where('cart_id', $cartId)->where('beat_id', $beatId)->first();

        if ($row) {
            // si déjà dans le panier, on ne fait rien (quantité reste 1)
            return;
        }
        
        $this->insert([
            'cart_id'    => $cartId,
            'beat_id'    => $beatId,
            'quantite'   => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }


    public function removeLine(int $cartId, int $beatId): void
    {
        $this->where('cart_id', $cartId)->where('beat_id', $beatId)->delete();
    }

    public function clearCart(int $cartId): void
    {
        $this->where('cart_id', $cartId)->delete();
    }
}
