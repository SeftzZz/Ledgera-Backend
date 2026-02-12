<?php

namespace App\Models;

use CodeIgniter\Model;

class SubLedgerModel extends Model
{
    protected $table = 'sub_ledgers';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_id',
        'partner_id',
        'journal_detail_id',
        'account_type',
        'amount'
    ];
}
