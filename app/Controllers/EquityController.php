<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoaModel;
use App\Models\CoaOpeningBalanceModel;

class EquityController extends BaseController
{
    protected CoaModel $coaModel;
    protected CoaOpeningBalanceModel $openingModel;

    public function __construct()
    {
        $this->coaModel     = new CoaModel();
        $this->openingModel = new CoaOpeningBalanceModel();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $companyId = (int) session()->get('company_id');

        $builder = $this->coaModel
            ->where('account_type', 'equity');

        if ($companyId !== 0) {
            $builder->where('company_id', $companyId);
        }

        return view('accounting/equity/index', [
            'title'    => 'Equity',
            'equities' => $builder->orderBy('account_code', 'ASC')->findAll()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE EQUITY ACCOUNT
    |--------------------------------------------------------------------------
    */
    public function store()
    {
        $companyId  = (int) session()->get('company_id');
        $accountCode = trim($this->request->getPost('account_code'));

        // VALIDASI UNIK
        if ($this->coaModel
            ->where('company_id', $companyId)
            ->where('account_code', $accountCode)
            ->first()) {

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Account code already exists'
            ]);
        }

        $coaId = $this->coaModel->insert([
            'company_id'  => $companyId,
            'account_code'=> $accountCode,
            'account_name'=> $this->request->getPost('account_name'),
            'account_type'=> 'equity',
            'parent_id'   => $this->request->getPost('parent_id') ?: null,
            'is_active'   => $this->request->getPost('is_active') ?? 1,
            'created_at'  => date('Y-m-d H:i:s'),
            'created_by'  => session()->get('user_id')
        ], true);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Akun equity berhasil ditambahkan',
            'coa_id'  => $coaId
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | OPENING BALANCE VIEW
    |--------------------------------------------------------------------------
    */
    public function openingBalance()
    {
        $companyId = (int) session()->get('company_id');

        $accounts = $this->coaModel
            ->where('company_id', $companyId)
            ->where('is_active', 1)
            ->orderBy('account_code', 'ASC')
            ->findAll();

        return view('accounting/equity/opening_balance', [
            'title'    => 'Opening Balance',
            'accounts' => $accounts
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE OPENING BALANCE
    |--------------------------------------------------------------------------
    */
    public function saveOpeningBalance()
    {
        $companyId = (int) session()->get('company_id');
        $rows      = $this->request->getJSON(true);

        $totalDebit  = 0;
        $totalCredit = 0;

        foreach ($rows as $row) {
            $totalDebit  += (float) ($row['debit'] ?? 0);
            $totalCredit += (float) ($row['credit'] ?? 0);
        }

        // VALIDASI BALANCE
        if ($totalDebit !== $totalCredit) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Opening balance not balanced'
            ]);
        }

        // HAPUS LAMA
        $this->openingModel
            ->where('company_id', $companyId)
            ->delete();

        foreach ($rows as $row) {

            if (empty($row['debit']) && empty($row['credit'])) {
                continue;
            }

            $this->openingModel->insert([
                'coa_id'     => $row['coa_id'],
                'company_id' => $companyId,
                'debit'      => $row['debit'] ?? 0,
                'credit'     => $row['credit'] ?? 0,
                'period_year'=> date('Y')
            ]);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Opening Balance Saved'
        ]);
    }
}
