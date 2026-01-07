<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletTransactionModel extends Model
{
    protected $table      = 'wallet_transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['user_id','amount_cents','reason','created_at'];
    protected $useTimestamps = false;
}
