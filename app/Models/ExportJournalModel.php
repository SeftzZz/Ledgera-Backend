<?php

namespace App\Models;

use CodeIgniter\Model;

class ExportJournalModel extends Model
{
    protected $table = 'vw_export_journal';
    protected $primaryKey = null;
    protected $returnType = 'array';
    protected $useAutoIncrement = false;
}
