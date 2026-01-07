<?php

namespace App\Models;

use CodeIgniter\Model;

class ModerationCaseModel extends Model
{
    protected $table      = 'moderation_cases';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'beat_id','reporter_id','reason','status','created_at','closed_at','closed_by'
    ];
    protected $useTimestamps = false;
}
