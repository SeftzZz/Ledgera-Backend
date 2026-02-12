<?php

namespace App\Services;

class ApprovalService
{
    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function init(string $module, int $transactionId, float $amount)
    {
        $flow = $this->db->table('approval_flows')
            ->where('module', $module)
            ->where('is_active', 1)
            ->get()
            ->getRow();

        if (! $flow) {
            return $this->autoApprove($module, $transactionId);
        }

        $rule = $this->db->table('approval_rules')
            ->where('approval_flow_id', $flow->id)
            ->where('min_amount <=', $amount)
            ->groupBy('id')
            ->orderBy('min_amount', 'DESC')
            ->get()
            ->getRow();

        if (! $rule || $rule->auto_approve) {
            return $this->autoApprove($module, $transactionId);
        }

        $steps = $this->db->table('approval_steps')
            ->where('approval_rule_id', $rule->id)
            ->orderBy('step_order')
            ->get()
            ->getResult();

        foreach ($steps as $step) {
            $this->db->table('approval_logs')->insert([
                'module' => $module,
                'transaction_id' => $transactionId,
                'step_order' => $step->step_order,
                'role_id' => $step->role_id,
                'status' => 'pending'
            ]);
        }

        return true;
    }

    private function autoApprove($module, $transactionId)
    {
        $this->db->table('approval_logs')->insert([
            'module' => $module,
            'transaction_id' => $transactionId,
            'status' => 'approved',
            'note' => 'Auto approved',
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function approve($module, $transactionId)
    {
        $user = service('request')->user;

        $step = $this->db->table('approval_logs')
            ->where('module', $module)
            ->where('transaction_id', $transactionId)
            ->where('status', 'pending')
            ->orderBy('step_order')
            ->get()
            ->getRow();

        if (! $step) return;

        // check role user
        if (! userHasRole($user->sub, $step->role_id)) {
            throw new \Exception('Not authorized');
        }

        $this->db->table('approval_logs')
            ->where('id', $step->id)
            ->update([
                'status' => 'approved',
                'approved_by' => $user->sub,
                'approved_at' => date('Y-m-d H:i:s')
            ]);
    }
}
