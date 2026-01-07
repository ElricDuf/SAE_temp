<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtistSpotlightModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    // Top vendeurs = le plus de beats vendus (buyer_id NOT NULL)
    public function getTopSellers(int $limit = 8): array
    {
        return $this->db->table('beats b')
            ->select('u.id as user_id, u.username, u.avatar, u.artist_genre, COUNT(b.id) as sold_count')
            ->join('users u', 'u.id = b.user_id')
            ->where('b.buyer_id IS NOT NULL', null, false)
            ->groupBy('b.user_id')
            ->orderBy('sold_count', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    // Top posteurs = le plus de beats actifs dispo (status active + buyer_id NULL)
    public function getTopPosters(int $limit = 8): array
    {
        return $this->db->table('beats b')
            ->select('u.id as user_id, u.username, u.avatar, u.artist_genre, COUNT(b.id) as available_count')
            ->join('users u', 'u.id = b.user_id')
            ->where('b.status', 'active')
            ->where('b.buyer_id', null)
            ->groupBy('b.user_id')
            ->orderBy('available_count', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    public function getSoldBeatsForUsers(array $userIds, int $perUser = 6): array
    {
        if (empty($userIds)) return [];

        $rows = $this->db->table('beats b')
            ->select('b.id, b.user_id, b.title, b.price, c.name as category_name')
            ->join('categories c', 'c.id = b.category_id')
            ->whereIn('b.user_id', $userIds)
            ->where('b.buyer_id IS NOT NULL', null, false)
            ->orderBy('b.sold_at', 'DESC')
            ->get()->getResultArray();

        return $this->groupLimitPerUser($rows, $perUser);
    }

    public function getAvailableBeatsForUsers(array $userIds, int $perUser = 6): array
    {
        if (empty($userIds)) return [];

        $rows = $this->db->table('beats b')
            ->select('b.id, b.user_id, b.title, b.price, c.name as category_name')
            ->join('categories c', 'c.id = b.category_id')
            ->whereIn('b.user_id', $userIds)
            ->where('b.status', 'active')
            ->where('b.buyer_id', null)
            ->orderBy('b.created_at', 'DESC')
            ->get()->getResultArray();

        return $this->groupLimitPerUser($rows, $perUser);
    }

    private function groupLimitPerUser(array $rows, int $perUser): array
    {
        $grouped = [];
        foreach ($rows as $r) {
            $uid = (int)$r['user_id'];
            $grouped[$uid] ??= [];
            if (count($grouped[$uid]) < $perUser) {
                $grouped[$uid][] = $r;
            }
        }
        return $grouped;
    }
}
