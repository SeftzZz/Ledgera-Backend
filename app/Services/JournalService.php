<?php

namespace App\Services;

use App\Models\JournalHeaderModel;
use App\Models\JournalDetailModel;
use App\Models\FiscalYearModel;
use App\Services\AccountingService;
use CodeIgniter\Database\BaseConnection;

class JournalService
{
    protected $db;
    protected $header;
    protected $detail;

    public function __construct()
    {
        $this->db     = db_connect();
        $this->header = new JournalHeaderModel();
        $this->detail = new JournalDetailModel();
    }

    public function create(array $data, array $lines): int
    {
        AccountingService::assertPeriodOpen(
            $data['company_id'],
            $data['period_month'],
            $data['period_year']
        );

        $this->db->transStart();

        // resolve fiscal year
        $fy = (new FiscalYearModel())
            ->where('company_id', $data['company_id'])
            ->where("'{$data['journal_date']}' BETWEEN start_date AND end_date")
            ->first();

        $data['fiscal_year_id'] = $fy['id'] ?? null;
        $data['status'] = 'draft';

        $journalId = $this->header->insert($data, true);

        foreach ($lines as $line) {
            $line['journal_id'] = $journalId;
            $this->detail->insert($line);
        }

        $this->db->transComplete();
        return $journalId;
    }

    public function post(int $journalId): void
    {
        $journal = $this->header->find($journalId);

        if ($journal['status'] !== 'approved') {
            throw new \Exception('Journal not approved');
        }

        $this->header->update($journalId, [
            'status'    => 'posted',
            'is_locked' => 1
        ]);
    }

    public function reverse(int $journalId, string $reverseDate): int
    {
        $original = $this->header->find($journalId);
        $details  = $this->detail->where('journal_id', $journalId)->findAll();

        $this->db->transStart();

        $newId = $this->header->insert([
            'company_id'   => $original['company_id'],
            'branch_id'    => $original['branch_id'],
            'journal_no'   => $original['journal_no'] . '-REV',
            'journal_date' => $reverseDate,
            'period_month'=> date('m', strtotime($reverseDate)),
            'period_year' => date('Y', strtotime($reverseDate)),
            'status'       => 'posted',
            'reversal_of'  => $journalId
        ], true);

        foreach ($details as $d) {
            $this->detail->insert([
                'journal_id' => $newId,
                'account_id' => $d['account_id'],
                'debit'      => $d['credit'],
                'credit'     => $d['debit']
            ]);
        }

        $this->db->transComplete();
        return $newId;
    }
}
