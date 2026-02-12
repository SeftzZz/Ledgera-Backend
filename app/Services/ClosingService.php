<?php

namespace App\Services;

use App\Models\JournalHeaderModel;
use App\Models\JournalDetailModel;
use App\Models\RetainedEarningsModel;
use App\Models\AccountingPeriodModel;

class ClosingService
{
    public function close(int $companyId, int $month, int $year): void
    {
        $period = new AccountingPeriodModel();
        $journal = new JournalHeaderModel();
        $detail = new JournalDetailModel();
        $re = new RetainedEarningsModel();

        $profit = db_connect()->query("
            SELECT
            SUM(CASE WHEN coa.account_type='revenue' THEN jd.credit-jd.debit ELSE 0 END) -
            SUM(CASE WHEN coa.account_type='expense' THEN jd.debit-jd.credit ELSE 0 END) AS profit
            FROM journal_details jd
            JOIN journal_headers jh ON jh.id = jd.journal_id
            JOIN chart_of_accounts coa ON coa.id = jd.account_id
            WHERE jh.company_id=? AND jh.period_month=? AND jh.period_year=? AND jh.status='posted'
        ", [$companyId, $month, $year])->getRow()->profit;

        $re->insert([
            'company_id'   => $companyId,
            'period_month' => $month,
            'period_year'  => $year,
            'amount'       => $profit
        ]);

        $period->where([
            'company_id'   => $companyId,
            'period_month' => $month,
            'period_year'  => $year
        ])->set(['is_closed' => 1])->update();
    }
}
