<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nama_kategori', 'menu_id', 'deskripsi', 'status'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // PERBAIKAN: Simplifikasi validation rules dengan menu_id
    protected $validationRules = [
        'nama_kategori' => 'required|min_length[3]|max_length[100]',
        'menu_id' => 'required|integer',
        'status' => 'in_list[aktif,nonaktif]'
    ];

    protected $validationMessages = [
        'nama_kategori' => [
            'required' => 'Nama kategori harus diisi',
            'min_length' => 'Nama kategori minimal 3 karakter',
            'max_length' => 'Nama kategori maksimal 100 karakter'
        ],
        'menu_id' => [
            'required' => 'Menu harus dipilih',
            'integer' => 'Menu tidak valid'
        ],
        'status' => [
            'in_list' => 'Status harus aktif atau nonaktif'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // PERBAIKAN: Override beforeUpdate untuk handle unique validation
    protected function beforeUpdate(array $data)
    {
        // Jika ini adalah update, kita perlu handle unique validation secara manual
        if (isset($data['data']['nama_kategori']) && isset($data['data']['menu_id']) && isset($data['id'])) {
            $existingKategori = $this->where('nama_kategori', $data['data']['nama_kategori'])
                                   ->where('menu_id', $data['data']['menu_id'])
                                   ->where('id !=', $data['id'][0])
                                   ->first();
                                   
            if ($existingKategori) {
                $this->errors = ['nama_kategori' => 'Nama kategori sudah ada dalam menu ini'];
                return false;
            }
        }
        
        return $data;
    }

    // Custom Methods
    public function getActiveKategori()
    {
        return $this->where('status', 'aktif')->findAll();
    }

    // PERBAIKAN: Update getKategoriWithCount dengan join menu
    public function getKategoriWithCount()
    {
        return $this->db->table('kategori k')
            ->select('k.*, m.nama_menu, COUNT(d.id) as jumlah_dokumen')
            ->join('menu m', 'm.id = k.menu_id', 'left')
            ->join('dokumen d', 'd.kategori_id = k.id', 'left')
            ->groupBy('k.id')
            ->orderBy('m.nama_menu', 'ASC')
            ->orderBy('k.nama_kategori', 'ASC')
            ->get()
            ->getResultArray();
    }

    // PERBAIKAN: Method untuk get kategori berdasarkan menu
    public function getKategoriByMenu($menuId)
    {
        return $this->where('menu_id', $menuId)
                    ->where('status', 'aktif')
                    ->orderBy('nama_kategori', 'ASC')
                    ->findAll();
    }

    // PERBAIKAN: Method untuk update dengan logging
    public function updateKategori($id, $data)
    {
        try {
            // Log data yang akan diupdate
            log_message('info', "Attempting to update kategori ID: $id with data: " . print_r($data, true));
            
            // Validasi manual untuk unique nama_kategori dalam menu yang sama
            $existing = $this->where('nama_kategori', $data['nama_kategori'])
                            ->where('menu_id', $data['menu_id'])
                            ->where('id !=', $id)
                            ->first();
                            
            if ($existing) {
                log_message('error', "Duplicate nama_kategori found in same menu: " . $data['nama_kategori']);
                return false;
            }
            
            // Update data
            $result = $this->update($id, $data);
            
            if ($result) {
                log_message('info', "Successfully updated kategori ID: $id");
            } else {
                log_message('error', "Failed to update kategori ID: $id. Errors: " . print_r($this->errors(), true));
            }
            
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', "Exception in updateKategori: " . $e->getMessage());
            return false;
        }
    }
}