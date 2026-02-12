<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'company'   => ['view','create','edit','delete'],
            'branch'    => ['view','create','edit','delete'],
            'coa'       => ['view','create','edit'],
            'journal'   => ['view','create','edit','approve'],
            'sales'     => ['view','create','edit','approve'],
            'purchase'  => ['view','create','edit','approve'],
            'cash'      => ['view','create','edit'],
            'bank'      => ['view','create','edit'],
            'report'    => ['view'],
            'fiscal'    => ['view','close'],
            'approval'  => ['view','edit'],
            'user'      => ['view','create','edit'],
            'audit'     => ['view'],
        ];

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                $this->db->table('permissions')->insert([
                    'module' => $module,
                    'action' => $action
                ]);
            }
        }
    }
}
