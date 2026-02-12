<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoaModel;
use App\Models\CompanyModel;
use App\Models\CoaOpeningBalanceModel;

class CoaController extends BaseController
{
    protected CoaModel $coaModel;
    protected CompanyModel $companyModel;
    protected CoaOpeningBalanceModel $openingModel;

    public function __construct()
    {
        $this->coaModel      = new CoaModel();
        $this->companyModel  = new CompanyModel();
        $this->openingModel  = new CoaOpeningBalanceModel();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view('coa/index', [
            'title'     => 'COA',
            'companies' => $this->companyModel
                ->where('deleted_at', null)
                ->orderBy('company_name', 'ASC')
                ->findAll()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */
    public function datatable()
    {
        $request      = service('request');
        $companyId    = (int) session()->get('company_id');
        $searchValue  = $request->getPost('search')['value'] ?? null;
        $length       = (int) $request->getPost('length');
        $start        = (int) $request->getPost('start');
        $draw         = (int) $request->getPost('draw');
        $order        = $request->getPost('order');

        $orderColumns = [
            null,
            null,
            'companies.company_name',
            'coa.account_code',
            'coa.account_name',
            'coa.account_type',
            'coa.parent_id',
            'coa.cashflow_type',
            'coa.is_active',
            null
        ];

        $builder = $this->baseQuery($companyId);

        // TOTAL
        $recordsTotal = (clone $builder)->countAllResults(false);

        // SEARCH
        if ($searchValue) {
            $builder->groupStart()
                ->like('coa.account_code', $searchValue)
                ->orLike('companies.company_name', $searchValue)
                ->orLike('coa.account_name', $searchValue)
                ->orLike('coa.account_type', $searchValue)
                ->orLike('coa.cashflow_type', $searchValue)
                ->orLike('coa.is_active', $searchValue)
            ->groupEnd();
        }

        $recordsFiltered = (clone $builder)->countAllResults(false);

        // ORDER
        if ($order) {
            $idx = (int) $order[0]['column'];
            if (!empty($orderColumns[$idx])) {
                $builder->orderBy($orderColumns[$idx], $order[0]['dir']);
            }
        } else {
            $builder->orderBy('coa.id', 'DESC');
        }

        $data = $builder
            ->limit($length, $start)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $this->formatData($data, $start)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store()
    {
        $request = service('request');
        $companyId = (int) $request->getPost('kantor_coa');
        $accountCode = trim($request->getPost('kode_coa'));

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

        $this->coaModel->insert([
            'company_id'    => $companyId,
            'account_code'  => $accountCode,
            'account_name'  => $request->getPost('nama_coa'),
            'account_type'  => $request->getPost('tipe_coa'),
            'parent_id'     => $request->getPost('induk_coa') ?: null,
            'cashflow_type' => $request->getPost('aruskas_coa'),
            'is_active'     => $request->getPost('status_coa'),
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => session()->get('user_id'),
            'updated_by'    => session()->get('user_id')
        ]);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data added successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | OPENING BALANCE VIEW
    |--------------------------------------------------------------------------
    */
    public function openingBalance()
    {
        $companyId = session()->get('company_id');

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
        $companyId = session()->get('company_id');
        $rows      = $this->request->getJSON(true);

        $totalDebit  = 0;
        $totalCredit = 0;

        foreach ($rows as $row) {
            $totalDebit  += (float) ($row['debit'] ?? 0);
            $totalCredit += (float) ($row['credit'] ?? 0);
        }

        if ($totalDebit !== $totalCredit) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Opening balance not balanced'
            ]);
        }

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
                'credit'     => $row['credit'] ?? 0
            ]);
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Opening Balance Saved'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BASE QUERY
    |--------------------------------------------------------------------------
    */
    private function baseQuery(int $companyId)
    {
        $builder = $this->coaModel
            ->select('coa.*, companies.company_name, parent.account_code AS parent_code')
            ->join('companies', 'companies.id = coa.company_id', 'left')
            ->join('coa parent', 'parent.id = coa.parent_id', 'left')
            ->where('coa.deleted_at', null);

        if ($companyId !== 0) {
            $builder->where('coa.company_id', $companyId);
        }

        return $builder;
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT DATATABLE RESULT
    |--------------------------------------------------------------------------
    */
    private function formatData(array $data, int $start): array
    {
        $result = [];
        $no = $start + 1;

        foreach ($data as $row) {

            $badgeStatus = $row['is_active']
                ? '<span class="badge bg-label-success">Active</span>'
                : '<span class="badge bg-label-danger">Inactive</span>';

            $actionBtn = '<div class="d-flex gap-2">';

            if (hasPermission('coa.edit')) {
                $actionBtn .= '<button class="btn btn-sm btn-icon btn-primary btn-edit" data-id="'.$row['id'].'">
                                <i class="ti ti-pencil"></i>
                               </button>';
            }

            if (hasPermission('coa.delete')) {
                $actionBtn .= '<button class="btn btn-sm btn-icon btn-danger btn-delete" data-id="'.$row['id'].'">
                                <i class="ti ti-trash"></i>
                               </button>';
            }

            $actionBtn .= '</div>';

            $result[] = [
                'no_urut'       => $no++.'.',
                'kantor_coa'    => esc($row['company_name'] ?? '-'),
                'kode_coa'      => esc($row['account_code']),
                'nama_coa'      => esc($row['account_name']),
                'tipe_coa'      => esc($row['account_type']),
                'induk_coa'     => esc($row['parent_code'] ?? '-'),
                'aruskas_coa'   => esc($row['cashflow_type']),
                'status_coa'    => $badgeStatus,
                'action'        => $actionBtn
            ];
        }

        return $result;
    }
}
