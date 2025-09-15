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
}