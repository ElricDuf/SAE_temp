<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'email',
        'username',
        'password_hash',
        'role',
        'avatar',         
        'artist_genre',  
        'created_at',
    ];

    protected $useTimestamps = false;

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', strtolower(trim($email)))->first();
    }

    public function findByUsername(string $username): ?array
    {
        return $this->where('username', trim($username))->first();
    }
}
