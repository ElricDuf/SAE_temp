<?php

namespace App\Models;

use CodeIgniter\Model;

class BeatModel extends Model
{
    protected $table      = 'beats';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id','category_id','bpm','musical_key','tags',
        'title','description','price',
        'status','buyer_id','sold_at',
        'is_featured','created_at','updated_at'
    ];

    protected $useTimestamps = false;

    public function findActiveById(int $id): ?array
    {
        return $this->where('id', $id)
            ->where('status', 'active')
            ->where('buyer_id', null)
            ->first();
    }

    public function markAsSold(int $beatId, int $buyerId): bool
    {
        $builder = $this->db->table($this->table);

        $builder->where('id', $beatId);
        $builder->where('status', 'active');
        $builder->where('buyer_id', null);

        $builder->update([
            'status'     => 'sold',
            'buyer_id'   => $buyerId,
            'sold_at'    => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->db->affectedRows() === 1;
    }

    /**
     * Feed par défaut (boutique sans recherche):
     * - beats actifs non vendus
     * - tri: featured puis récent
     */
    public function getDefaultFeed(int $limit = 30): array
    {
        return $this->db->table('beats b')
            ->select('b.*, c.name AS category_name, u.username AS seller_username')
            ->join('categories c', 'c.id = b.category_id', 'left')
            ->join('users u', 'u.id = b.user_id', 'left')
            ->where('b.status', 'active')
            ->where('b.buyer_id', null)
            ->orderBy('b.is_featured', 'DESC')
            ->orderBy('b.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Un beat avec joins (page produit)
     */
    public function getOneWithJoins(int $id): ?array
    {
        $row = $this->db->table('beats b')
            ->select('b.*, c.name AS category_name, u.username AS seller_username')
            ->join('categories c', 'c.id = b.category_id', 'left')
            ->join('users u', 'u.id = b.user_id', 'left')
            ->where('b.id', $id)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    /**
     * Recherche avancée (simple) — seulement si "do_search=1"
     */
    public function search(array $filters): array
    {
        $builder = $this->db->table('beats b');
        $builder->select('b.*, c.name AS category_name, u.username AS seller_username');
        $builder->join('categories c', 'c.id = b.category_id', 'left');
        $builder->join('users u', 'u.id = b.user_id', 'left');
        $builder->where('b.status', 'active');
        $builder->where('b.buyer_id', null);

        if (!empty($filters['q'])) {
            $q = trim((string)$filters['q']);
            $builder->groupStart()
                ->like('b.title', $q)
                ->orLike('b.tags', $q)
                ->orLike('b.description', $q)
            ->groupEnd();
        }

        if (!empty($filters['category_id'])) {
            $builder->where('b.category_id', (int)$filters['category_id']);
        }

        if (!empty($filters['bpm_min'])) {
            $builder->where('b.bpm >=', (int)$filters['bpm_min']);
        }
        if (!empty($filters['bpm_max'])) {
            $builder->where('b.bpm <=', (int)$filters['bpm_max']);
        }

        if (!empty($filters['musical_key'])) {
            $builder->where('b.musical_key', (string)$filters['musical_key']);
        }

        $builder->orderBy('b.is_featured', 'DESC');
        $builder->orderBy('b.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function findBySeller(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function findPurchasedByBuyer(int $userId): array
    {
        return $this->where('buyer_id', $userId)
            ->orderBy('sold_at', 'DESC')
            ->findAll();
    }
}
