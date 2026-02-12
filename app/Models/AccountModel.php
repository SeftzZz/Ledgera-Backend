<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table = 'chart_of_accounts';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'company_id',
        'account_code',
        'account_name',
        'account_type',
        'parent_id',
        'cashflow_type',
        'is_active'
    ];

    public function getTree($companyId)
    {
        return $this->where('company_id', $companyId)
                    ->orderBy('account_code', 'ASC')
                    ->findAll();
    }
}
