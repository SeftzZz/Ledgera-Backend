<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ApprovalFlowSeeder extends Seeder
{
    public function run()
    {
        $companyId = 1;

        // JOURNAL FLOW
        $this->db->table('approval_flows')->insert([
            'company_id' => $companyId,
            'module' => 'journal',
            'name' => 'Journal Approval',
            'is_active' => 1
        ]);

        $flowId = $this->db->insertID();

        // RULE > 10 jt
        $this->db->table('approval_rules')->insert([
            'approval_flow_id' => $flowId,
            'min_amount' => 10000000,
            'auto_approve' => 0
        ]);

        $ruleId = $this->db->insertID();

        $managerRole = $this->getRole('Accounting Manager');
        $ownerRole   = $this->getRole('Company Owner');

        $this->db->table('approval_steps')->insertBatch([
            [
                'approval_rule_id' => $ruleId,
                'step_order' => 1,
                'role_id' => $managerRole
            ],
            [
                'approval_rule_id' => $ruleId,
                'step_order' => 2,
                'role_id' => $ownerRole
            ]
        ]);
    }

    private function getRole($name)
    {
        return $this->db->table('roles')->where('name', $name)->get()->getRow()->id;
    }
}
