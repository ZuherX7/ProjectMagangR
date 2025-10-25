<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\DokumenModel;
use App\Models\PengaduanModel;
use App\Models\LogActivityModel;

class Landing extends BaseController
{
    protected $menuModel;
    protected $dokumenModel;
    protected $pengaduanModel;
    protected $logModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->dokumenModel = new DokumenModel();
        $this->pengaduanModel = new PengaduanModel();
        $this->logModel = new LogActivityModel();
    }

    public function index()
    {
        $data = [
            'title' => 'SIDODIK - Sistem Informasi Dokumen Diskominfotik',
            'menu' => $this->menuModel->getActiveMenuWithCount(),
            'recent_docs' => $this->dokumenModel->getPublicRecentDokumen(6),
            'popular_docs' => $this->dokumenModel->getPopularDokumen(6)
        ];

        return view('landing/index', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'Tentang SIDODIK'
        ];

        return view('landing/about', $data);
    }

    public function dokumen()
    {
        // Cek apakah user sudah login
        $isLoggedIn = session()->get('logged_in') ? true : false;
        
        // Get search and filter parameters
        $keyword = $this->request->getGet('q');
        $menuFilter = $this->request->getGet('menu');
        $kategoriFilter = $this->request->getGet('kategori');

        // Initialize variables
        $dokumen = [];
        $filter_info = 'Semua Dokumen';
        $current_menu = null;
        $current_kategori = null;

        // Start building query with joins
        $builder = $this->dokumenModel->select('dokumen.*, menu.nama_menu, kategori.nama_kategori')
                                    ->join('menu', 'menu.id = dokumen.menu_id', 'left')
                                    ->join('kategori', 'kategori.id = dokumen.kategori_id', 'left')
                                    ->where('dokumen.status', 'aktif');

        // FILTER BERDASARKAN STATUS LOGIN
        if (!$isLoggedIn) {
            // User belum login: hanya dokumen publik
            $builder->where('dokumen.akses', 'publik');
        }
        // User sudah login: tampilkan semua dokumen aktif (publik + privat) - tidak perlu filter tambahan

        // Apply menu filter
        if ($menuFilter && is_numeric($menuFilter)) {
            $builder->where('dokumen.menu_id', $menuFilter);
            $current_menu = $menuFilter;
            $menuInfo = $this->menuModel->find($menuFilter);
            if ($menuInfo) {
                $filter_info = $menuInfo['nama_menu'];
            }
        }

        // Apply category filter
        if ($kategoriFilter && is_numeric($kategoriFilter)) {
            $builder->where('dokumen.kategori_id', $kategoriFilter);
            $current_kategori = $kategoriFilter;
            $kategoriInfo = model('KategoriModel')->find($kategoriFilter);
            if ($kategoriInfo) {
                $filter_info = $kategoriInfo['nama_kategori'];
            }
        }

        // Apply search filter
        if ($keyword) {
            $searchTerms = explode(' ', trim($keyword));
            $builder->groupStart();
            
            foreach ($searchTerms as $term) {
                if (strlen($term) > 2) {
                    $builder->orLike('dokumen.judul', $term)
                        ->orLike('dokumen.deskripsi', $term)
                        ->orLike('dokumen.file_name', $term)
                        ->orLike('dokumen.tags', $term)
                        ->orLike('menu.nama_menu', $term)
                        ->orLike('kategori.nama_kategori', $term);
                }
            }
            $builder->groupEnd();
        }

        // Order results
        $builder->orderBy('dokumen.created_at', 'DESC');
        $dokumen = $builder->findAll();

        // Calculate search scores if searching
        if ($keyword && !empty($dokumen)) {
            foreach ($dokumen as &$doc) {
                $doc['search_score'] = $this->calculateSearchScore($doc, $keyword);
            }
            
            // Sort by search score
            usort($dokumen, function($a, $b) {
                return ($b['search_score'] ?? 0) <=> ($a['search_score'] ?? 0);
            });
        }

        // Get menu and categories for filters
        $allMenu = $this->menuModel->getActiveMenu();
        $allKategori = [];
        
        if ($current_menu) {
            // Show categories from selected menu
            $allKategori = model('KategoriModel')->getKategoriByMenu($current_menu);
        } elseif ($current_kategori) {
            // Show categories from same menu as selected category
            $kategoriInfo = model('KategoriModel')->find($current_kategori);
            if ($kategoriInfo && $kategoriInfo['menu_id']) {
                $allKategori = model('KategoriModel')->getKategoriByMenu($kategoriInfo['menu_id']);
            } else {
                $allKategori = model('KategoriModel')->getActiveKategori();
            }
        } else {
            // Show all categories
            $allKategori = model('KategoriModel')->getActiveKategori();
        }

        $data = [
            'title' => $keyword ? "Hasil Pencarian: $keyword" : ('Dokumen ' . ($isLoggedIn ? '' : 'Publik ') . '- ' . $filter_info),
            'menu' => $allMenu,
            'kategori' => $allKategori,
            'dokumen' => $dokumen,
            'filter_info' => $filter_info,
            'keyword' => $keyword,
            'current_menu' => $current_menu,
            'current_kategori' => $current_kategori,
            'is_logged_in' => $isLoggedIn,
            'user' => $isLoggedIn ? $this->getUserData() : null
        ];

        return view('landing/dokumen', $data);
    }

    // TAMBAH METHOD UNTUK HANDLE VIEW DOKUMEN DARI LANDING PAGE
    public function viewDokumen($id)
    {
        $dokumen = $this->dokumenModel->getDokumenDetail($id);
        
        if (!$dokumen || $dokumen['status'] !== 'aktif') {
            return redirect()->to('/dokumen-publik')->with('error', 'Dokumen tidak ditemukan');
        }

        // Cek akses dokumen
        $isLoggedIn = session()->get('logged_in') ? true : false;
        
        // Jika dokumen privat tapi user belum login, redirect ke login
        if ($dokumen['akses'] === 'privat' && !$isLoggedIn) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk mengakses dokumen ini');
        }

        // Increment views dengan anti-spam protection
        $user_id = $isLoggedIn ? session()->get('user_id') : null;
        $ip_address = $this->request->getIPAddress();
        $user_agent = $this->request->getUserAgent();

        $this->dokumenModel->incrementViews($id, $user_id, $ip_address);

        // Log activity
        try {
            if ($user_id) {
                // User yang login
                $activity_type = $isLoggedIn ? 'user_view' : 'public_view';
                $this->logModel->logDocumentAccess(
                    $user_id,
                    'view',
                    $id,
                    $activity_type,
                    $ip_address,
                    $user_agent
                );
            } else {
                // User tidak login tapi akses dokumen publik
                // Log dengan user_id = 0 atau skip logging untuk public user
                log_message('info', "Public user viewed document ID: $id from IP: $ip_address");
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to log view activity from landing: ' . $e->getMessage());
        }

        // Redirect ke file dengan target blank
        return redirect()->to(base_url('files/' . $dokumen['file_path']));
    }

    // TAMBAH METHOD UNTUK HANDLE DOWNLOAD DOKUMEN DARI LANDING PAGE
    public function downloadDokumen($id)
    {
        $dokumen = $this->dokumenModel->find($id);
        
        if (!$dokumen || $dokumen['status'] !== 'aktif') {
            return redirect()->to('/dokumen-publik')->with('error', 'Dokumen tidak ditemukan');
        }

        // Cek akses dokumen
        $isLoggedIn = session()->get('logged_in') ? true : false;
        
        // Jika dokumen privat tapi user belum login, redirect ke login
        if ($dokumen['akses'] === 'privat' && !$isLoggedIn) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk mengunduh dokumen ini');
        }

        $filePath = WRITEPATH . $dokumen['file_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server');
        }

        // Increment downloads dengan anti-spam protection
        $user_id = $isLoggedIn ? session()->get('user_id') : null;
        $ip_address = $this->request->getIPAddress();
        $user_agent = $this->request->getUserAgent();

        $this->dokumenModel->incrementDownloads($id, $user_id, $ip_address);

        // Log activity
        try {
            if ($user_id) {
                // User yang login
                $activity_type = $isLoggedIn ? 'user_download' : 'public_download';
                $this->logModel->logDocumentAccess(
                    $user_id,
                    'download',
                    $id,
                    $activity_type,
                    $ip_address,
                    $user_agent
                );
            } else {
                // User tidak login tapi download dokumen publik
                // Log dengan user_id = 0 atau skip logging untuk public user
                log_message('info', "Public user downloaded document ID: $id from IP: $ip_address");
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to log download activity from landing: ' . $e->getMessage());
        }

        // Force download
        return $this->response->download($filePath, null)->setFileName($dokumen['file_name']);
    }

    // HELPER METHOD UNTUK CALCULATE SEARCH SCORE
    private function calculateSearchScore($doc, $keyword)
    {
        $score = 0;
        $searchTerms = explode(' ', strtolower(trim($keyword)));
        
        $title = strtolower($doc['judul'] ?? '');
        $description = strtolower($doc['deskripsi'] ?? '');
        $fileName = strtolower($doc['file_name'] ?? '');
        $menuName = strtolower($doc['nama_menu'] ?? '');
        $categoryName = strtolower($doc['nama_kategori'] ?? '');
        $tags = strtolower($doc['tags'] ?? ''); // <-- TAMBAH INI
        
        foreach ($searchTerms as $term) {
            if (strlen($term) > 2) {
                if (strpos($title, $term) !== false) {
                    $score += 3;
                }
                
                // Tags matches (HIGH PRIORITY)
                if (strpos($tags, $term) !== false) {
                    $score += 2.5; // <-- TAMBAH INI
                }
                
                if (strpos($description, $term) !== false) {
                    $score += 2;
                }
                
                if (strpos($fileName, $term) !== false) {
                    $score += 1;
                }
                
                if (strpos($menuName, $term) !== false) {
                    $score += 1;
                }
                
                if (strpos($categoryName, $term) !== false) {
                    $score += 1;
                }
            }
        }
        
        $maxPossibleScore = count($searchTerms) * 10.5; // <-- UPDATE INI
        return $maxPossibleScore > 0 ? $score / $maxPossibleScore : 0;
    }

    // UPDATE METHOD submitPengaduan di Landing Controller
    public function submitPengaduan()
    {
        if ($this->request->getMethod(true) !== 'POST') {
            return redirect()->back()->with('error', 'Method tidak diizinkan');
        }

        // Rules dasar
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'telepon' => 'permit_empty|max_length[20]',
            'jenis_pemohon' => 'required|in_list[publik,asn]',
            'instansi' => 'permit_empty|max_length[100]',
            'judul_dokumen' => 'required|min_length[5]|max_length[200]',
            'deskripsi_kebutuhan' => 'required|min_length[10]|max_length[1000]',
            'kategori_permintaan' => 'required|in_list[surat,laporan,formulir,panduan,lainnya]',
            'urgency' => 'required|in_list[rendah,sedang,tinggi,sangat_tinggi]'
        ];

        // Jika jenis pemohon adalah ASN, NIP wajib diisi
        $jenisPemohon = $this->request->getPost('jenis_pemohon');
        if ($jenisPemohon === 'asn') {
            $rules['nip'] = 'required|min_length[18]|max_length[20]|numeric';
        } else {
            $rules['nip'] = 'permit_empty|min_length[18]|max_length[20]|numeric';
        }

        $messages = [
            'nama' => [
                'required' => 'Nama harus diisi',
                'min_length' => 'Nama minimal 3 karakter',
                'max_length' => 'Nama maksimal 100 karakter'
            ],
            'email' => [
                'required' => 'Email harus diisi',
                'valid_email' => 'Format email tidak valid',
                'max_length' => 'Email maksimal 100 karakter'
            ],
            'jenis_pemohon' => [
                'required' => 'Jenis pemohon harus dipilih',
                'in_list' => 'Jenis pemohon tidak valid'
            ],
            'nip' => [
                'required' => 'NIP harus diisi untuk ASN',
                'min_length' => 'NIP harus 18 digit',
                'max_length' => 'NIP maksimal 20 digit',
                'numeric' => 'NIP harus berupa angka'
            ],
            'judul_dokumen' => [
                'required' => 'Judul dokumen harus diisi',
                'min_length' => 'Judul dokumen minimal 5 karakter',
                'max_length' => 'Judul dokumen maksimal 200 karakter'
            ],
            'deskripsi_kebutuhan' => [
                'required' => 'Deskripsi kebutuhan harus diisi',
                'min_length' => 'Deskripsi minimal 10 karakter',
                'max_length' => 'Deskripsi maksimal 1000 karakter'
            ],
            'kategori_permintaan' => [
                'required' => 'Kategori permintaan harus dipilih',
                'in_list' => 'Kategori permintaan tidak valid'
            ],
            'urgency' => [
                'required' => 'Tingkat urgensi harus dipilih',
                'in_list' => 'Tingkat urgensi tidak valid'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => trim($this->request->getPost('nama')),
            'email' => trim($this->request->getPost('email')),
            'telepon' => trim($this->request->getPost('telepon')),
            'jenis_pemohon' => $this->request->getPost('jenis_pemohon'),
            'nip' => $jenisPemohon === 'asn' ? trim($this->request->getPost('nip')) : null,
            'instansi' => trim($this->request->getPost('instansi')),
            'judul_dokumen' => trim($this->request->getPost('judul_dokumen')),
            'deskripsi_kebutuhan' => trim($this->request->getPost('deskripsi_kebutuhan')),
            'kategori_permintaan' => $this->request->getPost('kategori_permintaan'),
            'urgency' => $this->request->getPost('urgency'),
            'status' => 'pending',
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()
        ];

        try {
            if ($this->pengaduanModel->save($data)) {
                $ticketNumber = 'REQ-' . date('Ymd') . '-' . str_pad($this->pengaduanModel->getInsertID(), 4, '0', STR_PAD_LEFT);
                
                // Update dengan nomor tiket
                $this->pengaduanModel->update($this->pengaduanModel->getInsertID(), ['ticket_number' => $ticketNumber]);
                
                return redirect()->to('/#pengaduan')
                               ->with('success', "Pengaduan berhasil dikirim! Nomor tiket Anda: <strong>{$ticketNumber}</strong>. Silakan simpan nomor ini untuk tracking.");
            } else {
                $errors = $this->pengaduanModel->errors();
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Gagal mengirim pengaduan: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Error submitting pengaduan: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function cekStatus()
    {
        $ticketNumber = $this->request->getGet('ticket');
        
        if (!$ticketNumber) {
            return redirect()->to('/')->with('error', 'Nomor tiket harus diisi');
        }

        $pengaduan = $this->pengaduanModel->where('ticket_number', $ticketNumber)->first();
        
        if (!$pengaduan) {
            return redirect()->to('/')->with('error', 'Nomor tiket tidak ditemukan');
        }

        $data = [
            'title' => 'Status Pengaduan - ' . $ticketNumber,
            'pengaduan' => $pengaduan
        ];

        return view('landing/status', $data);
    }

    public function apiStatus()
    {
        $ticketNumber = $this->request->getGet('ticket');
        
        if (!$ticketNumber) {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Nomor tiket harus diisi'
            ]);
        }

        $pengaduan = $this->pengaduanModel->where('ticket_number', $ticketNumber)->first();
        
        if (!$pengaduan) {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Nomor tiket tidak ditemukan'
            ]);
        }

        // Return the full pengaduan data for frontend processing
        return $this->response->setJSON([
            'error' => false,
            'data' => $pengaduan
        ]);
    }
}