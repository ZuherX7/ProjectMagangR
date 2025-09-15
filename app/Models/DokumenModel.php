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
        'judul', 'deskripsi', 'kategori_id', 'menu_id', 'file_name', 
        'file_path', 'file_type', 'file_size', 'uploaded_by', 
        'tanggal_upload', 'views', 'downloads', 'status'
        // 'created_at', 'updated_at'  // TAMBAHKAN INI
    ];

    protected bool $allowEmptyInserts = false;

    // Dates - PERBAIKI PENGATURAN TIMESTAMP
    protected $useTimestamps = false;  // UBAH DARI false KE true
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
        'tanggal_upload' => 'required|valid_date'
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
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom Methods
    
    // PERBAIKAN: Method untuk halaman admin - tampilkan semua dokumen (aktif dan nonaktif)
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
    
    // Method ini tetap ada untuk halaman publik - hanya tampilkan dokumen aktif
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

    public function incrementViews($id)
    {
        return $this->db->table('dokumen')
            ->where('id', $id)
            ->set('views', 'views + 1', false)
            ->update();
    }

    public function incrementDownloads($id)
    {
        return $this->db->table('dokumen')
            ->where('id', $id)
            ->set('downloads', 'downloads + 1', false)
            ->update();
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

    // UPDATED METHOD: Only show documents uploaded within last 2 weeks (14 days)
    public function getRecentDokumen($limit = 5)
    {
        // Calculate the date 7 days ago from today (1 week)
        $oneWeekAgo = date('Y-m-d H:i:s', strtotime('-7 days')); // UBAH DARI -14 days
        
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.status', 'aktif')
            ->where('d.created_at >=', $oneWeekAgo) // Only documents from last 1 week
            ->orderBy('d.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    // NEW METHOD: Get all recent documents (for admin or full listing)
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

    // NEW METHOD: Get documents that are older than 2 weeks (for cleanup or archiving)
    public function getOldDokumen($limit = null)
    {
        $twoWeeksAgo = date('Y-m-d H:i:s', strtotime('-14 days'));
        
        $query = $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.status', 'aktif')
            ->where('d.created_at <', $twoWeeksAgo)
            ->orderBy('d.created_at', 'DESC');
            
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get()->getResultArray();
    }

    public function getPopularDokumen($limit = 5)
    {
        return $this->db->table('dokumen d')
            ->select('d.*, k.nama_kategori, m.nama_menu')
            ->join('kategori k', 'k.id = d.kategori_id')
            ->join('menu m', 'm.id = d.menu_id')
            ->where('d.status', 'aktif')
            ->orderBy('d.views', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getDashboardStats()
    {
        $stats = [];
        
        // Total dokumen
        $stats['total_dokumen'] = $this->where('status', 'aktif')->countAllResults();
        
        // Total dokumen terbaru (2 minggu terakhir)
        $twoWeeksAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        $stats['recent_dokumen_count'] = $this->where('status', 'aktif')
                                             ->where('created_at >=', $twoWeeksAgo)
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

    // NEW METHOD: Check if document is considered "recent" (within 2 weeks)
    public function isRecentDokumen($dokumen_id)
    {
        $twoWeeksAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        
        $dokumen = $this->select('created_at')
                       ->where('id', $dokumen_id)
                       ->where('status', 'aktif')
                       ->first();
                       
        if (!$dokumen) {
            return false;
        }
        
        return $dokumen['created_at'] >= $twoWeeksAgo;
    }

    // NEW METHOD: Get count of recent documents
    public function getRecentDokumenCount()
    {
        $twoWeeksAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        
        return $this->where('status', 'aktif')
                   ->where('created_at >=', $twoWeeksAgo)
                   ->countAllResults();
    }
}