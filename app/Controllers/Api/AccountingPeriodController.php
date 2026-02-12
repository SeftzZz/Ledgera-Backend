<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\ClosingService;

class AccountingPeriodController extends BaseController
{
    public function close($companyId)
    {
        $data = $this->request->getJSON(true);

        (new ClosingService())->close(
            $companyId,
            $data['month'],
            $data['year']
        );

        return $this->response->setJSON(['status' => 'closed']);
    }
}
