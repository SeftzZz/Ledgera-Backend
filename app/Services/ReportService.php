<?php

namespace App\Services;

class ReportService
{
    public function profitLoss(int $companyId, int $month, int $year)
    {
        return db_connect()->query("
            SELECT
            coa.account_name,
            SUM(jd.debit - jd.credit) AS amount
            FROM journal_details jd
            JOIN chart_of_accounts coa ON coa.id = jd.account_id
            JOIN journal_headers jh ON jh.id = jd.journal_id
            WHERE jh.company_id=? AND jh.period_month=? AND jh.period_year=? AND jh.status='posted'
            GROUP BY coa.id
        ", [$companyId, $month, $year])->getResultArray();
    }
}
