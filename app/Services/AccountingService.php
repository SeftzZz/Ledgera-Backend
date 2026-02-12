<?php

namespace App\Services;

use App\Models\AccountingPeriodModel;

class AccountingService
{
    public static function assertPeriodOpen(
        int $companyId,
        int $month,
        int $year
    ): void {
        $periodModel = new AccountingPeriodModel();

        $period = $periodModel
            ->where([
                'company_id'   => $companyId,
                'period_month' => $month,
                'period_year'  => $year
            ])->first();

        if ($period && $period['is_closed']) {
            throw new \Exception('Accounting period is closed');
        }
    }
}
