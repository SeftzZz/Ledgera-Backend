<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Services\PermissionService;

class Login extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * GET /login
     * Show login page
     */
    public function index()
    {
        // Kalau sudah login, langsung ke dashboard
        if (session()->get('is_logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/login', [
            'title' => 'Login'
        ]);
    }

    /**
     * POST /login
     * Proses login web (session-based)
     */
    public function auth()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (! $email || ! $password) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email dan password wajib diisi');
        }

        $user = $this->userModel
            ->where('email', $email)
            ->where('is_active', 1)
            ->first();

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah');
        }

        // Ambil permission & cache ke session
        $permissions = service('permission')->cache($user['id']);

        // Set session login
        session()->set([
            'user_id'      => $user['id'],
            'user_name'    => $user['name'],
            'user_email'   => $user['email'],
            'company_id'   => $user['company_id'] ?? null,
            'branch_id'    => $user['branch_id'] ?? null,
            'permissions'  => $permissions,
            'is_logged_in' => true,
            'logged_at'    => date('Y-m-d H:i:s'),
        ]);

        // Update last login
        $this->userModel->update($user['id'], [
            'last_login_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('dashboard'));
    }

    /**
     * GET /logout
     * Destroy session
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to(base_url('login'))
            ->with('success', 'Anda berhasil logout');
    }
}
