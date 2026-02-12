<?php

namespace App\Models;

use CodeIgniter\Model;

class CoaModel extends Model
{
    protected $table            = 'coa';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'company_id',
        'account_code',
        'account_name',
        'account_type',
        'parent_id',
        'cashflow_type',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    protected $useTimestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function getByCompany($companyId)
    {
        return $this->where('company_id', $companyId)
                    ->orderBy('account_code', 'ASC')
                    ->findAll();
    }

    public function getEquityAccounts($companyId)
    {
        return $this->where('company_id', $companyId)
                    ->where('account_type', 'equity')
                    ->where('is_active', 1)
                    ->orderBy('account_code', 'ASC')
                    ->findAll();
    }
}
