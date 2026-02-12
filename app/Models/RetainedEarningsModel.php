<?php

namespace App\Models;

use CodeIgniter\Model;

class RetainedEarningsModel extends Model
{
    protected $table = 'retained_earnings';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_id',
        'period_month',
        'period_year',
        'amount'
    ];
}
