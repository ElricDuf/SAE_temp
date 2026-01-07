<?php

namespace App\Models;

use CodeIgniter\Model;

class ConversationModel extends Model
{
    protected $table      = 'conversations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['beat_id','buyer_id','seller_id','created_at'];
    protected $useTimestamps = false;

    public function isParticipant(int $conversationId, int $userId): bool
    {
        return $this->where('id', $conversationId)
            ->groupStart()
                ->where('buyer_id', $userId)
                ->orWhere('seller_id', $userId)
            ->groupEnd()
            ->countAllResults() > 0;
    }

    public function getOrCreate(int $beatId, int $buyerId, int $sellerId): int
    {
        $existing = $this->where([
            'beat_id'   => $beatId,
            'buyer_id'  => $buyerId,
            'seller_id' => $sellerId,
        ])->first();

        if ($existing) {
            return (int) $existing['id'];
        }

        $this->insert([
            'beat_id' => $beatId,
            'buyer_id' => $buyerId,
            'seller_id' => $sellerId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return (int) $this->getInsertID();
    }

    /**
     * Liste des conversations d'un user (page /conversations)
     * - récupère infos du beat, du vendeur/acheteur
     * - récupère un aperçu du dernier message
     */
    public function listForUser(int $userId): array
    {
        // Subquery: dernier message par conversation (MAX(created_at))
        $lastMsgSub = $this->db->table('messages')
            ->select('conversation_id, MAX(created_at) AS last_created_at')
            ->groupBy('conversation_id');

        // Subquery join sur messages pour récupérer contenu du dernier message
        return $this->db->table('conversations c')
            ->select('c.id, c.beat_id, c.buyer_id, c.seller_id, c.created_at')
            ->select('b.title AS beat_title, b.status AS beat_status, b.buyer_id AS beat_buyer_id')
            ->select('buyer.username AS buyer_username, seller.username AS seller_username')
            ->select('m.content AS last_message, m.created_at AS last_message_at')
            ->join('beats b', 'b.id = c.beat_id', 'left')
            ->join('users buyer', 'buyer.id = c.buyer_id', 'left')
            ->join('users seller', 'seller.id = c.seller_id', 'left')
            ->join('(' . $lastMsgSub->getCompiledSelect() . ') lm', 'lm.conversation_id = c.id', 'left', false)
            ->join('messages m', 'm.conversation_id = c.id AND m.created_at = lm.last_created_at', 'left', false)
            ->groupStart()
                ->where('c.buyer_id', $userId)
                ->orWhere('c.seller_id', $userId)
            ->groupEnd()
            ->orderBy('lm.last_created_at', 'DESC')
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
