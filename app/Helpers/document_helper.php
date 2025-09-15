<?php
// File: app/Helpers/document_helper.php

if (!function_exists('formatBytes')) {
    /**
     * Format bytes to human readable size
     */
    function formatBytes($size, $precision = 2)
    {
        if ($size == 0) return '0 B';
        
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $base = log($size, 1024);
        $pow = pow(1024, $base - floor($base));
        
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $units[floor($base)];
    }
}

if (!function_exists('getFileIcon')) {
    /**
     * Get file icon based on extension
     */
    function getFileIcon($extension)
    {
        $icons = [
            'pdf' => 'fas fa-file-pdf',
            'doc' => 'fas fa-file-word',
            'docx' => 'fas fa-file-word',
            'xls' => 'fas fa-file-excel',
            'xlsx' => 'fas fa-file-excel',
            'ppt' => 'fas fa-file-powerpoint',
            'pptx' => 'fas fa-file-powerpoint',
            'txt' => 'fas fa-file-alt',
            'zip' => 'fas fa-file-archive',
            'rar' => 'fas fa-file-archive',
            'jpg' => 'fas fa-file-image',
            'jpeg' => 'fas fa-file-image',
            'png' => 'fas fa-file-image',
            'gif' => 'fas fa-file-image',
        ];
        
        return $icons[strtolower($extension)] ?? 'fas fa-file';
    }
}

if (!function_exists('getFileIconColor')) {
    /**
     * Get file icon color based on extension
     */
    function getFileIconColor($extension)
    {
        $colors = [
            'pdf' => '#dc3545',
            'doc' => '#2b579a',
            'docx' => '#2b579a',
            'xls' => '#217346',
            'xlsx' => '#217346',
            'ppt' => '#d24726',
            'pptx' => '#d24726',
            'txt' => '#6c757d',
            'zip' => '#ffc107',
            'rar' => '#ffc107',
            'jpg' => '#28a745',
            'jpeg' => '#28a745',
            'png' => '#28a745',
            'gif' => '#28a745',
        ];
        
        return $colors[strtolower($extension)] ?? '#6c757d';
    }
}

if (!function_exists('sanitizeFileName')) {
    /**
     * Sanitize filename for safe storage
     */
    function sanitizeFileName($filename)
    {
        // Remove special characters and spaces
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);
        
        // Trim underscores from start and end
        return trim($filename, '_');
    }
}

if (!function_exists('createUploadDir')) {
    /**
     * Create upload directory if not exists
     */
    function createUploadDir($path = null)
    {
        if (!$path) {
            $path = WRITEPATH . 'uploads/documents/' . date('Y/m/');
        }
        
        if (!is_dir($path)) {
            return mkdir($path, 0755, true);
        }
        
        return true;
    }
}

if (!function_exists('validateFileType')) {
    /**
     * Validate file type
     */
    function validateFileType($file, $allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])
    {
        if (!$file || !$file->isValid()) {
            return false;
        }
        
        $extension = strtolower($file->getClientExtension());
        return in_array($extension, $allowedTypes);
    }
}

if (!function_exists('getDocumentUrl')) {
    /**
     * Get secure document URL
     */
    function getDocumentUrl($filePath)
    {
        // Remove the 'uploads/documents/' prefix if exists
        $cleanPath = str_replace('uploads/documents/', '', $filePath);
        return base_url('files/' . $cleanPath);
    }
}

if (!function_exists('isDocumentAccessible')) {
    /**
     * Check if document is accessible by current user
     */
    function isDocumentAccessible($documentId, $userId)
    {
        // For now, all logged in users can access all documents
        // You can implement more complex logic here based on roles, permissions, etc.
        return $userId !== null;
    }
}

if (!function_calls('timeAgo')) {
    /**
     * Convert timestamp to "time ago" format
     */
    function timeAgo($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'tahun',
            'm' => 'bulan',
            'w' => 'minggu',
            'd' => 'hari',
            'h' => 'jam',
            'i' => 'menit',
            's' => 'detik',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v;
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' lalu' : 'baru saja';
    }
}