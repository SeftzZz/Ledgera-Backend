<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\ApprovalService;

class JournalApprovalController extends BaseController
{
    public function approve($journalId)
    {
        $userId = user_id(); // asumsi helper auth
        (new ApprovalService())->approve($journalId, $userId);

        return $this->response->setJSON(['status' => 'approved']);
    }

    public function reject($journalId)
    {
        $note = $this->request->getJSON(true)['note'];
        (new ApprovalService())->reject($journalId, $note);

        return $this->response->setJSON(['status' => 'rejected']);
    }
}
