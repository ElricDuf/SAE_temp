<?php

namespace App\Models;

use CodeIgniter\Model;

class FavoriteModel extends Model
{
    protected $table      = 'favorites';
    protected $primaryKey = ''; // composite (user_id, beat_id)
    protected $returnType = 'array';

    protected $allowedFields = ['user_id','beat_id','created_at'];
    protected $useTimestamps = false;

    public function isFavorite(int $userId, int $beatId): bool
    {
        return $this->where('user_id', $userId)
            ->where('beat_id', $beatId)
            ->countAllResults() > 0;
    }

    /**
     * Toggle favori :
     * - si existe -> supprime et retourne false
     * - sinon -> ajoute et retourne true
     */
    public function toggle(int $userId, int $beatId): bool
    {
        $exists = $this->db->table($this->table)
            ->where('user_id', $userId)
            ->where('beat_id', $beatId)
            ->countAllResults();

        if ($exists > 0) {
            $this->db->table($this->table)
                ->where('user_id', $userId)
                ->where('beat_id', $beatId)
                ->delete();

            return false; // plus en favori
        }

        $this->db->table($this->table)->insert([
            'user_id' => $userId,
            'beat_id' => $beatId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return true; // maintenant en favori
    }
}
