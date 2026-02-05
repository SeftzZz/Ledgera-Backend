<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'role','hotel_id','name','email','phone','password','provider','provider_id','photo','is_active','is_verified','created_at',
        'created_by','updated_at','updated_by','deleted_at','deleted_by'
    ];

    protected $useTimestamps = true;
}
