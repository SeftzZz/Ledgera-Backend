<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BranchModel;

class BranchController extends BaseController
{
    public function index()
    {
        return $this->response->setJSON(
            (new BranchModel())->findAll()
        );
    }

    public function store()
    {
        $id = (new BranchModel())->insert($this->request->getJSON(true), true);
        return $this->response->setJSON(['id' => $id]);
    }
}
