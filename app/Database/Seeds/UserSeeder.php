<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $companyId = 1; // asumsi company seeder sudah jalan
        $branchId  = 1; // asumsi branch utama

        $users = [
            [
                'name'  => 'Super Admin',
                'email' => 'superadmin@ledgera.app',
                'role'  => 'Super Admin',
                'company_id' => null
            ],
            [
                'name'  => 'Company Owner',
                'email' => 'owner@company.test',
                'role'  => 'Company Owner',
                'company_id' => $companyId
            ],
            [
                'name'  => 'Accounting Manager',
                'email' => 'manager@company.test',
                'role'  => 'Accounting Manager',
                'company_id' => $companyId
            ],
            [
                'name'  => 'Accountant',
                'email' => 'accountant@company.test',
                'role'  => 'Accountant',
                'company_id' => $companyId
            ],
            [
                'name'  => 'Finance',
                'email' => 'finance@company.test',
                'role'  => 'Finance / Cashier',
                'company_id' => $companyId
            ],
            [
                'name'  => 'Sales',
                'email' => 'sales@company.test',
                'role'  => 'Sales',
                'company_id' => $companyId
            ],
            [
                'name'  => 'Purchasing',
                'email' => 'purchasing@company.test',
                'role'  => 'Purchasing',
                'company_id' => $companyId
            ],
            [
                'name'  => 'Auditor',
                'email' => 'auditor@company.test',
                'role'  => 'Auditor',
                'company_id' => $companyId
            ],
        ];

        foreach ($users as $user) {
            $this->createUser($user, $branchId);
        }
    }

    private function createUser(array $data, int $branchId)
    {
        $this->db->table('users')->insert([
            'company_id' => $data['company_id'],
            'branch_id'  => $branchId,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => password_hash('password123', PASSWORD_DEFAULT),
            'is_active'  => 1,
            'created_at'=> date('Y-m-d H:i:s')
        ]);

        $userId = $this->db->insertID();

        $role = $this->db->table('roles')
            ->where('name', $data['role'])
            ->get()
            ->getRow();

        if (! $role) {
            throw new \Exception('Role not found: ' . $data['role']);
        }

        $this->db->table('user_roles')->insert([
            'user_id'   => $userId,
            'role_id'   => $role->id,
            'branch_id' => null // NULL = all branches
        ]);
    }
}
