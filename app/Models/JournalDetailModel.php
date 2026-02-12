<?php

namespace App\Models;

use CodeIgniter\Model;

class JournalDetailModel extends Model
{
    protected $table = 'journal_details';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'journal_id',
        'account_id',
        'debit',
        'credit'
    ];
}
