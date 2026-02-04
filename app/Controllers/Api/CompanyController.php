<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CompanyModel;

class CompanyController extends BaseController
{
    protected $companyModel;

    public function __construct()
    {
        $this->companyModel = new CompanyModel();
    }

    public function index()
    {
        $company = $this->companyModel
            ->findAll();

        return $this->response->setJSON($company);
    }
}
