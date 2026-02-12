<?php

namespace Config;

class Menu
{
    public static function items(): array
    {
        return [

            [
                'title'      => 'Dashboard',
                'icon'       => 'ti ti-smart-home',
                'url'        => 'admin/dashboard',
                'permission' => 'dashboard.view',
            ],

            [
                'title' => 'Accounting',
                'icon'  => 'ti ti-file-invoice',
                'children' => [
                    [
                        'title'      => 'Journal',
                        'url'        => 'admin/journal',
                        'permission' => 'journal.view',
                    ],
                    [
                        'title'      => 'Approval',
                        'url'        => 'admin/approval',
                        'permission' => 'approval.view',
                    ],
                ]
            ],

            [
                'title'      => 'Logout',
                'icon'       => 'ti ti-logout',
                'url'        => 'logout',
                'permission' => null,
            ],
        ];
    }
}
