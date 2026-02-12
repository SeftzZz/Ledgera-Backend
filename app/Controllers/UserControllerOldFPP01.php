<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CompanyModel;

class UserController extends BaseController
{
    protected $user;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->companyModel = new CompanyModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Users',
            'companies' => $this->companyModel
                ->where('deleted_at', null)
                ->orderBy('company_name', 'ASC')
                ->findAll()
        ];

        return view('users/index', $data);
    }

    // DATATABLE SERVER SIDE
    public function datatable()
    {
        $request = service('request');

        $searchValue = $request->getPost('search')['value'] ?? null;
        $length = (int) $request->getPost('length');
        $start  = (int) $request->getPost('start');
        $draw   = (int) $request->getPost('draw');

        $order = $request->getPost('order');

        $orderColumns = [
            null,
            null,
            'users.name',
            'companies.company_name',
            'users.email',
            'users.phone',
            'users.is_active',
            null
        ];

        $companyId = (int) session()->get('company_id');

        // QUERY FILTERED (COUNT)
        $countQuery = $this->userModel
            ->join('companies', 'companies.id = users.company_id', 'left')
            ->where('users.deleted_at', null);

        // Company Scope
        if ($companyId !== 0) {
            $countQuery->where('users.company_id', $companyId);
        }

        if ($searchValue) {
            $countQuery->groupStart()
                ->like('users.name', $searchValue)
                ->orLike('companies.company_name', $searchValue)
                ->orLike('users.email', $searchValue)
                ->orLike('users.phone', $searchValue)
                ->orLike('users.is_active', $searchValue)
            ->groupEnd();
        }

        $recordsFiltered = $countQuery->countAllResults();

        // QUERY TOTAL
        $totalQuery = $this->userModel
            ->join('companies', 'companies.id = users.company_id', 'left')
            ->where('users.deleted_at', null);

        // Company Scope
        if ($companyId !== 0) {
            $totalQuery->where('users.company_id', $companyId);
        }

        $recordsTotal = $totalQuery->countAllResults();

        // QUERY DATA
        $dataQuery = $this->userModel
            ->select('users.*, companies.company_name')
            ->join('companies', 'companies.id = users.company_id', 'left')
            ->where('users.deleted_at', null);

        // Company Scope
        if ($companyId !== 0) {
            $dataQuery->where('users.company_id', $companyId);
        }

        if ($searchValue) {
            $dataQuery->groupStart()
                ->like('users.name', $searchValue)
                ->orLike('companies.company_name', $searchValue)
                ->orLike('users.email', $searchValue)
                ->orLike('users.phone', $searchValue)
                ->orLike('users.is_active', $searchValue)
            ->groupEnd();
        }

        // ORDERING
        if ($order) {
            $idx = (int) $order[0]['column'];
            if (!empty($orderColumns[$idx])) {
                $dataQuery->orderBy($orderColumns[$idx], $order[0]['dir']);
            }
        } else {
            $dataQuery->orderBy('users.id', 'DESC');
        }

        $data = $dataQuery
            ->limit($length, $start)
            ->get()
            ->getResultArray();

        // FORMAT DATA
        $result = [];
        $no = $start + 1;
        foreach ($data as $row) {
            $status = strtolower($row['is_active']);
            $badgeStatus = match ($status) {
                'active'   => '<span class="badge bg-label-success">Active</span>',
                'inactive' => '<span class="badge bg-label-danger">Inactive</span>',
                default    => '<span class="badge bg-label-secondary">'.ucfirst(esc($status)).'</span>',
            };

            $actionBtn = '<div class="d-flex gap-2">';

            if (hasPermission('users.edit')) {
                $actionBtn .= '
                    <button class="btn btn-sm btn-icon btn-primary btn-edit" data-id="'.$row['id'].'" title="Edit">
                        <i class="ti ti-pencil"></i>
                    </button>
                ';
            }

            if (hasPermission('users.delete') && session()->get('user_id') != $row['id']) {
                $actionBtn .= '
                    <button class="btn btn-sm btn-icon btn-danger btn-delete" data-id="'.$row['id'].'" title="Delete">
                        <i class="ti ti-trash"></i>
                    </button>
                ';
            }

            $actionBtn .= '</div>';

            $result[] = [
                'no_urut'       => $no++.'.',
                'name_user'     => esc($row['name']),
                'company_user'  => esc($row['company_name'] ?? '-'),
                'email_user'    => esc($row['email']),
                'hp_user'       => '+62' . esc($row['phone']),
                'status_user'   => $badgeStatus,
                'photo_user'    => esc($row['photo']),
                'action'        => $actionBtn
            ];
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $result
        ]);
    }



}
