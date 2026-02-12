<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\ReportService;

class ReportController extends BaseController
{
    public function profitLoss()
    {
        $companyId = $this->request->getGet('company_id');
        $month     = $this->request->getGet('month');
        $year      = $this->request->getGet('year');

        return $this->response->setJSON(
            (new ReportService())->profitLoss($companyId, $month, $year)
        );
    }
}
