<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountingPeriodModel extends Model
{
    protected $table = 'accounting_periods';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_id',
        'period_month',
        'period_year',
        'is_closed',
        'closed_at'
    ];
}
