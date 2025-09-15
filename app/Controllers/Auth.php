<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LogActivityModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $logModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->logModel = new LogActivityModel();
    }

    // ============================================
    // HALAMAN LOGIN
    // ============================================
    
    public function index()
    {
        return $this->login();
    }

    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if ($this->isLoggedIn()) {
            return $this->redirectBasedOnRole();
        }

        return view('auth/login');
    }

    // ============================================
    // FORGOT PASSWORD - NEW METHOD
    // ============================================
    
    public function forgotPassword()
    {
        // Validasi input
        $rules = [
            'nip' => 'required|min_length[3]',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        $messages = [
            'nip' => [
                'required' => 'NIP harus diisi',
                'min_length' => 'NIP minimal 3 karakter'
            ],
            'new_password' => [
                'required' => 'Password baru harus diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi password harus diisi',
                'matches' => 'Konfirmasi password tidak cocok'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Data tidak valid: ' . implode(', ', $this->validator->getErrors()));
        }

        // Cari user berdasarkan NIP
        $nip = $this->request->getPost('nip');
        $user = $this->userModel->getUserByNIP($nip);

        if (!$user) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'NIP tidak ditemukan dalam sistem');
        }

        // Pastikan user bukan admin (hanya user yang bisa reset password)
        if ($user['role'] !== 'user') {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Fitur reset password hanya untuk User, bukan Admin');
        }

        // Cek status user
        if ($user['status'] !== 'aktif') {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Akun tidak aktif, silakan hubungi administrator');
        }

        // Update password
        $newPassword = $this->request->getPost('new_password');
        $hashedPassword = $this->userModel->hashPassword($newPassword);

        $updateData = [
            'password' => $hashedPassword
        ];

        try {
            // Skip validation untuk update password
            $this->userModel->skipValidation(true);
            
            if ($this->userModel->update($user['id'], $updateData)) {
                // Log activity
                try {
                    $this->logModel->logActivity(
                        $user['id'],
                        'password_reset',
                        null,
                        $this->request->getIPAddress(),
                        $this->request->getUserAgent()
                    );
                } catch (\Exception $e) {
                    // Jika log gagal, tidak masalah
                    log_message('error', 'Gagal log password reset: ' . $e->getMessage());
                }

                log_message('info', 'Password reset berhasil untuk NIP: ' . $nip);
                return redirect()->to('/login')->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
            } else {
                $modelErrors = $this->userModel->errors();
                log_message('error', 'Gagal update password: ' . print_r($modelErrors, true));
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Gagal mereset password: ' . implode(', ', $modelErrors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during password reset: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // ============================================
    // PROSES LOGIN - INI YANG ERROR SEBELUMNYA
    // ============================================
    
    public function processLogin()
    {
        // Debug: pastikan method ini dipanggil
        log_message('info', 'processLogin dipanggil');
        
        if ($this->request->getMethod(true) !== 'POST') {
            return redirect()->back()->with('error', 'Method tidak diizinkan');
        }

        // Validasi input
        $rules = [
            'login_type' => 'required|in_list[admin,user]',
            'password' => 'required|min_length[1]'
        ];

        // Tambah validasi berdasarkan tipe login
        $loginType = $this->request->getPost('login_type');
        
        if ($loginType === 'admin') {
            $rules['username'] = 'required|min_length[3]';
        } else {
            $rules['nip'] = 'required|min_length[3]'; // Ubah jadi 3 untuk test
        }

        // Cek validasi
        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari form
        $password = $this->request->getPost('password');
        $user = null;

        // Cari user berdasarkan login type
        if ($loginType === 'admin') {
            $username = $this->request->getPost('username');
            $user = $this->userModel->getUserByUsername($username);
            
            // Debug
            log_message('info', 'Login admin dengan username: ' . $username);
            
        } else {
            $nip = $this->request->getPost('nip');
            $user = $this->userModel->getUserByNIP($nip);
            
            // Debug
            log_message('info', 'Login user dengan NIP: ' . $nip);
        }

        // Cek apakah user ditemukan
        if (!$user) {
            log_message('error', 'User tidak ditemukan');
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Username/NIP tidak ditemukan');
        }

        // Cek status user
        if ($user['status'] !== 'aktif') {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Akun tidak aktif');
        }

        // Cek role sesuai dengan login type
        if ($user['role'] !== $loginType) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Tipe login tidak sesuai');
        }

        // Verifikasi password
        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            log_message('error', 'Password salah untuk user: ' . ($user['username'] ?? $user['nip']));
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Password salah');
        }

        // Login berhasil - set session
        $sessionData = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'nip' => $user['nip'],
            'nama_lengkap' => $user['nama_lengkap'],
            'role' => $user['role'],
            'logged_in' => true
        ];

        $this->session->set($sessionData);

        try {
            // Log activity
            $this->logModel->logActivity(
                $user['id'],
                'login',
                null,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
        } catch (\Exception $e) {
            // Jika log gagal, tidak masalah
            log_message('error', 'Gagal log activity: ' . $e->getMessage());
        }

        // Redirect ke dashboard sesuai role
        log_message('info', 'Login berhasil untuk user: ' . $user['nama_lengkap']);
        return $this->redirectBasedOnRole();
    }

    // ============================================
    // LOGOUT
    // ============================================
    
    public function logout()
    {
        if ($this->isLoggedIn()) {
            try {
                // Log activity sebelum logout
                $this->logModel->logActivity(
                    session()->get('user_id'),
                    'logout',
                    null,
                    $this->request->getIPAddress(),
                    $this->request->getUserAgent()
                );
            } catch (\Exception $e) {
                log_message('error', 'Gagal log logout: ' . $e->getMessage());
            }
        }

        // Hapus session
        session()->destroy();

        // Redirect ke login
        return redirect()->to('/login')->with('success', 'Berhasil logout');
    }

    // ============================================
    // HELPER METHODS
    // ============================================
    
    private function redirectBasedOnRole()
    {
        $role = $this->session->get('role');
        
        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/user/dashboard');
        }
    }

    // ============================================
    // TEST METHOD (untuk debug)
    // ============================================
    
    public function test()
    {
        // Test koneksi database
        try {
            $db = \Config\Database::connect();
            $result = $db->query("SELECT COUNT(*) as total FROM users")->getRow();
            
            return "✅ Auth Controller OK!<br>" .
                   "✅ Database Connected!<br>" .
                   "✅ Total Users: " . $result->total . "<br>" .
                   "✅ Session Service: " . (session() ? 'OK' : 'ERROR') . "<br>" .
                   "✅ Request Service: " . ($this->request ? 'OK' : 'ERROR');
                   
        } catch (Exception $e) {
            return "❌ Database Error: " . $e->getMessage();
        }
    }
}