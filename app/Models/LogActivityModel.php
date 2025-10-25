<?php

namespace App\Models;

use CodeIgniter\Model;

class LogActivityModel extends Model
{
    protected $table = 'log_activity';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id', 'dokumen_id', 'activity', 'ip_address', 'user_agent'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';

    // Custom Methods
    public function logActivity($user_id, $activity, $dokumen_id = null, $ip_address = null, $user_agent = null)
    {
        $data = [
            'user_id' => $user_id,
            'activity' => $activity,
            'dokumen_id' => $dokumen_id,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent
        ];

        return $this->insert($data);
    }

    public function getRecentActivity($limit = 20)
    {
        return $this->db->table('log_activity l')
            ->select('l.*, u.nama_lengkap, d.judul as dokumen_judul')
            ->join('users u', 'u.id = l.user_id')
            ->join('dokumen d', 'd.id = l.dokumen_id', 'left')
            ->orderBy('l.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getUserActivity($user_id, $limit = 10)
    {
        return $this->db->table('log_activity l')
            ->select('l.*, d.judul as dokumen_judul')
            ->join('dokumen d', 'd.id = l.dokumen_id', 'left')
            ->where('l.user_id', $user_id)
            ->orderBy('l.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    // TAMBAHKAN METHOD INI DI LogActivityModel.php
    public function logDocumentAccess($user_id, $activity, $dokumen_id, $user_type, $ip_address = null, $user_agent = null)
    {
        $data = [
            'user_id' => $user_id,
            'activity' => $user_type . '_' . $activity, // admin_view, user_view, public_view, admin_download, dll
            'dokumen_id' => $dokumen_id,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent
        ];

        return $this->insert($data);
    }

    // Method untuk analisis berdasarkan user type
    public function getAccessAnalytics($dokumen_id = null)
    {
        $builder = $this->db->table('log_activity l')
            ->select('l.activity, COUNT(*) as count')
            ->where('l.activity LIKE', '%_view')
            ->orWhere('l.activity LIKE', '%_download');
            
        if ($dokumen_id) {
            $builder->where('l.dokumen_id', $dokumen_id);
        }
        
        $builder->groupBy('l.activity')
                ->orderBy('count', 'DESC');
                
        return $builder->get()->getResultArray();
    }
}