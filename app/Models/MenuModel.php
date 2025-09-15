<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    // PERBAIKAN: Tambahkan 'icon' ke allowedFields
    protected $allowedFields = [
        'nama_menu', 'deskripsi', 'icon', 'status'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // PERBAIKAN: Perbaiki validasi rules
    protected $validationRules = [
        'nama_menu' => 'required|min_length[3]|max_length[100]',
        'deskripsi' => 'permit_empty|max_length[500]',
        'icon' => 'permit_empty|max_length[50]',
        'status' => 'required|in_list[aktif,nonaktif]'
    ];

    protected $validationMessages = [
        'nama_menu' => [
            'required' => 'Nama menu harus diisi',
            'min_length' => 'Nama menu minimal 3 karakter',
            'max_length' => 'Nama menu maksimal 100 karakter'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status harus aktif atau nonaktif'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom Methods
    public function getActiveMenu()
    {
        return $this->where('status', 'aktif')
                    ->orderBy('nama_menu', 'ASC')
                    ->findAll();
    }

    // UNTUK ADMIN: Menampilkan semua menu (aktif dan nonaktif)
    public function getMenuWithCount()
    {
        return $this->db->table('menu m')
            ->select('m.*, COUNT(d.id) as jumlah_dokumen')
            ->join('dokumen d', 'd.menu_id = m.id', 'left')
            // Tidak ada filter status - untuk admin
            ->groupBy('m.id')
            ->orderBy('m.nama_menu', 'ASC')
            ->get()
            ->getResultArray();
    }

    // BARU: UNTUK USER DASHBOARD: Hanya menampilkan menu aktif dengan count
    public function getActiveMenuWithCount()
    {
        return $this->db->table('menu m')
            ->select('m.*, COUNT(d.id) as jumlah_dokumen')
            ->join('dokumen d', 'd.menu_id = m.id AND d.status = "aktif"', 'left')
            ->where('m.status', 'aktif') // Filter hanya menu aktif
            ->groupBy('m.id')
            ->orderBy('m.nama_menu', 'ASC')
            ->get()
            ->getResultArray();
    }

    // PERBAIKAN: Method untuk validasi edit dengan unique check
    public function validateForEdit($id, $data)
    {
        $rules = [
            'nama_menu' => "required|min_length[3]|max_length[100]|is_unique[menu.nama_menu,id,$id]",
            'deskripsi' => 'permit_empty|max_length[500]',
            'icon' => 'permit_empty|max_length[50]',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        return $this->validate($rules);
    }
}