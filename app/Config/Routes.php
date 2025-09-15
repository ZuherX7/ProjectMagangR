<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// ============================================
// PUBLIC ROUTES (tidak perlu login)
// ============================================

// Homepage
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->get('/about', 'Home::about');

// ============================================
// AUTH ROUTES
// ============================================

// Login page
$routes->get('/login', 'Auth::login');
$routes->get('login', 'Auth::login');

// Process login - FIXED
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
    
    // Kategori management - FIXED
    $routes->get('kategori', 'Admin::kategori');
    $routes->post('kategori/add', 'Admin::addKategori');
    $routes->post('kategori/edit/(:num)', 'Admin::editKategori/$1');
    $routes->get('kategori/delete/(:num)', 'Admin::deleteKategori/$1');

    // Tambahkan route ini di bagian ADMIN ROUTES di Routes.php

    // AJAX endpoint untuk get kategori by menu
    $routes->get('kategori/by-menu/(:num)', 'Admin::getKategoriByMenu/$1');
    
    // Menu management - FIXED
    $routes->get('menu', 'Admin::menu');
    $routes->post('menu/add', 'Admin::addMenu');
    $routes->post('menu/edit/(:num)', 'Admin::editMenu/$1');
    $routes->get('menu/delete/(:num)', 'Admin::deleteMenu/$1');
    
    // Dokumen management - FIXED
    $routes->get('dokumen', 'Admin::dokumen');
    $routes->post('dokumen/add', 'Admin::addDokumen');
    $routes->get('dokumen/edit/(:num)', 'Admin::editDokumenPage/$1');
    $routes->post('dokumen/edit/(:num)', 'Admin::editDokumen/$1');
    $routes->get('dokumen/delete/(:num)', 'Admin::deleteDokumen/$1');

    // User management - BARU
    $routes->get('user', 'Admin::user');
    $routes->post('user/add', 'Admin::addUser');
    $routes->post('user/edit/(:num)', 'Admin::editUser/$1');
    $routes->get('user/delete/(:num)', 'Admin::deleteUser/$1');
});

// ============================================
// USER ROUTES (need user login)
// ============================================

$routes->group('user', function($routes) {
    // Dashboard
    $routes->get('/', 'User::dashboard');
    $routes->get('dashboard', 'User::dashboard');
    
    // Dokumen browsing - FIXED ROUTES
    $routes->get('dokumen', 'User::dokumen');
    $routes->get('dokumen/menu/(:num)', 'User::dokumenByMenu/$1');      // UBAH INI
    $routes->get('dokumen/kategori/(:num)', 'User::dokumenByKategori/$1'); // UBAH INI
    $routes->get('dokumen/view/(:num)', 'User::viewDokumen/$1');
    $routes->get('dokumen/download/(:num)', 'User::downloadDokumen/$1');
    
    // Search & profile
    $routes->get('search', 'User::search');
    $routes->get('profile', 'User::profile');
});

// ============================================
// FILE SERVING (protected files) - IMPROVED
// ============================================

$routes->get('files/(:any)', function($path) {
    $filePath = WRITEPATH . $path;  // HAPUS 'uploads/documents/'
    
    if (file_exists($filePath)) {
        // Check if user is logged in
        $session = \Config\Services::session();
        if (!$session->get('user_id')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }
        
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