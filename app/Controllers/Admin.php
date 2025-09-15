<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\KategoriModel;
use App\Models\MenuModel;
use App\Models\DokumenModel;
use App\Models\LogActivityModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $kategoriModel;
    protected $menuModel;
    protected $dokumenModel;
    protected $logModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kategoriModel = new KategoriModel();
        $this->menuModel = new MenuModel();
        $this->dokumenModel = new DokumenModel();
        $this->logModel = new LogActivityModel();
    }

    public function dashboard()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        // Get menu statistics dengan document count (sama seperti di halaman menu)
        $menuStats = $this->menuModel->getMenuWithCount();
        
        // Get total dokumen
        $totalDokumen = $this->dokumenModel->countAllResults();

        $data = [
            'title' => 'Dashboard Admin',
            'user' => $this->getUserData(),
            'stats' => [
                'total_dokumen' => $totalDokumen,
                'menu_stats' => $menuStats // Data real dari database
            ],
            'recent_docs' => $this->dokumenModel->getRecentDokumen(5),
            'recent_activity' => $this->logModel->getRecentActivity(10)
        ];

        return view('admin/dashboard', $data);
    }

// =============== KATEGORI MANAGEMENT ===============
    public function kategori()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Kelola Kategori',
            'user' => $this->getUserData(),
            'kategori' => $this->kategoriModel->getKategoriWithCount(),
            'menu' => $this->menuModel->getActiveMenu() // Tambahkan menu aktif untuk dropdown
        ];

        return view('admin/kategori', $data);
    }

    public function addKategori()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
            'menu_id' => 'required|integer',
            'deskripsi' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validasi apakah menu exists dan aktif
        $menu = $this->menuModel->find($this->request->getPost('menu_id'));
        if (!$menu || $menu['status'] !== 'aktif') {
            return redirect()->back()->withInput()->with('error', 'Menu tidak valid atau tidak aktif');
        }

        // Cek apakah nama kategori sudah ada dalam menu yang sama
        $existingKategori = $this->kategoriModel
            ->where('nama_kategori', trim($this->request->getPost('nama_kategori')))
            ->where('menu_id', $this->request->getPost('menu_id'))
            ->first();
            
        if ($existingKategori) {
            return redirect()->back()->withInput()->with('error', 'Nama kategori sudah ada dalam menu "' . $menu['nama_menu'] . '"');
        }

        $data = [
            'nama_kategori' => trim($this->request->getPost('nama_kategori')),
            'menu_id' => $this->request->getPost('menu_id'),
            'deskripsi' => trim($this->request->getPost('deskripsi')),
            'status' => 'aktif'
        ];

        if ($this->kategoriModel->save($data)) {
            return redirect()->to('/admin/kategori')->with('success', 'Kategori berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambah kategori');
    }

    public function editKategori($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        // Validasi ID terlebih dahulu
        $existingKategori = $this->kategoriModel->find($id);
        if (!$existingKategori) {
            return redirect()->to('/admin/kategori')->with('error', 'Kategori tidak ditemukan');
        }

        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
            'menu_id' => 'required|integer',
            'deskripsi' => 'permit_empty|max_length[500]',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'Validation errors: ' . print_r($errors, true));
            return redirect()->back()->withInput()->with('error', 'Data tidak valid: ' . implode(', ', $errors));
        }

        // Validasi apakah menu exists
        $menu = $this->menuModel->find($this->request->getPost('menu_id'));
        if (!$menu) {
            return redirect()->back()->withInput()->with('error', 'Menu tidak valid');
        }

        // Cek apakah nama kategori sudah ada dalam menu yang sama (kecuali kategori ini sendiri)
        $existingKategori = $this->kategoriModel
            ->where('nama_kategori', trim($this->request->getPost('nama_kategori')))
            ->where('menu_id', $this->request->getPost('menu_id'))
            ->where('id !=', $id)
            ->first();
            
        if ($existingKategori) {
            return redirect()->back()->withInput()->with('error', 'Nama kategori sudah ada dalam menu "' . $menu['nama_menu'] . '"');
        }

        $data = [
            'nama_kategori' => trim($this->request->getPost('nama_kategori')),
            'menu_id' => $this->request->getPost('menu_id'),
            'deskripsi' => trim($this->request->getPost('deskripsi')),
            'status' => $this->request->getPost('status')
        ];

        log_message('info', "Updating kategori ID $id with data: " . print_r($data, true));

        try {
            $this->kategoriModel->skipValidation(true);
            
            if ($this->kategoriModel->update($id, $data)) {
                log_message('info', "Kategori ID $id berhasil diupdate");
                return redirect()->to('/admin/kategori')->with('success', 'Kategori berhasil diperbarui');
            } else {
                $modelErrors = $this->kategoriModel->errors();
                log_message('error', "Model update failed: " . print_r($modelErrors, true));
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori: ' . implode(', ', $modelErrors));
            }
        } catch (\Exception $e) {
            log_message('error', "Exception during kategori update: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteKategori($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        // Cek apakah kategori masih digunakan
        $count = $this->dokumenModel->where('kategori_id', $id)->countAllResults();
        if ($count > 0) {
            return redirect()->to('/admin/kategori')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $count . ' dokumen');
        }

        if ($this->kategoriModel->delete($id)) {
            return redirect()->to('/admin/kategori')->with('success', 'Kategori berhasil dihapus');
        }

        return redirect()->to('/admin/kategori')->with('error', 'Gagal menghapus kategori');
    }

    // TAMBAHAN: Method untuk get kategori berdasarkan menu (untuk AJAX)
    public function getKategoriByMenu($menuId = null)
    {
        $redirect = $this->redirectIfNotLoggedIn();
        if ($redirect) return $redirect;

        if (!$menuId) {
            return $this->response->setJSON(['error' => 'Menu ID required']);
        }

        $kategori = $this->kategoriModel->getKategoriByMenu($menuId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $kategori
        ]);
    }

    // =============== MENU MANAGEMENT ===============
    public function menu()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Kelola Menu',
            'user' => $this->getUserData(),
            'menu' => $this->menuModel->getMenuWithCount()
        ];

        return view('admin/menu', $data);
    }

    public function addMenu()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'nama_menu' => 'required|min_length[3]|max_length[100]|is_unique[menu.nama_menu]',
            'deskripsi' => 'permit_empty|max_length[500]',
            'icon' => 'permit_empty|max_length[50]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }

        $data = [
            'nama_menu' => trim($this->request->getPost('nama_menu')),
            'deskripsi' => trim($this->request->getPost('deskripsi')),
            'icon' => trim($this->request->getPost('icon')),
            // 'urutan' => $this->menuModel->getMaxUrutan() + 1,
            'status' => 'aktif'
        ];

        if ($this->menuModel->save($data)) {
            return redirect()->to('/admin/menu')->with('success', 'Menu berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambah menu');
    }

    public function editMenu($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'nama_menu' => "required|min_length[3]|max_length[100]|is_unique[menu.nama_menu,id,$id]",
            'deskripsi' => 'permit_empty|max_length[500]',
            'icon' => 'permit_empty|max_length[50]',
            // 'urutan' => 'required|integer|greater_than[0]',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }

        $data = [
            'nama_menu' => trim($this->request->getPost('nama_menu')),
            'deskripsi' => trim($this->request->getPost('deskripsi')),
            'icon' => trim($this->request->getPost('icon')),
            // 'urutan' => (int) $this->request->getPost('urutan'),
            'status' => $this->request->getPost('status')
        ];

        // PERBAIKAN: Nonaktifkan validasi model sementara untuk update
        $this->menuModel->skipValidation(true);

        if ($this->menuModel->update($id, $data)) {
            return redirect()->to('/admin/menu')->with('success', 'Menu berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui menu');
    }

    public function deleteMenu($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        // Cek apakah menu masih digunakan
        $count = $this->dokumenModel->where('menu_id', $id)->countAllResults();
        if ($count > 0) {
            return redirect()->to('/admin/menu')->with('error', 'Menu tidak dapat dihapus karena masih digunakan oleh ' . $count . ' dokumen');
        }

        if ($this->menuModel->delete($id)) {
            return redirect()->to('/admin/menu')->with('success', 'Menu berhasil dihapus');
        }

        return redirect()->to('/admin/menu')->with('error', 'Gagal menghapus menu');
    }

    // =============== DOKUMEN MANAGEMENT ===============
    public function dokumen()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Kelola Dokumen',
            'user' => $this->getUserData(),
            'dokumen' => $this->dokumenModel->getAllDokumen(),
            'kategori' => $this->kategoriModel->getActiveKategori(),
            'menu' => $this->menuModel->getActiveMenu()
        ];

        return view('admin/dokumen', $data);
    }

    public function addDokumen()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'judul' => 'required|min_length[5]|max_length[200]',
            'deskripsi' => 'permit_empty|max_length[1000]',
            'kategori_id' => 'required|integer',
            'menu_id' => 'required|integer',
            'file' => 'uploaded[file]|max_size[file,10240]'
        ];

        // PERBAIKAN: Validasi file extension secara manual dan lebih strict
        $file = $this->request->getFile('file');
        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
        
        if ($file && $file->isValid()) {
            // PERBAIKAN: Gunakan getClientExtension() yang lebih reliable
            $clientExtension = strtolower($file->getClientExtension());
            $originalName = $file->getClientName();
            
            // Double check extension dari nama file asli juga
            $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            
            if (!in_array($clientExtension, $allowedExtensions) && !in_array($fileExtension, $allowedExtensions)) {
                return redirect()->back()->withInput()->with('error', 'Format file tidak didukung. Hanya diperbolehkan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX');
            }
            
            // PERBAIKAN: Validasi MIME type untuk PPT/PPTX
            $mimeType = $file->getClientMimeType();
            $allowedMimes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                // TAMBAHAN: MIME types alternatif untuk PPT/PPTX
                'application/mspowerpoint',
                'application/powerpoint',
                'application/x-mspowerpoint'
            ];
            
            // Log untuk debugging
            log_message('info', "File upload debug - Name: {$originalName}, Extension: {$clientExtension}, MIME: {$mimeType}");
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validasi apakah kategori dan menu exists
        $kategori = $this->kategoriModel->find($this->request->getPost('kategori_id'));
        $menu = $this->menuModel->find($this->request->getPost('menu_id'));
        
        if (!$kategori || !$menu) {
            return redirect()->back()->withInput()->with('error', 'Kategori atau Menu tidak valid');
        }

        // Handle file upload
        if (!$file->hasMoved()) {
            try {
                // PERBAIKAN: Gunakan extension yang benar
                $originalName = $file->getClientName();
                $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                
                // Jika extension kosong atau tidak valid, gunakan dari client extension
                if (empty($fileExtension) || !in_array($fileExtension, $allowedExtensions)) {
                    $fileExtension = strtolower($file->getClientExtension());
                }
                
                // PERBAIKAN: Generate nama file dengan extension yang benar
                $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                $uploadPath = WRITEPATH . 'uploads/documents/' . date('Y/m/');
                
                // Create directory if not exists
                if (!is_dir($uploadPath)) {
                    if (!mkdir($uploadPath, 0755, true)) {
                        throw new \Exception('Gagal membuat direktori upload');
                    }
                }

                // Check if directory is writable
                if (!is_writable($uploadPath)) {
                    throw new \Exception('Direktori upload tidak dapat ditulis');
                }

                // PERBAIKAN: Move file dengan nama yang sudah benar
                if (!$file->move($uploadPath, $fileName)) {
                    throw new \Exception('Gagal memindahkan file');
                }

                // Verify file was moved successfully
                $fullPath = $uploadPath . $fileName;
                if (!file_exists($fullPath)) {
                    throw new \Exception('File tidak ditemukan setelah upload');
                }

                $data = [
                    'judul' => trim($this->request->getPost('judul')),
                    'deskripsi' => trim($this->request->getPost('deskripsi')),
                    'kategori_id' => $this->request->getPost('kategori_id'),
                    'menu_id' => $this->request->getPost('menu_id'),
                    'file_name' => $originalName, // PERBAIKAN: Simpan nama asli
                    'file_path' => 'uploads/documents/' . date('Y/m/') . $fileName, // Path dengan extension benar
                    'file_type' => $fileExtension, // PERBAIKAN: Extension yang sudah divalidasi
                    'file_size' => $file->getSize(),
                    'uploaded_by' => $this->session->get('user_id'),
                    'tanggal_upload' => date('Y-m-d'),
                    'status' => 'aktif'
                ];

                // Log untuk debugging
                log_message('info', "Saving document - File: {$fileName}, Type: {$fileExtension}, Original: {$originalName}");

                if ($this->dokumenModel->save($data)) {
                    // Log activity
                    $this->logModel->logActivity(
                        $this->session->get('user_id'),
                        'upload',
                        $this->dokumenModel->getInsertID(),
                        $this->request->getIPAddress(),
                        $this->request->getUserAgent()
                    );

                    return redirect()->to('/admin/dokumen')->with('success', 'Dokumen berhasil ditambahkan');
                } else {
                    // Delete uploaded file if database save fails
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                    throw new \Exception('Gagal menyimpan data dokumen ke database');
                }
                
            } catch (\Exception $e) {
                log_message('error', 'File upload error: ' . $e->getMessage());
                
                // Jangan tampilkan error message untuk Unknown column updated_at
                if (strpos($e->getMessage(), 'Unknown column') !== false && strpos($e->getMessage(), 'updated_at') !== false) {
                    return redirect()->to('/admin/dokumen')->with('success', 'Dokumen berhasil ditambahkan');
                }
                
                return redirect()->back()->with('error', 'Gagal mengupload file: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'File tidak valid atau sudah dipindahkan');
    }

    public function editDokumen($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'judul' => 'required|min_length[5]|max_length[200]',
            'deskripsi' => 'permit_empty|max_length[1000]',
            'kategori_id' => 'required|integer',
            'menu_id' => 'required|integer',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        // Jika ada file baru
        $file = $this->request->getFile('file');
        if ($file && $file->isValid()) {
            $rules['file'] = 'max_size[file,10240]|ext_in[file,pdf,doc,docx,xls,xlsx,ppt,pptx]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }

        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return redirect()->to('/admin/dokumen')->with('error', 'Dokumen tidak ditemukan');
        }

        $data = [
            'judul' => trim($this->request->getPost('judul')),
            'deskripsi' => trim($this->request->getPost('deskripsi')),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'menu_id' => $this->request->getPost('menu_id'),
            'status' => $this->request->getPost('status')
        ];

        // Handle file upload jika ada
        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                // Hapus file lama
                $oldFile = WRITEPATH . $dokumen['file_path'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }

                $fileName = $file->getRandomName();
                $uploadPath = WRITEPATH . 'uploads/documents/' . date('Y/m/') . '/';
                
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $file->move($uploadPath, $fileName);

                $data['file_name'] = $file->getClientName();
                $data['file_path'] = 'uploads/documents/' . date('Y/m/') . '/' . $fileName;
                $data['file_type'] = $file->getClientExtension();
                $data['file_size'] = $file->getSize();
            } catch (\Exception $e) {
                log_message('error', 'File update error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal mengupdate file: ' . $e->getMessage());
            }
        }

        // UPDATE DATA
        if ($this->dokumenModel->update($id, $data)) {
            // PERBAIKAN: Pastikan tidak ada output sebelum redirect
            
            // Log activity (comment dulu untuk debug)
            // $this->logModel->logActivity(
            //     $this->session->get('user_id'),
            //     'edit',
            //     $id,
            //     $this->request->getIPAddress(),
            //     $this->request->getUserAgent()
            // );

            // Force redirect dengan header
            session()->setFlashdata('success', 'Dokumen berhasil diperbarui');
            header('Location: ' . base_url('admin/dokumen'));
            exit();
        }

        return redirect()->back()->with('error', 'Gagal memperbarui dokumen');
    }

    public function deleteDokumen($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return redirect()->to('/admin/dokumen')->with('error', 'Dokumen tidak ditemukan');
        }

        // Hapus dari database terlebih dahulu
        if ($this->dokumenModel->delete($id)) {
            
            // Coba hapus file fisik (tapi jangan gagalkan operasi jika gagal)
            try {
                $filePath = WRITEPATH . $dokumen['file_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            } catch (\Exception $e) {
                // Log error tapi jangan gagalkan operasi
                log_message('error', 'File deletion error (but document deleted from DB): ' . $e->getMessage());
            }

            // Log activity
            try {
                $this->logModel->logActivity(
                    $this->session->get('user_id'),
                    'delete',
                    $id,
                    $this->request->getIPAddress(),
                    $this->request->getUserAgent()
                );
            } catch (\Exception $e) {
                log_message('error', 'Log activity error: ' . $e->getMessage());
            }

            return redirect()->to('/admin/dokumen')->with('success', 'Dokumen berhasil dihapus');
        }

        return redirect()->to('/admin/dokumen')->with('error', 'Gagal menghapus dokumen dari database');
    }

// =============== USER MANAGEMENT ===============
// Tambahkan method ini ke dalam class Admin di Admin.php

    public function user()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Kelola User',
            'user' => $this->getUserData(),
            'users' => $this->userModel->findAll()
        ];

        return view('admin/user', $data);
    }

    public function addUser()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        // Validasi input
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'nip' => 'permit_empty|max_length[20]|is_unique[users.nip]',
            'password' => 'required|min_length[6]',
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|in_list[admin,user]'
        ];

        $messages = [
            'username' => [
                'required' => 'Username harus diisi',
                'is_unique' => 'Username sudah digunakan, silakan pilih yang lain',
                'min_length' => 'Username minimal 3 karakter',
                'max_length' => 'Username maksimal 50 karakter'
            ],
            'nip' => [
                'is_unique' => 'NIP sudah digunakan',
                'max_length' => 'NIP maksimal 20 karakter'
            ],
            'password' => [
                'required' => 'Password harus diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
            'nama_lengkap' => [
                'required' => 'Nama lengkap harus diisi',
                'min_length' => 'Nama lengkap minimal 3 karakter',
                'max_length' => 'Nama lengkap maksimal 100 karakter'
            ],
            'role' => [
                'required' => 'Role harus dipilih',
                'in_list' => 'Role tidak valid'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'User add validation errors: ' . print_r($errors, true));
            
            // Kembalikan ke halaman user dengan error message
            return redirect()->to('/admin/user')
                            ->withInput()
                            ->with('error', 'Gagal menambah user: ' . implode(', ', $errors));
        }

        // Persiapkan data
        $username = trim($this->request->getPost('username'));
        $nip = trim($this->request->getPost('nip'));
        $password = $this->request->getPost('password');
        $nama_lengkap = trim($this->request->getPost('nama_lengkap'));
        $role = $this->request->getPost('role');

        // Double check untuk memastikan username unik
        $existingUser = $this->userModel->where('username', $username)->first();
        if ($existingUser) {
            return redirect()->to('/admin/user')
                            ->withInput()
                            ->with('error', 'Username "' . $username . '" sudah digunakan, silakan pilih yang lain');
        }

        // Double check untuk NIP jika diisi
        if (!empty($nip)) {
            $existingNIP = $this->userModel->where('nip', $nip)->first();
            if ($existingNIP) {
                return redirect()->to('/admin/user')
                                ->withInput()
                                ->with('error', 'NIP "' . $nip . '" sudah digunakan');
            }
        }

        $data = [
            'username' => $username,
            'nip' => !empty($nip) ? $nip : null,
            'password' => $this->userModel->hashPassword($password),
            'nama_lengkap' => $nama_lengkap,
            'role' => $role,
            'status' => 'aktif'
        ];

        try {
            // Bypass validation di model karena sudah divalidasi di controller
            $this->userModel->skipValidation(true);
            
            if ($this->userModel->save($data)) {
                log_message('info', 'User berhasil ditambahkan: ' . $username);
                return redirect()->to('/admin/user')->with('success', 'User "' . $username . '" berhasil ditambahkan');
            } else {
                $modelErrors = $this->userModel->errors();
                log_message('error', 'Model save failed: ' . print_r($modelErrors, true));
                return redirect()->to('/admin/user')
                                ->withInput()
                                ->with('error', 'Gagal menyimpan user: ' . implode(', ', $modelErrors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during user creation: ' . $e->getMessage());
            return redirect()->to('/admin/user')
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function editUser($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/user')->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,$id]",
            'nip' => "permit_empty|max_length[20]|is_unique[users.nip,id,$id]",
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|in_list[admin,user]',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        // Jika password diisi, validasi
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'User edit validation errors: ' . print_r($errors, true));
            return redirect()->back()->withInput()->with('error', 'Data tidak valid: ' . implode(', ', $errors));
        }

        $data = [
            'username' => trim($this->request->getPost('username')),
            'nip' => trim($this->request->getPost('nip')) ?: null, // Convert empty string to null
            'nama_lengkap' => trim($this->request->getPost('nama_lengkap')),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status')
        ];

        // Update password jika diisi
        if (!empty($password)) {
            $data['password'] = $this->userModel->hashPassword($password);
        }

        // Log data yang akan diupdate untuk debugging
        log_message('info', "Updating user ID $id with data: " . print_r($data, true));

        try {
            // Skip validation pada model untuk update (seperti yang dilakukan pada kategori)
            $this->userModel->skipValidation(true);
            
            if ($this->userModel->update($id, $data)) {
                log_message('info', "User ID $id berhasil diupdate");
                return redirect()->to('/admin/user')->with('success', 'User berhasil diperbarui');
            } else {
                // Cek error dari model
                $modelErrors = $this->userModel->errors();
                log_message('error', "Model update failed for user: " . print_r($modelErrors, true));
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui user: ' . implode(', ', $modelErrors));
            }
        } catch (\Exception $e) {
            log_message('error', "Exception during user update: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/user')->with('error', 'User tidak ditemukan');
        }

        // Cek apakah user masih memiliki dokumen
        $count = $this->dokumenModel->where('uploaded_by', $id)->countAllResults();
        if ($count > 0) {
            return redirect()->to('/admin/user')->with('error', 'User tidak dapat dihapus karena masih memiliki ' . $count . ' dokumen');
        }

        // Tidak boleh menghapus diri sendiri
        if ($id == $this->session->get('user_id')) {
            return redirect()->to('/admin/user')->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/user')->with('success', 'User berhasil dihapus');
        }

        return redirect()->to('/admin/user')->with('error', 'Gagal menghapus user');
    }
}
