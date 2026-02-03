<?php

namespace App\Models;

use CodeIgniter\Model;

class EducationModel extends Model
{
    protected $table = 'worker_educations';
    protected $allowedFields = [
        'user_id',
        'level',
        'title',
        'instituted_name',
        'start_date',
        'end_date',
        'is_current',
        'sort_order',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at'
    ];
}
