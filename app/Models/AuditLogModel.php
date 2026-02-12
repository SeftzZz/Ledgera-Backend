<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_id',
        'table_name',
        'record_id',
        'action',
        'old_data',
        'new_data',
        'user_id'
    ];
}
