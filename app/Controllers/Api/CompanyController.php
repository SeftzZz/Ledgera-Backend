<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CompanyModel;

class CompanyController extends BaseController
{
    public function index()
    {
        return $this->response->setJSON(
            (new CompanyModel())->findAll()
        );
    }

    public function store()
    {
        $id = (new CompanyModel())->insert($this->request->getJSON(true), true);
        return $this->response->setJSON(['id' => $id]);
    }

    public function show($id)
    {
        return $this->response->setJSON(
            (new CompanyModel())->find($id)
        );
    }
}
