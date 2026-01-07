<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table      = 'orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'buyer_id','guest_email','status','total','created_at','paid_at'
    ];
    protected $useTimestamps = false;
}
