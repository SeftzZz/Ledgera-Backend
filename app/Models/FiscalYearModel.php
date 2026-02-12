<?php

namespace App\Models;

use CodeIgniter\Model;

class FiscalYearModel extends Model
{
    protected $table = 'fiscal_years';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_id',
        'year_name',
        'start_date',
        'end_date',
        'is_active'
    ];
}