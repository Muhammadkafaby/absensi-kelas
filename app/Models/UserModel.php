<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password_hash',
        'name',
        'role',
        'teacher_id',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'username'      => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'email'         => 'permit_empty|valid_email|is_unique[users.email,id,{id}]',
        'password_hash' => 'required|min_length[8]',
        'name'          => 'required|min_length[3]|max_length[255]',
        'role'          => 'required|in_list[admin,guru]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'   => 'Username harus diisi',
            'is_unique'  => 'Username sudah digunakan',
        ],
        'email' => [
            'valid_email' => 'Email tidak valid',
            'is_unique'   => 'Email sudah digunakan',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get user dengan relasi teacher
     */
    public function getUserWithTeacher($userId)
    {
        return $this->select('users.*, teachers.name as teacher_name, teachers.nip')
                    ->join('teachers', 'teachers.id = users.teacher_id', 'left')
                    ->where('users.id', $userId)
                    ->first();
    }

    /**
     * Get user by username untuk login
     */
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}
