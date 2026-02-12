<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\TransactionService;

class TransactionController extends BaseController
{
    public function store()
    {
        try {
            $id = (new TransactionService())
                ->create($this->request->getJSON(true));

            return $this->response->setJSON([
                'status' => 'success',
                'transaction_id' => $id
            ]);
        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => $e->getMessage()]);
        }
    }
}
