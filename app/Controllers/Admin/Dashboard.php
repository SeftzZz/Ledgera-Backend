<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
        return view('admin/dashboard', [
            'title' => 'Dashboard'
        ]);
    }
}