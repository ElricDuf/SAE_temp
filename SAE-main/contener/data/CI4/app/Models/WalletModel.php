<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table      = 'wallets';
    protected $primaryKey = 'user_id';
    protected $returnType = 'array';

    protected $allowedFields = ['user_id','balance_cents','updated_at'];
    protected $useTimestamps = false;
}
