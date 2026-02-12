<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CompanyModel;
use CodeIgniter\HTTP\ResponseInterface;

class CompanyController extends BaseController
{
    protected CompanyModel $model;

    public function __construct()
    {
        $this->model = new CompanyModel();
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/companies
    |--------------------------------------------------------------------------
    */
    public function index(): ResponseInterface
    {
        return $this->respond([
            'status' => true,
            'data'   => $this->model
                ->where('deleted_at', null)
                ->orderBy('company_name', 'ASC')
                ->findAll()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/companies/{id}
    |--------------------------------------------------------------------------
    */
    public function show($id): ResponseInterface
    {
        $company = $this->model
            ->where('deleted_at', null)
            ->find($id);

        if (! $company) {
            return $this->failNotFound('Company not found');
        }

        return $this->respond([
            'status' => true,
            'data'   => $company
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/companies
    |--------------------------------------------------------------------------
    */
    public function store(): ResponseInterface
    {
        $data = $this->request->getJSON(true);

        if (! $this->validate([
            'company_name' => 'required|min_length[3]',
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $id = $this->model->insert([
            'company_name' => $data['company_name'],
            'created_at'   => date('Y-m-d H:i:s'),
            'created_by'   => service('request')->user->sub ?? null
        ], true);

        return $this->respondCreated([
            'status' => true,
            'id'     => $id
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PUT /api/companies/{id}
    |--------------------------------------------------------------------------
    */
    public function update($id): ResponseInterface
    {
        $company = $this->model->find($id);

        if (! $company) {
            return $this->failNotFound('Company not found');
        }

        $data = $this->request->getJSON(true);

        $this->model->update($id, [
            'company_name' => $data['company_name'] ?? $company['company_name'],
            'updated_at'   => date('Y-m-d H:i:s'),
            'updated_by'   => service('request')->user->sub ?? null
        ]);

        return $this->respond([
            'status' => true,
            'message'=> 'Company updated successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE /api/companies/{id}
    |--------------------------------------------------------------------------
    */
    public function delete($id): ResponseInterface
    {
        $company = $this->model->find($id);

        if (! $company) {
            return $this->failNotFound('Company not found');
        }

        $this->model->update($id, [
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => service('request')->user->sub ?? null
        ]);

        return $this->respond([
            'status' => true,
            'message'=> 'Company deleted successfully'
        ]);
    }
}
