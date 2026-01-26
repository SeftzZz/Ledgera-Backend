<?php

namespace App\Models;

use CodeIgniter\Model;

class JobModel extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'hotel_id',
        'position',
        'job_date_start',
        'job_date_end',
        'start_time',
        'end_time',
        'category',
        'fee',
        'location',
        'description',
        'requirement_skill',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    protected $useTimestamps = false;
}
