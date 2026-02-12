<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AccountModel;

class AccountController extends BaseController
{
    public function index()
    {
        return $this->response->setJSON(
            (new AccountModel())->findAll()
        );
    }

    public function store()
    {
        $id = (new AccountModel())->insert($this->request->getJSON(true), true);
        return $this->response->setJSON(['id' => $id]);
    }

    public function tree()
    {
        $companyId = $this->request->getGet('company_id');
        return $this->response->setJSON(
            (new AccountModel())->getTree($companyId)
        );
    }
}
