<?php

namespace App\Models;

use CodeIgniter\Model;

class JournalHeaderModel extends Model
{
    protected $table = 'journal_headers';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_id',
        'branch_id',
        'fiscal_year_id',
        'journal_no',
        'journal_date',
        'description',
        'period_month',
        'period_year',
        'status',
        'is_locked',
        'reversal_of',
        'reverse_date'
    ];
}
