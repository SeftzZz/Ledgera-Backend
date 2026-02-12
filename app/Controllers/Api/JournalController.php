<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\JournalService;
use App\Services\ApprovalService;

class JournalController extends BaseController
{
    public function store()
    {
        $payload = $this->request->getJSON(true);

        try {
            $id = (new JournalService())->create(
                $payload['header'],
                $payload['lines']
            );

            return $this->response->setJSON(['journal_id' => $id]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => $e->getMessage()]);
        }
    }

    public function submit($id)
    {
        (new ApprovalService())->submit($id);
        return $this->response->setJSON(['status' => 'submitted']);
    }

    public function post($id)
    {
        (new JournalService())->post($id);
        return $this->response->setJSON(['status' => 'posted']);
    }

    public function reverse($id)
    {
        $reverseDate = $this->request->getJSON(true)['reverse_date'];
        $newId = (new JournalService())->reverse($id, $reverseDate);

        return $this->response->setJSON([
            'reversal_journal_id' => $newId
        ]);
    }
}
