<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoaModel;
use App\Models\CoaOpeningBalanceModel;

class EquityController extends BaseController
{
    protected $coa;
    protected $opening;

    public function __construct()
    {
        $this->coa = new CoaModel();
        $this->opening = new CoaOpeningBalanceModel();
    }

    public function index()
    {
        $companyId = session()->get('company_id');

        $builder = $this->coa->where('account_type', 'equity');

        if ($companyId != 0) {
            $builder->where('company_id', $companyId);
        }

        $data = [
            'title'  => 'Equity',
            'equities' => $builder->findAll()
        ];


        return view('accounting/equity/index', $data);
    }

    public function store()
    {
        $companyId = session()->get('company_id');

        $coaId = $this->coa->insert([
            'company_id'  => $companyId,
            'account_code'=> $this->request->getPost('account_code'),
            'account_name'=> $this->request->getPost('account_name'),
            'account_type'=> 'equity',
            'parent_id'   => $this->request->getPost('parent_id'),
            'is_active'   => $this->request->getPost('is_active')
        ]);

        $this->opening->insert([
            'coa_id'          => $coaId,
            'company_id'      => $companyId,
            'opening_balance' => $this->request->getPost('opening_balance'),
            'period_year'     => date('Y')
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message'=> 'Akun berhasil ditambahkan'
        ]);
    }

    public function openingBalance()
    {
        $companyId = session()->get('company_id');

        $coaModel = new \App\Models\CoaModel();

        $accounts = $coaModel
            ->where('company_id', $companyId)
            ->where('is_active', 1)
            ->orderBy('account_code', 'ASC')
            ->findAll();

        return view('accounting/equity/opening_balance', [
            'title'  => 'Opening Balance',
            'accounts' => $accounts
        ]);
    }

    public function saveOpeningBalance()
    {
        $companyId = session()->get('company_id');
        $data = $this->request->getJSON(true);

        $model = new \App\Models\CoaOpeningBalanceModel();

        // delete existing first
        $model->where('company_id', $companyId)->delete();

        foreach ($data as $row) {

            if (empty($row['debit']) && empty($row['credit'])) {
                continue;
            }

            $model->insert([
                'coa_id'     => $row['coa_id'],
                'company_id' => $companyId,
                'debit'      => $row['debit'] ?? 0,
                'credit'     => $row['credit'] ?? 0
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Opening Balance Saved'
        ]);
    }



}

