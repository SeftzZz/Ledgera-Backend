<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'Super Admin' => [
                '*'
            ],
            'Company Owner' => [
                'company.*','branch.*','report.view',
                'journal.approve','sales.approve','purchase.approve',
                'fiscal.close','approval.*','audit.view'
            ],
            'Accounting Manager' => [
                'coa.*','journal.*','report.view','fiscal.view','fiscal.close'
            ],
            'Accountant' => [
                'journal.view','journal.create','journal.edit',
                'sales.view','purchase.view','report.view'
            ],
            'Finance / Cashier' => [
                'cash.*','bank.*','journal.view'
            ],
            'Sales' => [
                'sales.*','partner.view'
            ],
            'Purchasing' => [
                'purchase.*','partner.view'
            ],
            'Auditor' => [
                'report.view','audit.view','journal.view'
            ],
        ];

        $permissionMap = $this->getPermissionMap();

        foreach ($roles as $roleName => $rules) {
            $this->db->table('roles')->insert([
                'company_id' => null,
                'name' => $roleName,
                'description' => $roleName . ' default role'
            ]);

            $roleId = $this->db->insertID();

            foreach ($rules as $rule) {
                if ($rule === '*') {
                    foreach ($permissionMap as $permissionId) {
                        $this->attach($roleId, $permissionId);
                    }
                    continue;
                }

                [$module, $action] = explode('.', $rule);

                foreach ($permissionMap as $perm) {
                    if (
                        ($perm['module'] === $module || $module === '*') &&
                        ($perm['action'] === $action || $action === '*')
                    ) {
                        $this->attach($roleId, $perm['id']);
                    }
                }
            }
        }
    }

    private function getPermissionMap()
    {
        return $this->db->table('permissions')->get()->getResultArray();
    }

    private function attach($roleId, $permissionId)
    {
        $this->db->table('role_permissions')->insert([
            'role_id' => $roleId,
            'permission_id' => $permissionId
        ]);
    }
}
