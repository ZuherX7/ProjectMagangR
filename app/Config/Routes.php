<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Landing');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// ============================================
// LANDING PAGE ROUTES (public access)
// ============================================

// Landing page - Default route
$routes->get('/', 'Landing::index');
$routes->get('/home', 'Landing::index'); // fallback
$routes->get('/about', 'Landing::about');
$routes->get('/dokumen-publik', 'Landing::dokumen');

// Dokumen view and download dari landing page (public access dengan kontrol akses)
$routes->get('/dokumen/view/(:num)', 'Landing::viewDokumen/$1');
$routes->get('/dokumen/download/(:num)', 'Landing::downloadDokumen/$1');

// Pengaduan system
$routes->post('/pengaduan/submit', 'Landing::submitPengaduan');
$routes->get('/pengaduan/status', 'Landing::cekStatus');
$routes->post('/pengaduan/status', 'Landing::cekStatus');
// API untuk cek status pengaduan (public access)
$routes->get('/api/status', 'Landing::apiStatus');
$routes->post('/api/status', 'Landing::apiStatus');

// ============================================
// AUTH ROUTES
// ============================================

// Login page
$routes->get('/login', 'Auth::login');
$routes->get('login', 'Auth::login');

// Process login
$routes->post('/auth/processLogin', 'Auth::processLogin');
$routes->post('auth/processLogin', 'Auth::processLogin');

// Logout
$routes->get('/logout', 'Auth::logout');
$routes->get('logout', 'Auth::logout');

// Auth group (alternative routes)
$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::login');
    $routes->get('login', 'Auth::login');
    $routes->post('processLogin', 'Auth::processLogin');
    $routes->post('forgotPassword', 'Auth::forgotPassword');
    $routes->get('logout', 'Auth::logout');
    
    // Test route untuk debug
    $routes->get('test', 'Auth::test');
});

// ============================================
// ADMIN ROUTES (need admin login)
// ============================================

$routes->group('admin', function($routes) {
    // Dashboard
    $routes->get('/', 'Admin::dashboard');
    $routes->get('dashboard', 'Admin::dashboard');
    
    // Kategori management
    $routes->get('kategori', 'Admin::kategori');
    $routes->post('kategori/add', 'Admin::addKategori');
    $routes->post('kategori/edit/(:num)', 'Admin::editKategori/$1');
    $routes->get('kategori/delete/(:num)', 'Admin::deleteKategori/$1');
    $routes->get('kategori/by-menu/(:num)', 'Admin::getKategoriByMenu/$1');
    
    // Menu management
    $routes->get('menu', 'Admin::menu');
    $routes->post('menu/add', 'Admin::addMenu');
    $routes->post('menu/edit/(:num)', 'Admin::editMenu/$1');
    $routes->get('menu/delete/(:num)', 'Admin::deleteMenu/$1');
    
    // Dokumen management
    $routes->get('dokumen', 'Admin::dokumen');
    $routes->post('dokumen/add', 'Admin::addDokumen');
    $routes->get('dokumen/edit/(:num)', 'Admin::editDokumenPage/$1');
    $routes->post('dokumen/edit/(:num)', 'Admin::editDokumen/$1');
    $routes->get('dokumen/delete/(:num)', 'Admin::deleteDokumen/$1');

    // Dokumen view and download (increment counter untuk admin)
    $routes->get('dokumen/view/(:num)', 'Admin::viewDokumen/$1');
    $routes->get('dokumen/download/(:num)', 'Admin::downloadDokumen/$1');

    // User management
    $routes->get('user', 'Admin::user');
    $routes->post('user/add', 'Admin::addUser');
    $routes->post('user/edit/(:num)', 'Admin::editUser/$1');
    $routes->get('user/delete/(:num)', 'Admin::deleteUser/$1');
    
    $routes->get('pengaduan', 'Admin::pengaduan');
    $routes->get('pengaduan/detail/(:num)', 'Admin::detailPengaduan/$1');
    $routes->post('pengaduan/update-status/(:num)', 'Admin::updateStatusPengaduan/$1');
    $routes->post('pengaduan/link-dokumen/(:num)', 'Admin::linkDokumenPengaduan/$1'); // PERBAIKI NAMA METHOD
    $routes->get('pengaduan/delete/(:num)', 'Admin::deletePengaduan/$1');

    // TAMBAHKAN ROUTES INI KE DALAM GROUP 'admin' DI Routes.php
    // LETAKKAN SETELAH ROUTES USER MANAGEMENT

    // Pengaduan management - UPDATED dengan detail dan notifications
    $routes->get('pengaduan', 'Admin::pengaduan');
    $routes->post('pengaduan/update-status/(:num)', 'Admin::updateStatusPengaduan/$1');
    $routes->post('pengaduan/link-dokumen/(:num)', 'Admin::linkDokumenPengaduan/$1');
    $routes->get('pengaduan/delete/(:num)', 'Admin::deletePengaduan/$1');
    $routes->get('pengaduan/detail/(:num)', 'Admin::detailPengaduan/$1');
    
    // AJAX endpoint untuk notifications
    $routes->get('pengaduan/notifications', 'Admin::getPengaduanNotifications');
    
    // AJAX endpoints untuk pengaduan
    $routes->get('pengaduan/pending-count', 'Admin::getPendingPengaduanCount');
    $routes->get('pengaduan/search', 'Admin::searchPengaduan');

    // Dashboard Export
    $routes->get('dashboard/export', 'Admin::exportDashboardReport');

    // Rekap Pengaduan (sudah ada, biarkan tetap)
    $routes->get('pengaduan/rekap-data', 'Admin::getRekapData');
    $routes->get('pengaduan/export-rekap', 'Admin::exportRekapPengaduan');

    // Rekap Pengaduan
    $routes->get('pengaduan/rekap-data', 'Admin::getRekapData');
    $routes->get('pengaduan/export-rekap', 'Admin::exportRekapPengaduan');

    // Activity Details & Export (di dalam group 'admin')
    $routes->get('analytics/activity-details', 'Admin::getActivityDetails');
    $routes->get('analytics/activity-export', 'Admin::exportActivityReport');
});

// ============================================
// USER ROUTES (need user login)
// ============================================

$routes->group('user', function($routes) {
    // Dashboard
    $routes->get('/', 'User::dashboard');
    $routes->get('dashboard', 'User::dashboard');
    
    // Redirect dokumen routes ke landing page
    $routes->get('dokumen', function() {
        return redirect()->to('/dokumen-publik');
    });
    $routes->get('dokumen/menu/(:num)', function($menuId) {
        return redirect()->to('/dokumen-publik?menu=' . $menuId);
    });
    $routes->get('dokumen/kategori/(:num)', function($kategoriId) {
        return redirect()->to('/dokumen-publik?kategori=' . $kategoriId);
    });
    $routes->get('dokumen/view/(:num)', function($docId) {
        return redirect()->to('/dokumen-publik');
    });
    $routes->get('dokumen/download/(:num)', 'User::downloadDokumen/$1');
    
    // Search & profile
    $routes->get('search', function() {
        return redirect()->to('/dokumen-publik');
    });
    $routes->get('profile', 'User::profile');
});

// ============================================
// FILE SERVING (protected files)
// ============================================

// GANTI route /files/(:any) di Routes.php dengan yang ini
$routes->get('files/(:any)', function($path) {
    $filePath = WRITEPATH . $path;
    
    if (file_exists($filePath)) {
        // Check if user is logged in OR if document is public
        $session = \Config\Services::session();
        $db = \Config\Database::connect();
        
        // Extract document ID from path if possible
        $pathParts = explode('/', $path);
        $fileName = end($pathParts);
        
        // Check if document is public
        $query = $db->query("SELECT akses FROM dokumen WHERE file_path = ?", [$path]);
        $dokumen = $query->getRow();
        
        if (!$dokumen) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }
        
        // Allow access if document is public OR user is logged in
        if ($dokumen->akses !== 'publik' && !$session->get('user_id')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }
        
        // TIDAK INCREMENT COUNTER DI SINI - biar cuma increment lewat controller
        // Counter hanya increment lewat method viewDokumen/downloadDokumen di controller
        
        // Get file info
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        
        // Set appropriate headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: private, must-revalidate, max-age=0');
        header('Pragma: private');
        
        readfile($filePath);
        exit;
    }
    
    throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
});

// ============================================
// ERROR HANDLING
// ============================================

$routes->get('404', function() {
    return view('errors/404');
});

// ============================================
// DEVELOPMENT ROUTES (only in development)
// ============================================

if (ENVIRONMENT === 'development') {
    $routes->get('dev/info', function() {
        phpinfo();
    });
    
    $routes->get('dev/routes', function() {
        $routes = service('routes');
        echo '<pre>';
        print_r($routes->getRoutes());
        echo '</pre>';
    });
    
    // Test upload directory
    $routes->get('dev/test-upload', function() {
        $uploadDir = WRITEPATH . 'uploads/documents/';
        
        echo '<h3>Upload Directory Test</h3>';
        echo '<p>Path: ' . $uploadDir . '</p>';
        echo '<p>Exists: ' . (is_dir($uploadDir) ? 'YES' : 'NO') . '</p>';
        echo '<p>Writable: ' . (is_writable($uploadDir) ? 'YES' : 'NO') . '</p>';
        
        if (!is_dir($uploadDir)) {
            if (mkdir($uploadDir, 0755, true)) {
                echo '<p style="color: green;">Directory created successfully!</p>';
            } else {
                echo '<p style="color: red;">Failed to create directory!</p>';
            }
        }
        
        // List files
        if (is_dir($uploadDir)) {
            $files = scandir($uploadDir);
            echo '<h4>Files in upload directory:</h4>';
            echo '<ul>';
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    echo '<li>' . $file . ' (' . filesize($uploadDir . $file) . ' bytes)</li>';
                }
            }
            echo '</ul>';
        }
    });
}
?>