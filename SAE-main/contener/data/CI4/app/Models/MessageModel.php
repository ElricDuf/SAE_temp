<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table      = 'messages';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'conversation_id','sender_id','content','created_at','read_at'
    ];
    protected $useTimestamps = false;

    public function findByConversation(int $conversationId): array
    {
        return $this->db->table('messages m')
            ->select('m.*, u.username')
            ->join('users u', 'u.id = m.sender_id', 'left')
            ->where('m.conversation_id', $conversationId)
            ->orderBy('m.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }
}
