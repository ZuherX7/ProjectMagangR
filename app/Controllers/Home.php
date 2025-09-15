<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\KategoriModel;
use App\Models\DokumenModel;

class Home extends BaseController
{
    protected $menuModel;
    protected $kategoriModel;
    protected $dokumenModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->kategoriModel = new KategoriModel();
        $this->dokumenModel = new DokumenModel();
    }

    public function index()
    {
        // Redirect jika sudah login
        if ($this->isLoggedIn()) {
            return $this->redirectBasedOnRole();
        }

        $data = [
            'title' => 'SIDODIK - Sistem Informasi Dokumen',
            'menu' => $this->menuModel->getMenuWithCount(),
            'recent_docs' => $this->dokumenModel->getRecentDokumen(6)
        ];

        return view('home/index', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'Tentang SIDODIK'
        ];

        return view('home/about', $data);
    }

    private function redirectBasedOnRole()
    {
        $role = $this->session->get('role');
        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/user/dashboard');
        }
    }
}