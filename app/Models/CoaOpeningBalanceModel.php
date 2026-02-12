<?php

namespace App\Models;

use CodeIgniter\Model;

class CoaOpeningBalanceModel extends Model
{
    protected $table            = 'coa_opening_balances';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields = [
        'company_id',
        'coa_id',
        'opening_balance',
        'period_year'
    ];

    protected $useTimestamps = true;

    /*
    |--------------------------------------------------------------------------
    | Get balance
    |--------------------------------------------------------------------------
    */
    public function getBalance($companyId, $coaId, $year)
    {
        return $this->where([
            'company_id' => $companyId,
            'coa_id'     => $coaId,
            'period_year'=> $year
        ])->first();
    }
}
