<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'judul', 'deskripsi', 'tags', 'kategori_id', 'menu_id', 'file_name', 
        'file_path', 'file_type', 'file_size', 'uploaded_by', 
        'tanggal_upload', 'views', 'downloads', 'status', 'akses'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'judul' => 'required|min_length[5]|max_length[200]',
        'kategori_id' => 'required|integer|is_not_unique[kategori.id]',
        'menu_id' => 'required|integer|is_not_unique[menu.id]',
        'file_name' => 'required',
        'file_path' => 'required',
        'file_type' => 'required|in_list[pdf,doc,docx,xls,xlsx,ppt,pptx]',
        'file_size' => 'required|integer',
        'tanggal_upload' => 'required|valid_date',
        'status' => 'in_list[aktif,nonaktif]',
        'akses' => 'in_list[publik,privat]'
    ];

    protected $validationMessages = [
        'judul' => [
            'required' => 'Judul dokumen harus diisi',
            'min_length' => 'Judul minimal 5 karakter'
        ],
        'kategori_id' => [
            'required' => 'Kategori harus dipilih',
            'is_not_unique' => 'Kategori tidak valid'
        ],
        'menu_id' => [
            'required' => 'Menu harus dipilih',
            'is_not_unique' => 'Menu tidak valid'
        ],
        'status' => [
            'in_list' => 'Status harus aktif atau nonaktif'
        ],
        'akses' => [
            'in_list' => 'Akses harus publik atau privat'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom Methods
    
    // ADMIN: Method untuk halaman admin - tampilkan semua dokumen
    public function getAllDokumen()
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu, u.nama_lengkap as uploader')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->join('users u', 'u.id = d.uploaded_by')
            ->orderBy('d.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
    
    // PUBLIC: Method untuk landing page - hanya dokumen publik dan aktif
    public function getPublicDokumen()
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu, u.nama_lengkap as uploader')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->join('users u', 'u.id = d.uploaded_by')
            ->where('d.status', 'aktif')
            ->where('d.akses', 'publik')
            ->orderBy('d.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
    
    // USER: Method untuk user yang sudah login - dokumen aktif (publik + privat)
    public function getActiveDokumen()
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu, u.nama_lengkap as uploader')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->join('users u', 'u.id = d.uploaded_by')
            ->where('d.status', 'aktif')
            ->orderBy('d.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // PUBLIC: Dokumen by menu untuk public access
    public function getPublicDokumenByMenu($menu_id)
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.menu_id', $menu_id)
            ->where('d.status', 'aktif')
            ->where('d.akses', 'publik')
            ->orderBy('d.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // USER: Dokumen by menu untuk logged in user
    public function getDokumenByMenu($menu_id)
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.menu_id', $menu_id)
            ->where('d.status', 'aktif')
            ->orderBy('d.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // USER: Dokumen by kategori untuk logged in user
    public function getDokumenByKategori($kategori_id)
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.kategori_id', $kategori_id)
            ->where('d.status', 'aktif')
            ->orderBy('d.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // PUBLIC: Search dokumen untuk public access
    public function searchPublicDokumen($keyword)
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.status', 'aktif')
            ->where('d.akses', 'publik')
            ->groupStart()
                ->like('d.judul', $keyword)
                ->orLike('d.deskripsi', $keyword)
                ->orLike('d.file_name', $keyword)
                ->orLike('k.nama_kategori', $keyword)
                ->orLike('m.nama_menu', $keyword)
            ->groupEnd()
            ->orderBy('d.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // USER: Search dokumen untuk logged in user
    public function searchDokumen($keyword)
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.status', 'aktif')
            ->groupStart()
                ->like('d.judul', $keyword)
                ->orLike('d.deskripsi', $keyword)
                ->orLike('d.file_name', $keyword)
                ->orLike('k.nama_kategori', $keyword)
                ->orLike('m.nama_menu', $keyword)
            ->groupEnd()
            ->orderBy('d.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    // GANTI method incrementViews dan incrementDownloads di DokumenModel.php dengan yang ini:

    public function incrementViews($id, $user_id = null, $ip_address = null)
    {
        // Cek apakah user sudah view dalam 1 jam terakhir (prevent spam)
        if ($user_id && $ip_address) {
            $recentView = $this->db->table('log_activity')
                ->where('user_id', $user_id)
                ->where('dokumen_id', $id)
                ->where('activity LIKE', '%view%')
                ->where('ip_address', $ip_address)
                ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-1 hour')))
                ->countAllResults();
                
            if ($recentView > 0) {
                log_message('info', "View increment blocked - Recent view detected for user $user_id, doc $id");
                return false; // Jangan increment jika sudah view dalam 1 jam
            }
        }
        
        // Increment view counter
        $result = $this->db->table('dokumen')
            ->where('id', $id)
            ->set('views', 'views + 1', false)
            ->update();
            
        if ($result) {
            log_message('info', "Views incremented for document ID: $id");
        }
        
        return $result;
    }

    public function incrementDownloads($id, $user_id = null, $ip_address = null)
    {
        // Cek apakah user sudah download dalam 5 menit terakhir (prevent spam)
        if ($user_id && $ip_address) {
            $recentDownload = $this->db->table('log_activity')
                ->where('user_id', $user_id)
                ->where('dokumen_id', $id)
                ->where('activity LIKE', '%download%')
                ->where('ip_address', $ip_address)
                ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-5 minutes')))
                ->countAllResults();
                
            if ($recentDownload > 0) {
                log_message('info', "Download increment blocked - Recent download detected for user $user_id, doc $id");
                return false; // Jangan increment jika sudah download dalam 5 menit
            }
        }
        
        // Increment download counter
        $result = $this->db->table('dokumen')
            ->where('id', $id)
            ->set('downloads', 'downloads + 1', false)
            ->update();
            
        if ($result) {
            log_message('info', "Downloads incremented for document ID: $id");
        }
        
        return $result;
    }

    public function getDokumenDetail($id)
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu, u.nama_lengkap as uploader')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->join('users u', 'u.id = d.uploaded_by')
            ->where('d.id', $id)
            ->get()
            ->getRowArray();
    }

    // Tambahkan method ini di DokumenModel.php
    public function getPublicRecentDokumen($limit = 5)
    {
        $oneWeekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.status', 'aktif')
            ->where('d.akses', 'publik')
            ->where('d.created_at >=', $oneWeekAgo)
            ->orderBy('d.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    // USER: Recent documents untuk user dashboard
    public function getRecentDokumen($limit = 5)
    {
        $oneWeekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.status', 'aktif')
            ->where('d.created_at >=', $oneWeekAgo)
            ->orderBy('d.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    // ADMIN: All recent documents (untuk admin)
    public function getAllRecentDokumen($limit = 5)
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.status', 'aktif')
            ->orderBy('d.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getPopularDokumen($limit = 5)
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.status', 'aktif')
            ->where('d.akses', 'publik') // Hanya dokumen publik untuk popular
            ->orderBy('d.views', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getDashboardStats()
    {
        $stats = [];
        
        // Total dokumen aktif
        $stats['total_dokumen'] = $this->where('status', 'aktif')->countAllResults();
        
        // Total dokumen publik
        $stats['total_publik'] = $this->where('status', 'aktif')
                                      ->where('akses', 'publik')
                                      ->countAllResults();
                                      
        // Total dokumen privat
        $stats['total_privat'] = $this->where('status', 'aktif')
                                      ->where('akses', 'privat')
                                      ->countAllResults();
        
        // Total dokumen terbaru (1 minggu terakhir)
        $oneWeekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        $stats['recent_dokumen_count'] = $this->where('status', 'aktif')
                                             ->where('created_at >=', $oneWeekAgo)
                                             ->countAllResults();
        
        // Dokumen per menu
        $stats['dokumen_per_menu'] = $this->db->table('dokumen d')
            ->select('m.nama_menu, COUNT(d.id) as jumlah')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.status', 'aktif')
            ->groupBy('m.id')
            ->get()
            ->getResultArray();
            
        // Dokumen per kategori
        $stats['dokumen_per_kategori'] = $this->db->table('dokumen d')
            ->select('k.nama_kategori, COUNT(d.id) as jumlah')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->where('d.status', 'aktif')
            ->groupBy('k.id')
            ->get()
            ->getResultArray();
            
        return $stats;
    }

    public function isRecentDokumen($dokumen_id)
    {
        $oneWeekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        
        $dokumen = $this->select('created_at')
                       ->where('id', $dokumen_id)
                       ->where('status', 'aktif')
                       ->first();
                       
        if (!$dokumen) {
            return false;
        }
        
        return $dokumen['created_at'] >= $oneWeekAgo;
    }

    public function getRecentDokumenCount()
    {
        $oneWeekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        
        return $this->where('status', 'aktif')
                   ->where('created_at >=', $oneWeekAgo)
                   ->countAllResults();
    }

    // Method untuk check akses dokumen
    public function checkDokumenAccess($id, $isLoggedIn = false)
    {
        $dokumen = $this->select('status, akses')
                       ->where('id', $id)
                       ->first();
                       
        if (!$dokumen || $dokumen['status'] !== 'aktif') {
            return false;
        }
        
        // Jika dokumen publik, siapa saja bisa akses
        if ($dokumen['akses'] === 'publik') {
            return true;
        }
        
        // Jika dokumen privat, harus login
        return $isLoggedIn;
    }

    // Method untuk filter dokumen berdasarkan user login status
    public function filterDokumenByAccess($dokumen, $isLoggedIn = false)
    {
        return array_filter($dokumen, function($doc) use ($isLoggedIn) {
            if ($doc['status'] !== 'aktif') {
                return false;
            }
            
            if ($doc['akses'] === 'publik') {
                return true;
            }
            
            return $isLoggedIn;
        });
    }
}