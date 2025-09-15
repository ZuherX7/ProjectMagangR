<?php

// ============================================
// 1. USER MODEL - app/Models/UserModel.php
// ============================================

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'username', 'nip', 'password', 'nama_lengkap', 'role', 'status'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'password' => 'required|min_length[6]',
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'role' => 'required|in_list[admin,user]'
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username harus diisi',
            'is_unique' => 'Username sudah digunakan',
            'min_length' => 'Username minimal 3 karakter'
        ],
        'password' => [
            'required' => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter'
        ],
        'nama_lengkap' => [
            'required' => 'Nama lengkap harus diisi'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom Methods
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getUserByNIP($nip)
    {
        return $this->where('nip', $nip)->first();
    }

    public function getActiveUsers($role = null)
    {
        $builder = $this->where('status', 'aktif');
        if ($role) {
            $builder->where('role', $role);
        }
        return $builder->findAll();
    }

    public function verifyPassword($inputPassword, $hashedPassword)
    {
        return password_verify($inputPassword, $hashedPassword);
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}