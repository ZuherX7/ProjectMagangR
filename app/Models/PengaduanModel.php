<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaduanModel extends Model
{
    protected $table = 'pengaduan_dokumen';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'ticket_number', 'nama', 'email', 'telepon', 'jenis_pemohon', 'nip', 'instansi',
        'judul_dokumen', 'deskripsi_kebutuhan', 'kategori_permintaan',
        'urgency', 'status', 'admin_response', 'dokumen_terkait',
        'ip_address', 'user_agent', 'tanggal_respon'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|max_length[100]',
        'jenis_pemohon' => 'required|in_list[publik,asn]',
        'judul_dokumen' => 'required|min_length[5]|max_length[200]',
        'deskripsi_kebutuhan' => 'required|min_length[10]|max_length[1000]',
        'kategori_permintaan' => 'required|in_list[surat,laporan,formulir,panduan,lainnya]',
        'urgency' => 'required|in_list[rendah,sedang,tinggi,sangat_tinggi]'
    ];

    protected $validationMessages = [
        'nama' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama minimal 3 karakter'
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid'
        ],
        'jenis_pemohon' => [
            'required' => 'Jenis pemohon harus dipilih',
            'in_list' => 'Jenis pemohon tidak valid'
        ],
        'judul_dokumen' => [
            'required' => 'Judul dokumen harus diisi',
            'min_length' => 'Judul dokumen minimal 5 karakter'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom validation berdasarkan jenis pemohon
    public function validatePengaduan($data)
    {
        // Validasi dasar
        $rules = $this->validationRules;
        
        // Jika jenis pemohon adalah ASN, NIP wajib diisi
        if (isset($data['jenis_pemohon']) && $data['jenis_pemohon'] === 'asn') {
            $rules['nip'] = 'required|min_length[18]|max_length[20]|numeric';
        } else {
            // Jika publik, NIP boleh kosong
            $rules['nip'] = 'permit_empty|min_length[18]|max_length[20]|numeric';
        }

        $validation = \Config\Services::validation();
        $validation->setRules($rules, $this->validationMessages);
        
        return $validation->run($data);
    }

    // Custom Methods
    public function getPengaduanWithStats()
    {
        return $this->select('*, 
                             CASE 
                                WHEN status = "pending" THEN "Menunggu"
                                WHEN status = "proses" THEN "Diproses" 
                                WHEN status = "selesai" THEN "Selesai"
                                WHEN status = "ditolak" THEN "Ditolak"
                                ELSE "Unknown"
                             END as status_text,
                             CASE
                                WHEN urgency = "rendah" THEN "Rendah"
                                WHEN urgency = "sedang" THEN "Sedang"
                                WHEN urgency = "tinggi" THEN "Tinggi"
                                WHEN urgency = "sangat_tinggi" THEN "Sangat Tinggi"
                                ELSE "Unknown"
                             END as urgency_text,
                             CASE
                                WHEN jenis_pemohon = "publik" THEN "Publik"
                                WHEN jenis_pemohon = "asn" THEN "ASN"
                                ELSE "Unknown"
                             END as jenis_pemohon_text')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getPengaduanByStatus($status)
    {
        return $this->where('status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getPengaduanByJenis($jenis)
    {
        return $this->where('jenis_pemohon', $jenis)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getPengaduanByUrgency($urgency)
    {
        return $this->where('urgency', $urgency)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getRecentPengaduan($limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getStatistics()
    {
        $stats = [];
        
        // Total pengaduan
        $stats['total'] = $this->countAllResults();
        
        // Pengaduan berdasarkan status
        $stats['pending'] = $this->where('status', 'pending')->countAllResults();
        $stats['proses'] = $this->where('status', 'proses')->countAllResults();
        $stats['selesai'] = $this->where('status', 'selesai')->countAllResults();
        $stats['ditolak'] = $this->where('status', 'ditolak')->countAllResults();
        
        // Pengaduan berdasarkan jenis pemohon
        $stats['publik'] = $this->where('jenis_pemohon', 'publik')->countAllResults();
        $stats['asn'] = $this->where('jenis_pemohon', 'asn')->countAllResults();
        
        // Pengaduan berdasarkan urgency
        $stats['urgent'] = $this->whereIn('urgency', ['tinggi', 'sangat_tinggi'])->countAllResults();
        
        // Pengaduan hari ini
        $today = date('Y-m-d');
        $stats['today'] = $this->where('DATE(created_at)', $today)->countAllResults();
        
        // Pengaduan minggu ini
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $stats['this_week'] = $this->where('DATE(created_at) >=', $weekStart)->countAllResults();
        
        return $stats;
    }

    public function updateStatus($id, $status, $adminResponse = null)
    {
        $data = [
            'status' => $status,
            'tanggal_respon' => date('Y-m-d H:i:s')
        ];
        
        if ($adminResponse) {
            $data['admin_response'] = $adminResponse;
        }
        
        return $this->update($id, $data);
    }

    public function linkDokumen($id, $dokumenId)
    {
        return $this->update($id, ['dokumen_terkait' => $dokumenId]);
    }

    public function searchPengaduan($keyword)
    {
        return $this->groupStart()
                    ->like('nama', $keyword)
                    ->orLike('email', $keyword)
                    ->orLike('nip', $keyword)
                    ->orLike('judul_dokumen', $keyword)
                    ->orLike('ticket_number', $keyword)
                    ->orLike('deskripsi_kebutuhan', $keyword)
                    ->groupEnd()
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getPengaduanDetail($id)
    {
        return $this->select('pengaduan_dokumen.*, dokumen.judul as dokumen_judul, dokumen.file_path')
                    ->join('dokumen', 'dokumen.id = pengaduan_dokumen.dokumen_terkait', 'left')
                    ->where('pengaduan_dokumen.id', $id)
                    ->first();
    }
}