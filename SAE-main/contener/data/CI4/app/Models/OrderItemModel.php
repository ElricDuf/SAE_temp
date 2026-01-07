<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table      = 'order_items';
    protected $primaryKey = ''; // composite
    protected $returnType = 'array';

    protected $allowedFields = ['order_id','beat_id','price'];
    protected $useTimestamps = false;
}
