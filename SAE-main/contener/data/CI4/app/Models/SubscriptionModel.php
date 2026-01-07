<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionModel extends Model
{
    protected $table      = 'subscriptions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id','type','status',
        'commission_percent','buyer_discount_percent','monthly_credit_cents',
        'started_at','ends_at'
    ];
    protected $useTimestamps = false;

    public function getActive(int $userId, string $type): ?array
    {
        return $this->where('user_id', $userId)
            ->where('type', $type)
            ->where('status', 'active')
            ->first();
    }

    public function getAnyActive(int $userId): ?array
    {
        return $this->where('user_id', $userId)
            ->where('status', 'active')
            ->first();
    }

}
