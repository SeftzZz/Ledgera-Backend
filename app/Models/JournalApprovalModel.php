<?php

namespace App\Models;

use CodeIgniter\Model;

class JournalApprovalModel extends Model
{
    protected $table = 'journal_approvals';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'journal_id',
        'level',
        'approved_by',
        'approved_at',
        'status',
        'note'
    ];
}
