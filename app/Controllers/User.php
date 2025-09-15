<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\MenuModel;
use App\Models\DokumenModel;
use App\Models\LogActivityModel;
use App\Models\UserModel;

class User extends BaseController
{
    protected $kategoriModel;
    protected $menuModel;
    protected $dokumenModel;
    protected $logModel;
    protected $userModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
        $this->menuModel = new MenuModel();
        $this->dokumenModel = new DokumenModel();
        $this->logModel = new LogActivityModel();
        $this->userModel = new UserModel();
    }

    public function dashboard()
    {
        $redirect = $this->redirectIfNotLoggedIn();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Dashboard User',
            'user' => $this->getUserData(),
            'menu' => $this->menuModel->getActiveMenuWithCount(),
            'kategori' => $this->kategoriModel->getKategoriWithCount(),
            'recent_docs' => $this->dokumenModel->getRecentDokumen(6),
            'popular_docs' => $this->dokumenModel->getPopularDokumen(6),
            'total_users' => $this->userModel->where('status', 'aktif')->countAllResults()
        ];

        return view('user/dashboard', $data);
    }

    // UPDATED: Method utama untuk semua dokumen dengan search integration
    public function dokumen()
    {
        $redirect = $this->redirectIfNotLoggedIn();
        if ($redirect) return $redirect;

        // Get search and filter parameters
        $keyword = $this->request->getGet('q');
        $menuFilter = $this->request->getGet('menu');
        $kategoriFilter = $this->request->getGet('kategori');

        // Initialize variables
        $dokumen = [];
        $filter_info = 'Semua Dokumen';
        $current_menu = null;
        $current_kategori = null;

        // Start building query with joins
        $builder = $this->dokumenModel->select('dokumen.*, menu.nama_menu, kategori.nama_kategori')
                                     ->join('menu', 'menu.id = dokumen.menu_id', 'left')
                                     ->join('kategori', 'kategori.id = dokumen.kategori_id', 'left')
                                     ->where('dokumen.status', 'aktif');

        // Apply menu filter
        if ($menuFilter && is_numeric($menuFilter)) {
            $builder->where('dokumen.menu_id', $menuFilter);
            $current_menu = $menuFilter;
            $menuInfo = $this->menuModel->find($menuFilter);
            if ($menuInfo) {
                $filter_info = $menuInfo['nama_menu'];
            }
        }

        // Apply category filter
        if ($kategoriFilter && is_numeric($kategoriFilter)) {
            $builder->where('dokumen.kategori_id', $kategoriFilter);
            $current_kategori = $kategoriFilter;
            $kategoriInfo = $this->kategoriModel->find($kategoriFilter);
            if ($kategoriInfo) {
                $filter_info = $kategoriInfo['nama_kategori'];
            }
        }

        // Apply search filter
        if ($keyword) {
            $searchTerms = explode(' ', trim($keyword));
            $builder->groupStart();
            
            foreach ($searchTerms as $term) {
                if (strlen($term) > 2) {
                    $builder->orLike('dokumen.judul', $term)
                           ->orLike('dokumen.deskripsi', $term)
                           ->orLike('dokumen.file_name', $term)
                           ->orLike('menu.nama_menu', $term)
                           ->orLike('kategori.nama_kategori', $term);
                }
            }
            $builder->groupEnd();
        }

        // Order results
        if ($keyword) {
            $builder->orderBy('dokumen.created_at', 'DESC');
        } else {
            $builder->orderBy('dokumen.created_at', 'DESC');
        }

        $dokumen = $builder->findAll();

        // Calculate search scores if searching
        if ($keyword && !empty($dokumen)) {
            foreach ($dokumen as &$doc) {
                $doc['search_score'] = $this->calculateSearchScore($doc, $keyword);
            }
            
            // Sort by search score
            usort($dokumen, function($a, $b) {
                return ($b['search_score'] ?? 0) <=> ($a['search_score'] ?? 0);
            });
        }

        // Get menu and categories for filters
        $allMenu = $this->menuModel->getActiveMenu();
        $allKategori = [];
        
        if ($current_menu) {
            // Show categories from selected menu
            $allKategori = $this->kategoriModel->getKategoriByMenu($current_menu);
        } elseif ($current_kategori) {
            // Show categories from same menu as selected category
            $kategoriInfo = $this->kategoriModel->find($current_kategori);
            if ($kategoriInfo && $kategoriInfo['menu_id']) {
                $allKategori = $this->kategoriModel->getKategoriByMenu($kategoriInfo['menu_id']);
            } else {
                $allKategori = $this->kategoriModel->getActiveKategori();
            }
        } else {
            // Show all categories
            $allKategori = $this->kategoriModel->getActiveKategori();
        }

        $data = [
            'title' => $keyword ? "Hasil Pencarian: $keyword" : $filter_info,
            'user' => $this->getUserData(),
            'dokumen' => $dokumen,
            'menu' => $allMenu,
            'kategori' => $allKategori,
            'filter_info' => $filter_info,
            'keyword' => $keyword,
            'current_menu' => $current_menu,
            'current_kategori' => $current_kategori
        ];

        return view('user/dokumen', $data);
    }

    // UPDATED: Method untuk dokumen by menu dengan search support
    public function dokumenByMenu($menu_id)
    {
        $redirect = $this->redirectIfNotLoggedIn();
        if ($redirect) return $redirect;

        // Get search keyword
        $keyword = $this->request->getGet('q');
        
        $menu_info = $this->menuModel->find($menu_id);
        if (!$menu_info) {
            return redirect()->to('/user/dokumen')->with('error', 'Menu tidak ditemukan');
        }

        // Build query with search support
        $builder = $this->dokumenModel->select('dokumen.*, menu.nama_menu, kategori.nama_kategori')
                                     ->join('menu', 'menu.id = dokumen.menu_id', 'left')
                                     ->join('kategori', 'kategori.id = dokumen.kategori_id', 'left')
                                     ->where('dokumen.menu_id', $menu_id)
                                     ->where('dokumen.status', 'aktif');

        // Apply search if keyword exists
        if ($keyword) {
            $searchTerms = explode(' ', trim($keyword));
            $builder->groupStart();
            
            foreach ($searchTerms as $term) {
                if (strlen($term) > 2) {
                    $builder->orLike('dokumen.judul', $term)
                           ->orLike('dokumen.deskripsi', $term)
                           ->orLike('dokumen.file_name', $term);
                }
            }
            $builder->groupEnd();
        }

        $dokumen = $builder->orderBy('dokumen.created_at', 'DESC')->findAll();

        // Calculate search scores if searching
        if ($keyword && !empty($dokumen)) {
            foreach ($dokumen as &$doc) {
                $doc['search_score'] = $this->calculateSearchScore($doc, $keyword);
            }
            
            usort($dokumen, function($a, $b) {
                return ($b['search_score'] ?? 0) <=> ($a['search_score'] ?? 0);
            });
        }

        // Get categories for this menu
        $kategori_filtered = $this->kategoriModel->getKategoriByMenu($menu_id);
        $allMenu = $this->menuModel->getActiveMenu();

        $data = [
            'title' => $keyword ? "Pencarian di {$menu_info['nama_menu']}: $keyword" : 'Dokumen - ' . $menu_info['nama_menu'],
            'user' => $this->getUserData(),
            'dokumen' => $dokumen,
            'menu' => $allMenu,
            'kategori' => $kategori_filtered,
            'filter_info' => $menu_info['nama_menu'],
            'keyword' => $keyword,
            'current_menu' => $menu_id,
            'current_kategori' => null
        ];

        return view('user/dokumen', $data);
    }

    // UPDATED: Method untuk dokumen by kategori dengan search support
    public function dokumenByKategori($kategori_id)
    {
        $redirect = $this->redirectIfNotLoggedIn();
        if ($redirect) return $redirect;

        // Get search keyword
        $keyword = $this->request->getGet('q');
        
        $kategori_info = $this->kategoriModel->find($kategori_id);
        if (!$kategori_info) {
            return redirect()->to('/user/dokumen')->with('error', 'Kategori tidak ditemukan');
        }

        // Build query with search support
        $builder = $this->dokumenModel->select('dokumen.*, menu.nama_menu, kategori.nama_kategori')
                                     ->join('menu', 'menu.id = dokumen.menu_id', 'left')
                                     ->join('kategori', 'kategori.id = dokumen.kategori_id', 'left')
                                     ->where('dokumen.kategori_id', $kategori_id)
                                     ->where('dokumen.status', 'aktif');

        // Apply search if keyword exists
        if ($keyword) {
            $searchTerms = explode(' ', trim($keyword));
            $builder->groupStart();
            
            foreach ($searchTerms as $term) {
                if (strlen($term) > 2) {
                    $builder->orLike('dokumen.judul', $term)
                           ->orLike('dokumen.deskripsi', $term)
                           ->orLike('dokumen.file_name', $term);
                }
            }
            $builder->groupEnd();
        }

        $dokumen = $builder->orderBy('dokumen.created_at', 'DESC')->findAll();

        // Calculate search scores if searching
        if ($keyword && !empty($dokumen)) {
            foreach ($dokumen as &$doc) {
                $doc['search_score'] = $this->calculateSearchScore($doc, $keyword);
            }
            
            usort($dokumen, function($a, $b) {
                return ($b['search_score'] ?? 0) <=> ($a['search_score'] ?? 0);
            });
        }

        // Get related categories from the same menu
        $menu_id_from_kategori = $kategori_info['menu_id'] ?? null;
        $kategori_filtered = [];
        
        if ($menu_id_from_kategori) {
            $kategori_filtered = $this->kategoriModel->getKategoriByMenu($menu_id_from_kategori);
        } else {
            $kategori_filtered = $this->kategoriModel->getActiveKategori();
        }

        $allMenu = $this->menuModel->getActiveMenu();

        $data = [
            'title' => $keyword ? "Pencarian di {$kategori_info['nama_kategori']}: $keyword" : 'Dokumen - ' . $kategori_info['nama_kategori'],
            'user' => $this->getUserData(),
            'dokumen' => $dokumen,
            'menu' => $allMenu,
            'kategori' => $kategori_filtered,
            'filter_info' => $kategori_info['nama_kategori'],
            'keyword' => $keyword,
            'current_kategori' => $kategori_id,
            'current_menu' => $menu_id_from_kategori
        ];

        return view('user/dokumen', $data);
    }

    // UPDATED: Search method - redirect to dokumen with search params
    public function search()
    {
        $redirect = $this->redirectIfNotLoggedIn();
        if ($redirect) return $redirect;

        $keyword = $this->request->getGet('q');
        $menuFilter = $this->request->getGet('menu');
        $kategoriFilter = $this->request->getGet('kategori');

        // Build query string for redirect
        $queryParams = [];
        if ($keyword) $queryParams['q'] = $keyword;
        if ($menuFilter) $queryParams['menu'] = $menuFilter;
        if ($kategoriFilter) $queryParams['kategori'] = $kategoriFilter;
        
        $queryString = !empty($queryParams) ? '?' . http_build_query($queryParams) : '';
        
        return redirect()->to('/user/dokumen' . $queryString);
    }

    // NEW: Helper method to calculate search score
    private function calculateSearchScore($doc, $keyword)
    {
        $score = 0;
        $searchTerms = explode(' ', strtolower(trim($keyword)));
        
        $title = strtolower($doc['judul'] ?? '');
        $description = strtolower($doc['deskripsi'] ?? '');
        $fileName = strtolower($doc['file_name'] ?? '');
        $menuName = strtolower($doc['nama_menu'] ?? '');
        $categoryName = strtolower($doc['nama_kategori'] ?? '');
        
        foreach ($searchTerms as $term) {
            if (strlen($term) > 2) {
                // Title matches are worth most
                if (strpos($title, $term) !== false) {
                    $score += 3;
                }
                
                // Description matches
                if (strpos($description, $term) !== false) {
                    $score += 2;
                }
                
                // File name matches
                if (strpos($fileName, $term) !== false) {
                    $score += 1;
                }
                
                // Menu name matches
                if (strpos($menuName, $term) !== false) {
                    $score += 1;
                }
                
                // Category name matches
                if (strpos($categoryName, $term) !== false) {
                    $score += 1;
                }
            }
        }
        
        // Normalize score (0-1 scale)
        $maxPossibleScore = count($searchTerms) * 8; // 3+2+1+1+1 per term
        return $maxPossibleScore > 0 ? $score / $maxPossibleScore : 0;
    }

    public function viewDokumen($id)
    {
        $redirect = $this->redirectIfNotLoggedIn();
        if ($redirect) return $redirect;

        $dokumen = $this->dokumenModel->getDokumenDetail($id);
        
        if (!$dokumen || $dokumen['status'] !== 'aktif') {
            return redirect()->to('/user/dashboard')->with('error', 'Dokumen tidak ditemukan');
        }

        // Increment views
        $this->dokumenModel->incrementViews($id);

        // Log activity
        try {
            $this->logModel->logActivity(
                $this->session->get('user_id'),
                'view',
                $id,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
        } catch (\Exception $e) {
            log_message('error', 'Failed to log view activity: ' . $e->getMessage());
        }

        $data = [
            'title' => $dokumen['judul'],
            'user' => $this->getUserData(),
            'dokumen' => $dokumen
        ];

        return view('user/view_dokumen', $data);
    }

    public function downloadDokumen($id)
    {
        $redirect = $this->redirectIfNotLoggedIn();
        if ($redirect) return $redirect;

        $dokumen = $this->dokumenModel->find($id);
        
        if (!$dokumen || $dokumen['status'] !== 'aktif') {
            return redirect()->to('/user/dashboard')->with('error', 'Dokumen tidak ditemukan');
        }

        $filePath = WRITEPATH . $dokumen['file_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server');
        }

        // Increment downloads
        $this->dokumenModel->incrementDownloads($id);

        // Log activity
        try {
            $this->logModel->logActivity(
                $this->session->get('user_id'),
                'download',
                $id,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
        } catch (\Exception $e) {
            log_message('error', 'Failed to log download activity: ' . $e->getMessage());
        }

        // Force download
        return $this->response->download($filePath, null)->setFileName($dokumen['file_name']);
    }

    public function profile()
    {
        $redirect = $this->redirectIfNotLoggedIn();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Profil Saya',
            'user' => $this->getUserData(),
            'activity' => $this->logModel->getUserActivity($this->session->get('user_id'), 15)
        ];

        return view('user/profile', $data);
    }
}