<?php

namespace App\Models;

use CodeIgniter\Model;

class ApprovalFlowModel extends Model
{
    protected $table = 'approval_flows';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_id',
        'module',
        'level',
        'role_name'
    ];
}
