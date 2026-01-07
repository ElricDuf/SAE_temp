<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeModel extends Model
{
    /**
     * Derniers beats (pour l'accueil)
     * Joint categories + users, et récupère le preview_mp3 via beat_files.
     */
    public function getLatestBeats(int $limit = 6): array
    {
        return $this->db->table('beats b')
            ->select('b.id, b.title, b.price, b.created_at, b.status, b.bpm, b.musical_key, b.is_featured')
            ->select('c.name AS category_name, u.username AS seller_username')
            ->select('bf.path AS preview_path')
            ->join('categories c', 'c.id = b.category_id', 'left')
            ->join('users u', 'u.id = b.user_id', 'left')
            ->join('beat_files bf', "bf.beat_id = b.id AND bf.type = 'preview_mp3'", 'left')
            ->where('b.status', 'active')
            ->where('b.buyer_id', null)
            ->orderBy('b.is_featured', 'DESC')
            ->orderBy('b.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Top catégories (genres) par nombre de beats actifs
     */
    public function getTopCategories(int $limit = 6): array
    {
        return $this->db->table('categories c')
            ->select('c.id, c.name, c.slug, COUNT(b.id) AS beats_count')
            ->join('beats b', 'b.category_id = c.id', 'left')
            ->where('b.status', 'active')
            ->where('b.buyer_id', null)
            ->groupBy('c.id')
            ->orderBy('beats_count', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Stats simples pour l'accueil
     */
    public function getStats(): array
    {
        $totalBeats = (int) $this->db->table('beats')->countAllResults();

        $activeBeats = (int) $this->db->table('beats')
            ->where('status', 'active')
            ->where('buyer_id', null)
            ->countAllResults();

        $soldBeats = (int) $this->db->table('beats')
            ->where('status', 'sold')
            ->countAllResults();

        $totalUsers = (int) $this->db->table('users')->countAllResults();

        return [
            'total_beats'  => $totalBeats,
            'active_beats' => $activeBeats,
            'sold_beats'   => $soldBeats,
            'total_users'  => $totalUsers,
        ];
    }
}
