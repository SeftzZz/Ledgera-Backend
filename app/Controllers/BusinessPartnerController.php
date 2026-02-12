<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BusinessPartnerModel;

class BusinessPartnerController extends BaseController
{
    protected BusinessPartnerModel $model;

    public function __construct()
    {
        $this->model = new BusinessPartnerModel();
    }

    public function index()
    {
        return view('master_data/partner/index', [
            'title' => 'Business Partner'
        ]);
    }

    public function datatable()
    {
        $request = service('request');

        $search = $request->getPost('search')['value'] ?? null;
        $length = (int) $request->getPost('length');
        $start  = (int) $request->getPost('start');
        $draw   = (int) $request->getPost('draw');
        $order  = $request->getPost('order');

        $builder = $this->model->where('deleted_at', null);

        $recordsTotal = (clone $builder)->countAllResults(false);

        if ($search) {
            $builder->groupStart()
                ->like('partner_name', $search)
                ->orLike('partner_code', $search)
            ->groupEnd();
        }

        $recordsFiltered = (clone $builder)->countAllResults(false);

        if ($order) {
            $columns = [null, 'partner_code', 'partner_name', 'partner_type'];
            $idx = $order[0]['column'];
            if (!empty($columns[$idx])) {
                $builder->orderBy($columns[$idx], $order[0]['dir']);
            }
        } else {
            $builder->orderBy('id', 'DESC');
        }

        $data = $builder->limit($length, $start)->get()->getResultArray();

        $result = [];
        $no = $start + 1;

        foreach ($data as $row) {

            $action = '
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-icon btn-primary btn-edit" data-id="'.$row['id'].'">
                        <i class="ti ti-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-icon btn-danger btn-delete" data-id="'.$row['id'].'">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            ';

            $result[] = [
                'no'            => $no++.'.',
                'partner_code'  => esc($row['partner_code']),
                'partner_name'  => esc($row['partner_name']),
                'partner_type'  => esc($row['partner_type']),
                'action'        => $action
            ];
        }

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $result
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        $this->model->insert([
            'partner_code' => $data['partner_code'],
            'partner_name' => $data['partner_name'],
            'partner_type' => $data['partner_type'],
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message'=> 'Business Partner saved successfully'
        ]);
    }
}
