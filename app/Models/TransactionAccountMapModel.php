<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionAccountMapModel extends Model
{
    protected $table = 'transaction_account_map';
    protected $primaryKey = null;
    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'trx_type',
        'debit_account_id',
        'credit_account_id'
    ];
}
