<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Login extends BaseController
{
    protected $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function index()
    {
        return view('auth/login', [
            'title' => 'Login | Hey! Work'
        ]);
    }

    public function auth()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->users
            ->where('email', $email)
            ->where('is_active', 'active')
            ->where('deleted_at', null)
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        if (!$user['is_active']) {
            return redirect()->back()->with('error', 'User is inactive');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Incorrect password');
        }

        session()->set([
            'user_id'        => $user['id'],
            'hotel_id'       => $user['hotel_id'],
            'user_name'      => $user['name'],
            'user_role'      => $user['role'],
            'user_email'     => $user['email'],
            'user_photo'     => $user['photo'],
            'isLoggedIn'     => true
        ]);

        return redirect()->to('/admin/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
