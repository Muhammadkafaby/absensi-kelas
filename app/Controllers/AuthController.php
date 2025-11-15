<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    /**
     * Display login page
     */
    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Handle login POST request
     */
    public function doLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Validasi input
        if (empty($username) || empty($password)) {
            session()->setFlashdata('error', 'Username dan password harus diisi');
            return redirect()->back()->withInput();
        }

        // Cari user berdasarkan username
        $userModel = new UserModel();
        $user = $userModel->getUserByUsername($username);

        if (!$user) {
            session()->setFlashdata('error', 'Username atau password salah');
            return redirect()->back()->withInput();
        }

        // Verify password
        if (!password_verify($password, $user['password_hash'])) {
            session()->setFlashdata('error', 'Username atau password salah');
            return redirect()->back()->withInput();
        }

        // Set session data
        $sessionData = [
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'name'       => $user['name'],
            'role'       => $user['role'],
            'teacher_id' => $user['teacher_id'],
            'logged_in'  => true,
        ];

        session()->set($sessionData);

        // Redirect ke dashboard dengan pesan sukses
        session()->setFlashdata('success', 'Selamat datang, ' . $user['name']);
        return redirect()->to('/dashboard');
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        session()->destroy();
        session()->setFlashdata('success', 'Anda telah berhasil logout');
        return redirect()->to('/login');
    }
}
