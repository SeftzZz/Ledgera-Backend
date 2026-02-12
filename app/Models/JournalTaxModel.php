<?php

namespace App\Models;

use CodeIgniter\Model;

class JournalTaxModel extends Model
{
    protected $table = 'journal_taxes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'journal_id',
        'tax_id',
        'tax_base',
        'tax_amount'
    ];
}
