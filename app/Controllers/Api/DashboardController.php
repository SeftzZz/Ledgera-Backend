<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    protected $db;
    protected int $userId;
    protected ?int $branchId;

    public function __construct()
    {
        $this->db       = db_connect();
        $this->userId   = session('user_id');
        $this->branchId = session('branch_id');
    }

    /**
     * Dashboard main endpoint
     * GET /api/dashboard
     */
    public function index(): ResponseInterface
    {
        return $this->respond([
            'user' => $this->userInfo(),
            'kpi'  => $this->kpiSummary(),
            'approval' => $this->approvalSummary(),
            'chart' => $this->chartSummary(),
        ]);
    }

    /* ===============================
     * USER INFO
     * =============================== */
    protected function userInfo(): array
    {
        return [
            'id'         => $this->userId,
            'name'       => session('user_name'),
            'branch_id'  => $this->branchId,
            'permissions'=> session('permissions') ?? [],
        ];
    }

    /* ===============================
     * KPI SUMMARY
     * =============================== */
    protected function kpiSummary(): array
    {
        return [
            'cash_balance'     => $this->cashBalance(),
            'total_revenue'    => $this->totalRevenue(),
            'total_expense'    => $this->totalExpense(),
            'net_profit'       => $this->netProfit(),
        ];
    }

    protected function cashBalance(): float
    {
        $builder = $this->db->table('accounts')
            ->selectSum('balance', 'total')
            ->where('type', 'cash');

        if ($this->branchId) {
            $builder->where('branch_id', $this->branchId);
        }

        return (float) ($builder->get()->getRow()->total ?? 0);
    }

    protected function totalRevenue(): float
    {
        return (float) ($this->sumJournalByType('income'));
    }

    protected function totalExpense(): float
    {
        return (float) ($this->sumJournalByType('expense'));
    }

    protected function netProfit(): float
    {
        return $this->totalRevenue() - $this->totalExpense();
    }

    protected function sumJournalByType(string $type): float
    {
        $builder = $this->db->table('journal_details jd')
            ->join('accounts a', 'a.id = jd.account_id')
            ->join('journals j', 'j.id = jd.journal_id')
            ->selectSum('jd.amount', 'total')
            ->where('a.type', $type)
            ->where('j.status', 'posted');

        if ($this->branchId) {
            $builder->where('j.branch_id', $this->branchId);
        }

        return (float) ($builder->get()->getRow()->total ?? 0);
    }

    /* ===============================
     * APPROVAL SUMMARY
     * =============================== */
    protected function approvalSummary(): array
    {
        $builder = $this->db->table('approval_requests ar')
            ->join('user_roles ur', 'ur.role_id = ar.role_id')
            ->where('ur.user_id', $this->userId)
            ->where('ar.status', 'pending');

        if ($this->branchId) {
            $builder->where('ar.branch_id', $this->branchId);
        }

        return [
            'pending_count' => $builder->countAllResults(),
        ];
    }

    /* ===============================
     * CHART DATA (LAST 12 MONTH)
     * =============================== */
    protected function chartSummary(): array
    {
        $rows = $this->db->table('journals')
            ->select("
                DATE_FORMAT(date, '%Y-%m') AS period,
                SUM(total) AS total
            ")
            ->where('status', 'posted')
            ->groupBy('period')
            ->orderBy('period', 'ASC')
            ->limit(12)
            ->get()->getResultArray();

        return [
            'labels' => array_column($rows, 'period'),
            'values' => array_map('floatval', array_column($rows, 'total')),
        ];
    }
}
