<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ExportJournalModel;

class ExportController extends BaseController
{
    public function journals()
    {
        return $this->response->setJSON(
            (new ExportJournalModel())->findAll()
        );
    }
}
