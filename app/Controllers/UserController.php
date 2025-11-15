<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    /**
     * Show change password form (redirect to profile page)
     */
    public function changePassword()
    {
        // Change password form is now integrated in the profile page
        return redirect()->to('/user/profile')->with('info', 'Formulir ganti password tersedia di halaman profil');
    }

    /**
     * Process password change
     */
    public function updatePassword()
    {
        $userId = session()->get('user_id');

        // Validation rules
        $rules = [
            'old_password' => [
                'label' => 'Password lama',
                'rules' => 'required',
            ],
            'new_password' => [
                'label' => 'Password baru',
                'rules' => 'required|min_length[8]',
            ],
            'confirm_password' => [
                'label' => 'Konfirmasi password',
                'rules' => 'required|matches[new_password]',
            ],
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Verify old password
        if (!password_verify($this->request->getPost('old_password'), $user['password_hash'])) {
            session()->setFlashdata('error', 'Password lama tidak sesuai');
            return redirect()->back();
        }

        // Update password
        $newPasswordHash = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);

        if ($userModel->update($userId, ['password_hash' => $newPasswordHash])) {
            session()->setFlashdata('success', 'Password berhasil diubah');
            return redirect()->to('/dashboard');
        }

        session()->setFlashdata('error', 'Gagal mengubah password');
        return redirect()->back();
    }

    /**
     * User profile page
     */
    public function profile()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->getUserWithTeacher($userId);

        $data = [
            'title' => 'Profil Saya',
            'user'  => $user,
        ];

        return view('user/enterprise_profile', $data);
    }

    /**
     * Update user profile
     */
    public function updateProfile()
    {
        $userId = session()->get('user_id');

        $rules = [
            'name'  => 'required|min_length[3]|max_length[255]',
            'email' => 'permit_empty|valid_email',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            return redirect()->back()->withInput();
        }

        $userModel = new UserModel();
        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];

        if ($userModel->update($userId, $data)) {
            // Update session name
            session()->set('name', $data['name']);
            session()->setFlashdata('success', 'Profil berhasil diperbarui');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui profil');
        }

        return redirect()->to('/user/profile');
    }
}
