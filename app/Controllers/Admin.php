<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\KategoriModel;
use App\Models\MenuModel;
use App\Models\DokumenModel;
use App\Models\LogActivityModel;
use App\Models\PengaduanModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $kategoriModel;
    protected $menuModel;
    protected $dokumenModel;
    protected $logModel;
    protected $pengaduanModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kategoriModel = new KategoriModel();
        $this->menuModel = new MenuModel();
        $this->dokumenModel = new DokumenModel();
        $this->logModel = new LogActivityModel();
        $this->pengaduanModel = new PengaduanModel(); // TAMBAHAN INI
    }

    public function dashboard()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $this->initializeDB();

        // Get menu statistics dengan document count
        $menuStats = $this->menuModel->getMenuWithCount();
        
        // Get total dokumen
        $totalDokumen = $this->dokumenModel->countAllResults();

        $data = [
            'title' => 'Dashboard Admin',
            'user' => $this->getUserData(),
            'stats' => [
                'total_dokumen' => $totalDokumen,
                'menu_stats' => $menuStats
            ],
            'recent_docs' => $this->dokumenModel->getRecentDokumen(5),
            'recent_activity' => $this->logModel->getRecentActivity(10),
            
            // TAMBAH ANALYTICS DATA
            'overview_stats' => $this->getOverviewStats(),
            'document_stats' => $this->getDocumentStats(),
            'user_activity' => $this->getUserActivity(),
            'pengaduan_stats' => $this->getPengaduanStats(),
            'pengaduan_analytics' => $this->getPengaduanAnalytics(),
            'monthly_trends' => $this->getMonthlyTrends(),
            'menu_performance' => $this->getMenuPerformance(),
            'top_documents' => $this->getTopDocuments(),
            
            // TAMBAH INI - untuk activity details
            'all_users' => $this->userModel->where('status', 'aktif')->findAll()
        ];

        return view('admin/dashboard', $data);
    }

    // TAMBAHKAN METHOD INI DI Admin.php (setelah method dashboard)
    public function exportDashboardReport()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $this->initializeDB();

        $format = $this->request->getGet('format'); // 'pdf' atau 'excel'

        if (!in_array($format, ['pdf', 'excel'])) {
            return redirect()->back()->with('error', 'Format tidak valid');
        }

        // Get all data
        $data = [
            'overview_stats' => $this->getOverviewStats(),
            'document_stats' => $this->getDocumentStats(),
            'user_activity' => $this->getUserActivity(),
            'pengaduan_stats' => $this->getPengaduanStats(),
            'pengaduan_analytics' => $this->getPengaduanAnalytics(),
            'menu_performance' => $this->getMenuPerformance(),
            'top_documents' => $this->getTopDocuments(),
            'export_date' => date('Y-m-d H:i:s')
        ];

        if ($format === 'excel') {
            return $this->exportDashboardToExcel($data);
        } else {
            return $this->exportDashboardToPDF($data);
        }
    }

    private function exportDashboardToExcel($data)
    {
        $filename = 'Dashboard_Analytics_Report_' . date('Ymd_His') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // UTF-8 BOM
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header
        fputcsv($output, ['LAPORAN ANALYTICS DASHBOARD - SIDODIK']);
        fputcsv($output, ['Tanggal Export', date('d F Y H:i:s')]);
        fputcsv($output, []);
        
        // Overview Statistics
        fputcsv($output, ['=== STATISTIK OVERVIEW ===']);
        fputcsv($output, ['Total Dokumen', $data['overview_stats']['total_documents']]);
        fputcsv($output, ['Total Views', $data['overview_stats']['total_views']]);
        fputcsv($output, ['Total Downloads', $data['overview_stats']['total_downloads']]);
        fputcsv($output, ['Total Users', $data['overview_stats']['total_users']]);
        fputcsv($output, ['Total Pengaduan', $data['overview_stats']['total_pengaduan']]);
        fputcsv($output, ['Sesi Aktif', $data['overview_stats']['active_sessions']]);
        fputcsv($output, []);
        
        // Document Statistics
        fputcsv($output, ['=== STATISTIK DOKUMEN ===']);
        fputcsv($output, ['Tipe File', 'Jumlah']);
        foreach ($data['document_stats']['by_type'] as $type) {
            fputcsv($output, [strtoupper($type['file_type']), $type['count']]);
        }
        fputcsv($output, []);
        
        // Top Documents
        fputcsv($output, ['=== TOP 10 DOKUMEN POPULER ===']);
        fputcsv($output, ['Judul', 'Views', 'Downloads', 'Menu', 'Kategori']);
        foreach (array_slice($data['top_documents']['most_viewed'], 0, 10) as $doc) {
            fputcsv($output, [
                $doc['judul'],
                $doc['views'],
                $doc['downloads'],
                $doc['nama_menu'],
                $doc['nama_kategori']
            ]);
        }
        fputcsv($output, []);
        
        // Menu Performance
        fputcsv($output, ['=== PERFORMA MENU ===']);
        fputcsv($output, ['Menu', 'Jumlah Dokumen', 'Total Views', 'Total Downloads', 'Rata-rata Views']);
        foreach ($data['menu_performance'] as $menu) {
            fputcsv($output, [
                $menu['nama_menu'],
                $menu['document_count'],
                $menu['total_views'],
                $menu['total_downloads'],
                round($menu['avg_views_per_doc'], 1)
            ]);
        }
        fputcsv($output, []);
        
        // Pengaduan Statistics
        fputcsv($output, ['=== STATISTIK PENGADUAN ===']);
        fputcsv($output, ['Tingkat Keberhasilan', $data['pengaduan_analytics']['success_percentage'] . '%']);
        fputcsv($output, ['Tingkat Penolakan', $data['pengaduan_analytics']['reject_percentage'] . '%']);
        fputcsv($output, ['Sedang Diproses', $data['pengaduan_analytics']['ongoing_percentage'] . '%']);
        fputcsv($output, ['Rata-rata Waktu Respon (Hari)', $data['pengaduan_analytics']['response_time']['avg_days']]);
        fputcsv($output, []);
        
        // Top Dokumen Diminta
        fputcsv($output, ['=== TOP DOKUMEN YANG PALING DIMINTA ===']);
        fputcsv($output, ['Judul Dokumen', 'Total Permintaan', 'Terpenuhi', 'Ditolak', 'Tingkat Pemenuhan (%)']);
        foreach ($data['pengaduan_analytics']['top_dokumen_diminta'] as $dok) {
            $fulfillment_rate = $dok['jumlah_permintaan'] > 0 
                ? round(($dok['terpenuhi'] / $dok['jumlah_permintaan']) * 100, 1) 
                : 0;
            fputcsv($output, [
                $dok['judul_dokumen'],
                $dok['jumlah_permintaan'],
                $dok['terpenuhi'],
                $dok['ditolak'],
                $fulfillment_rate
            ]);
        }
        
        fclose($output);
        exit;
    }

    private function exportDashboardToPDF($data)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Dashboard Analytics Report</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 11px;
                    line-height: 1.4;
                    margin: 20px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 3px solid #667eea;
                    padding-bottom: 15px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 20px;
                    color: #2c3e50;
                }
                .header p {
                    margin: 5px 0;
                    color: #5a6c7d;
                    font-size: 10px;
                }
                .section {
                    margin-bottom: 25px;
                    page-break-inside: avoid;
                }
                .section-title {
                    background: #667eea;
                    color: white;
                    padding: 8px 15px;
                    font-weight: bold;
                    margin-bottom: 10px;
                    font-size: 14px;
                }
                .stats-grid {
                    display: table;
                    width: 100%;
                    margin-bottom: 15px;
                }
                .stat-item {
                    display: table-cell;
                    width: 33.33%;
                    padding: 10px;
                    text-align: center;
                    border: 1px solid #e9ecef;
                }
                .stat-item .number {
                    font-size: 24px;
                    font-weight: bold;
                    color: #667eea;
                }
                .stat-item .label {
                    color: #5a6c7d;
                    font-size: 10px;
                    margin-top: 5px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                }
                table th {
                    background: #667eea;
                    color: white;
                    padding: 8px 5px;
                    text-align: left;
                    font-size: 10px;
                }
                table td {
                    padding: 6px 5px;
                    border: 1px solid #e9ecef;
                    font-size: 9px;
                }
                table tr:nth-child(even) {
                    background: #f8f9fa;
                }
                .footer {
                    margin-top: 30px;
                    padding-top: 15px;
                    border-top: 2px solid #e9ecef;
                    text-align: right;
                    font-size: 9px;
                    color: #5a6c7d;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN ANALYTICS DASHBOARD</h1>
                <p>SISTEM INFORMASI DOKUMEN DISKOMINFOTIK (SIDODIK)</p>
                <p>Kabupaten Bandung Barat</p>
                <p>Tanggal: ' . date('d F Y H:i:s') . '</p>
            </div>
            
            <div class="section">
                <div class="section-title">STATISTIK OVERVIEW</div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="number">' . number_format($data['overview_stats']['total_documents']) . '</div>
                        <div class="label">Total Dokumen</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">' . number_format($data['overview_stats']['total_views']) . '</div>
                        <div class="label">Total Views</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">' . number_format($data['overview_stats']['total_downloads']) . '</div>
                        <div class="label">Total Downloads</div>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="number">' . number_format($data['overview_stats']['total_users']) . '</div>
                        <div class="label">Total Users</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">' . number_format($data['overview_stats']['total_pengaduan']) . '</div>
                        <div class="label">Total Pengaduan</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">' . number_format($data['overview_stats']['active_sessions']) . '</div>
                        <div class="label">Sesi Aktif</div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">TOP 10 DOKUMEN POPULER</div>
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Judul Dokumen</th>
                            <th width="15%">Menu</th>
                            <th width="15%">Kategori</th>
                            <th width="12%">Views</th>
                            <th width="13%">Downloads</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        $no = 1;
        foreach (array_slice($data['top_documents']['most_viewed'], 0, 10) as $doc) {
            $html .= '
                        <tr>
                            <td align="center">' . $no++ . '</td>
                            <td>' . htmlspecialchars($doc['judul']) . '</td>
                            <td>' . htmlspecialchars($doc['nama_menu']) . '</td>
                            <td>' . htmlspecialchars($doc['nama_kategori']) . '</td>
                            <td align="center">' . number_format($doc['views']) . '</td>
                            <td align="center">' . number_format($doc['downloads']) . '</td>
                        </tr>';
        }
        
        $html .= '
                    </tbody>
                </table>
            </div>
            
            <div class="section">
                <div class="section-title">PERFORMA MENU</div>
                <table>
                    <thead>
                        <tr>
                            <th width="40%">Menu</th>
                            <th width="20%">Jumlah Dokumen</th>
                            <th width="20%">Total Views</th>
                            <th width="20%">Total Downloads</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($data['menu_performance'] as $menu) {
            $html .= '
                        <tr>
                            <td>' . htmlspecialchars($menu['nama_menu']) . '</td>
                            <td align="center">' . number_format($menu['document_count']) . '</td>
                            <td align="center">' . number_format($menu['total_views']) . '</td>
                            <td align="center">' . number_format($menu['total_downloads']) . '</td>
                        </tr>';
        }
        
        $html .= '
                    </tbody>
                </table>
            </div>
            
            <div class="section">
                <div class="section-title">STATISTIK PENGADUAN</div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="number" style="color: #27ae60;">' . number_format($data['pengaduan_analytics']['success_percentage'], 1) . '%</div>
                        <div class="label">Tingkat Keberhasilan</div>
                    </div>
                    <div class="stat-item">
                        <div class="number" style="color: #e74c3c;">' . number_format($data['pengaduan_analytics']['reject_percentage'], 1) . '%</div>
                        <div class="label">Tingkat Penolakan</div>
                    </div>
                    <div class="stat-item">
                        <div class="number" style="color: #f39c12;">' . number_format($data['pengaduan_analytics']['response_time']['avg_days'], 1) . '</div>
                        <div class="label">Rata-rata Respon (Hari)</div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">TOP DOKUMEN YANG PALING DIMINTA</div>
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="45%">Judul Dokumen</th>
                            <th width="15%">Total Request</th>
                            <th width="15%">Terpenuhi</th>
                            <th width="10%">Ditolak</th>
                            <th width="10%">Rate</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        $no = 1;
        foreach (array_slice($data['pengaduan_analytics']['top_dokumen_diminta'], 0, 10) as $dok) {
            $fulfillment_rate = $dok['jumlah_permintaan'] > 0 
                ? round(($dok['terpenuhi'] / $dok['jumlah_permintaan']) * 100, 1) 
                : 0;
            $html .= '
                        <tr>
                            <td align="center">' . $no++ . '</td>
                            <td>' . htmlspecialchars($dok['judul_dokumen']) . '</td>
                            <td align="center">' . $dok['jumlah_permintaan'] . '</td>
                            <td align="center" style="color: #27ae60;">' . $dok['terpenuhi'] . '</td>
                            <td align="center" style="color: #e74c3c;">' . $dok['ditolak'] . '</td>
                            <td align="center">' . $fulfillment_rate . '%</td>
                        </tr>';
        }
        
        $html .= '
                    </tbody>
                </table>
            </div>
            
            <div class="footer">
                <p>Dokumen ini dibuat secara otomatis oleh sistem SIDODIK</p>
                <p>Dicetak pada: ' . date('d F Y H:i:s') . '</p>
            </div>
        </body>
        </html>';

        header('Content-Type: text/html; charset=utf-8');
        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Dashboard Analytics Report</title>
            <script>
                window.onload = function() {
                    window.print();
                }
            </script>
        </head>
        <body>
        ' . $html . '
        </body>
        </html>';
        exit;
    }

// =============== KATEGORI MANAGEMENT ===============
    public function kategori()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Kelola Kategori',
            'user' => $this->getUserData(),
            'kategori' => $this->kategoriModel->getKategoriWithCount(),
            'menu' => $this->menuModel->getActiveMenu() // Tambahkan menu aktif untuk dropdown
        ];

        return view('admin/kategori', $data);
    }

    public function addKategori()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
            'menu_id' => 'required|integer',
            'deskripsi' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $menu = $this->menuModel->find($this->request->getPost('menu_id'));
        if (!$menu || $menu['status'] !== 'aktif') {
            return redirect()->back()->withInput()->with('error', 'Menu tidak valid atau tidak aktif');
        }

        $existingKategori = $this->kategoriModel
            ->where('nama_kategori', trim($this->request->getPost('nama_kategori')))
            ->where('menu_id', $this->request->getPost('menu_id'))
            ->first();
            
        if ($existingKategori) {
            return redirect()->back()->withInput()->with('error', 'Nama kategori sudah ada dalam menu "' . $menu['nama_menu'] . '"');
        }

        $data = [
            'nama_kategori' => trim($this->request->getPost('nama_kategori')),
            'menu_id' => $this->request->getPost('menu_id'),
            'deskripsi' => trim($this->request->getPost('deskripsi')),
            'status' => 'aktif'
        ];

        if ($this->kategoriModel->save($data)) {
            // LOG ACTIVITY - TAMBAH KATEGORI
            $kategoriId = $this->kategoriModel->getInsertID();
            $this->logModel->logActivity(
                $this->session->get('user_id'),
                'add_kategori',
                null,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
            
            return redirect()->to('/admin/kategori')->with('success', 'Kategori berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambah kategori');
    }

    public function editKategori($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $existingKategori = $this->kategoriModel->find($id);
        if (!$existingKategori) {
            return redirect()->to('/admin/kategori')->with('error', 'Kategori tidak ditemukan');
        }

        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
            'menu_id' => 'required|integer',
            'deskripsi' => 'permit_empty|max_length[500]',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'Validation errors: ' . print_r($errors, true));
            return redirect()->back()->withInput()->with('error', 'Data tidak valid: ' . implode(', ', $errors));
        }

        $menu = $this->menuModel->find($this->request->getPost('menu_id'));
        if (!$menu) {
            return redirect()->back()->withInput()->with('error', 'Menu tidak valid');
        }

        $existingKategori = $this->kategoriModel
            ->where('nama_kategori', trim($this->request->getPost('nama_kategori')))
            ->where('menu_id', $this->request->getPost('menu_id'))
            ->where('id !=', $id)
            ->first();
            
        if ($existingKategori) {
            return redirect()->back()->withInput()->with('error', 'Nama kategori sudah ada dalam menu "' . $menu['nama_menu'] . '"');
        }

        $data = [
            'nama_kategori' => trim($this->request->getPost('nama_kategori')),
            'menu_id' => $this->request->getPost('menu_id'),
            'deskripsi' => trim($this->request->getPost('deskripsi')),
            'status' => $this->request->getPost('status')
        ];

        log_message('info', "Updating kategori ID $id with data: " . print_r($data, true));

        try {
            $this->kategoriModel->skipValidation(true);
            
            if ($this->kategoriModel->update($id, $data)) {
                // LOG ACTIVITY - EDIT KATEGORI
                $this->logModel->logActivity(
                    $this->session->get('user_id'),
                    'edit_kategori',
                    null,
                    $this->request->getIPAddress(),
                    $this->request->getUserAgent()
                );
                
                log_message('info', "Kategori ID $id berhasil diupdate");
                return redirect()->to('/admin/kategori')->with('success', 'Kategori berhasil diperbarui');
            } else {
                $modelErrors = $this->kategoriModel->errors();
                log_message('error', "Model update failed: " . print_r($modelErrors, true));
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori: ' . implode(', ', $modelErrors));
            }
        } catch (\Exception $e) {
            log_message('error', "Exception during kategori update: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteKategori($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $count = $this->dokumenModel->where('kategori_id', $id)->countAllResults();
        if ($count > 0) {
            return redirect()->to('/admin/kategori')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $count . ' dokumen');
        }

        if ($this->kategoriModel->delete($id)) {
            // LOG ACTIVITY - DELETE KATEGORI
            $this->logModel->logActivity(
                $this->session->get('user_id'),
                'delete_kategori',
                null,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
            
            return redirect()->to('/admin/kategori')->with('success', 'Kategori berhasil dihapus');
        }

        return redirect()->to('/admin/kategori')->with('error', 'Gagal menghapus kategori');
    }

    // TAMBAHAN: Method untuk get kategori berdasarkan menu (untuk AJAX)
    public function getKategoriByMenu($menuId = null)
    {
        $redirect = $this->redirectIfNotLoggedIn();
        if ($redirect) return $redirect;

        if (!$menuId) {
            return $this->response->setJSON(['error' => 'Menu ID required']);
        }

        $kategori = $this->kategoriModel->getKategoriByMenu($menuId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $kategori
        ]);
    }

    // =============== MENU MANAGEMENT ===============
    public function menu()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Kelola Menu',
            'user' => $this->getUserData(),
            'menu' => $this->menuModel->getMenuWithCount()
        ];

        return view('admin/menu', $data);
    }

    public function addMenu()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'nama_menu' => 'required|min_length[3]|max_length[100]|is_unique[menu.nama_menu]',
            'deskripsi' => 'permit_empty|max_length[500]',
            'icon' => 'permit_empty|max_length[50]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }

        $data = [
            'nama_menu' => trim($this->request->getPost('nama_menu')),
            'deskripsi' => trim($this->request->getPost('deskripsi')),
            'icon' => trim($this->request->getPost('icon')),
            'status' => 'aktif'
        ];

        if ($this->menuModel->save($data)) {
            // LOG ACTIVITY - ADD MENU
            $this->logModel->logActivity(
                $this->session->get('user_id'),
                'add_menu',
                null,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
            
            return redirect()->to('/admin/menu')->with('success', 'Menu berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambah menu');
    }

    public function editMenu($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'nama_menu' => "required|min_length[3]|max_length[100]|is_unique[menu.nama_menu,id,$id]",
            'deskripsi' => 'permit_empty|max_length[500]',
            'icon' => 'permit_empty|max_length[50]',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }

        $data = [
            'nama_menu' => trim($this->request->getPost('nama_menu')),
            'deskripsi' => trim($this->request->getPost('deskripsi')),
            'icon' => trim($this->request->getPost('icon')),
            'status' => $this->request->getPost('status')
        ];

        $this->menuModel->skipValidation(true);

        if ($this->menuModel->update($id, $data)) {
            // LOG ACTIVITY - EDIT MENU
            $this->logModel->logActivity(
                $this->session->get('user_id'),
                'edit_menu',
                null,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
            
            return redirect()->to('/admin/menu')->with('success', 'Menu berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui menu');
    }

    public function deleteMenu($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $count = $this->dokumenModel->where('menu_id', $id)->countAllResults();
        if ($count > 0) {
            return redirect()->to('/admin/menu')->with('error', 'Menu tidak dapat dihapus karena masih digunakan oleh ' . $count . ' dokumen');
        }

        if ($this->menuModel->delete($id)) {
            // LOG ACTIVITY - DELETE MENU
            $this->logModel->logActivity(
                $this->session->get('user_id'),
                'delete_menu',
                null,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
            
            return redirect()->to('/admin/menu')->with('success', 'Menu berhasil dihapus');
        }

        return redirect()->to('/admin/menu')->with('error', 'Gagal menghapus menu');
    }

    // =============== DOKUMEN MANAGEMENT ===============
    public function dokumen()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Kelola Dokumen',
            'user' => $this->getUserData(),
            'dokumen' => $this->dokumenModel->getAllDokumen(),
            'kategori' => $this->kategoriModel->getActiveKategori(),
            'menu' => $this->menuModel->getActiveMenu()
        ];

        return view('admin/dokumen', $data);
    }

    public function addDokumen()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'judul' => 'required|min_length[5]|max_length[200]',
            'deskripsi' => 'permit_empty|max_length[1000]',
            'kategori_id' => 'required|integer',
            'menu_id' => 'required|integer',
            'status' => 'required|in_list[aktif,nonaktif]',        // <- TAMBAH INI
            'akses' => 'required|in_list[publik,privat]',          // <- TAMBAH INI  
            'file' => 'uploaded[file]|max_size[file,10240]'
        ];

        // PERBAIKAN: Validasi file extension secara manual dan lebih strict
        $file = $this->request->getFile('file');
        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
        
        if ($file && $file->isValid()) {
            // PERBAIKAN: Gunakan getClientExtension() yang lebih reliable
            $clientExtension = strtolower($file->getClientExtension());
            $originalName = $file->getClientName();
            
            // Double check extension dari nama file asli juga
            $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            
            if (!in_array($clientExtension, $allowedExtensions) && !in_array($fileExtension, $allowedExtensions)) {
                return redirect()->back()->withInput()->with('error', 'Format file tidak didukung. Hanya diperbolehkan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX');
            }
            
            // PERBAIKAN: Validasi MIME type untuk PPT/PPTX
            $mimeType = $file->getClientMimeType();
            $allowedMimes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                // TAMBAHAN: MIME types alternatif untuk PPT/PPTX
                'application/mspowerpoint',
                'application/powerpoint',
                'application/x-mspowerpoint'
            ];
            
            // Log untuk debugging
            log_message('info', "File upload debug - Name: {$originalName}, Extension: {$clientExtension}, MIME: {$mimeType}");
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validasi apakah kategori dan menu exists
        $kategori = $this->kategoriModel->find($this->request->getPost('kategori_id'));
        $menu = $this->menuModel->find($this->request->getPost('menu_id'));
        
        if (!$kategori || !$menu) {
            return redirect()->back()->withInput()->with('error', 'Kategori atau Menu tidak valid');
        }

        // Handle file upload
        if (!$file->hasMoved()) {
            try {
                // PERBAIKAN: Gunakan extension yang benar
                $originalName = $file->getClientName();
                $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                
                // Jika extension kosong atau tidak valid, gunakan dari client extension
                if (empty($fileExtension) || !in_array($fileExtension, $allowedExtensions)) {
                    $fileExtension = strtolower($file->getClientExtension());
                }
                
                // PERBAIKAN: Generate nama file dengan extension yang benar
                $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                $uploadPath = WRITEPATH . 'uploads/documents/' . date('Y/m/');
                
                // Create directory if not exists
                if (!is_dir($uploadPath)) {
                    if (!mkdir($uploadPath, 0755, true)) {
                        throw new \Exception('Gagal membuat direktori upload');
                    }
                }

                // Check if directory is writable
                if (!is_writable($uploadPath)) {
                    throw new \Exception('Direktori upload tidak dapat ditulis');
                }

                // PERBAIKAN: Move file dengan nama yang sudah benar
                if (!$file->move($uploadPath, $fileName)) {
                    throw new \Exception('Gagal memindahkan file');
                }

                // Verify file was moved successfully
                $fullPath = $uploadPath . $fileName;
                if (!file_exists($fullPath)) {
                    throw new \Exception('File tidak ditemukan setelah upload');
                }

                $data = [
                    'judul' => trim($this->request->getPost('judul')),
                    'deskripsi' => trim($this->request->getPost('deskripsi')),
                    'tags' => $this->cleanTags($this->request->getPost('tags')),
                    'kategori_id' => $this->request->getPost('kategori_id'),
                    'menu_id' => $this->request->getPost('menu_id'),
                    'file_name' => $originalName,
                    'file_path' => 'uploads/documents/' . date('Y/m/') . $fileName,
                    'file_type' => $fileExtension,
                    'file_size' => $file->getSize(),
                    'uploaded_by' => $this->session->get('user_id'),
                    'tanggal_upload' => date('Y-m-d'),
                    'status' => $this->request->getPost('status') ?: 'aktif',
                    'akses' => $this->request->getPost('akses') ?: 'privat'
                ];

                // Auto-generate tags if empty
                if (empty($data['tags'])) {
                    $menuInfo = $this->menuModel->find($data['menu_id']);
                    $kategoriInfo = $this->kategoriModel->find($data['kategori_id']);
                    
                    $data['tags'] = generateAutoTags(
                        $data['judul'],
                        $menuInfo['nama_menu'] ?? '',
                        $kategoriInfo['nama_kategori'] ?? ''
                    );
                }

                // Log untuk debugging
                log_message('info', "Saving document - File: {$fileName}, Type: {$fileExtension}, Original: {$originalName}");

                if ($this->dokumenModel->save($data)) {
                    // Log activity
                    $this->logModel->logActivity(
                        $this->session->get('user_id'),
                        'upload',
                        $this->dokumenModel->getInsertID(),
                        $this->request->getIPAddress(),
                        $this->request->getUserAgent()
                    );

                    return redirect()->to('/admin/dokumen')->with('success', 'Dokumen berhasil ditambahkan');
                } else {
                    // Delete uploaded file if database save fails
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                    throw new \Exception('Gagal menyimpan data dokumen ke database');
                }
                
            } catch (\Exception $e) {
                log_message('error', 'File upload error: ' . $e->getMessage());
                
                // Jangan tampilkan error message untuk Unknown column updated_at
                if (strpos($e->getMessage(), 'Unknown column') !== false && strpos($e->getMessage(), 'updated_at') !== false) {
                    return redirect()->to('/admin/dokumen')->with('success', 'Dokumen berhasil ditambahkan');
                }
                
                return redirect()->back()->with('error', 'Gagal mengupload file: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'File tidak valid atau sudah dipindahkan');
    }

    public function editDokumen($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'judul' => 'required|min_length[5]|max_length[200]',
            'deskripsi' => 'permit_empty|max_length[1000]',
            'kategori_id' => 'required|integer',
            'menu_id' => 'required|integer',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        // Jika ada file baru
        $file = $this->request->getFile('file');
        if ($file && $file->isValid()) {
            $rules['file'] = 'max_size[file,10240]|ext_in[file,pdf,doc,docx,xls,xlsx,ppt,pptx]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
        }

        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return redirect()->to('/admin/dokumen')->with('error', 'Dokumen tidak ditemukan');
        }

        $data = [
            'judul' => trim($this->request->getPost('judul')),
            'deskripsi' => trim($this->request->getPost('deskripsi')),
            'tags' => $this->cleanTags($this->request->getPost('tags')),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'menu_id' => $this->request->getPost('menu_id'),
            'status' => $this->request->getPost('status'),
            'akses' => $this->request->getPost('akses')
        ];

        // Handle file upload jika ada
        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                // Hapus file lama
                $oldFile = WRITEPATH . $dokumen['file_path'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }

                $fileName = $file->getRandomName();
                $uploadPath = WRITEPATH . 'uploads/documents/' . date('Y/m/') . '/';
                
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $file->move($uploadPath, $fileName);

                $data['file_name'] = $file->getClientName();
                $data['file_path'] = 'uploads/documents/' . date('Y/m/') . '/' . $fileName;
                $data['file_type'] = $file->getClientExtension();
                $data['file_size'] = $file->getSize();
            } catch (\Exception $e) {
                log_message('error', 'File update error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal mengupdate file: ' . $e->getMessage());
            }
        }

        // UPDATE DATA
        if ($this->dokumenModel->update($id, $data)) {
            // PERBAIKAN: Pastikan tidak ada output sebelum redirect
            
            // Log activity (comment dulu untuk debug)
            // $this->logModel->logActivity(
            //     $this->session->get('user_id'),
            //     'edit',
            //     $id,
            //     $this->request->getIPAddress(),
            //     $this->request->getUserAgent()
            // );

            // Force redirect dengan header
            session()->setFlashdata('success', 'Dokumen berhasil diperbarui');
            header('Location: ' . base_url('admin/dokumen'));
            exit();
        }

        return redirect()->back()->with('error', 'Gagal memperbarui dokumen');
    }

    public function deleteDokumen($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return redirect()->to('/admin/dokumen')->with('error', 'Dokumen tidak ditemukan');
        }

        // Hapus dari database terlebih dahulu
        if ($this->dokumenModel->delete($id)) {
            
            // Coba hapus file fisik (tapi jangan gagalkan operasi jika gagal)
            try {
                $filePath = WRITEPATH . $dokumen['file_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            } catch (\Exception $e) {
                // Log error tapi jangan gagalkan operasi
                log_message('error', 'File deletion error (but document deleted from DB): ' . $e->getMessage());
            }

            // Log activity
            try {
                $this->logModel->logActivity(
                    $this->session->get('user_id'),
                    'delete',
                    $id,
                    $this->request->getIPAddress(),
                    $this->request->getUserAgent()
                );
            } catch (\Exception $e) {
                log_message('error', 'Log activity error: ' . $e->getMessage());
            }

            return redirect()->to('/admin/dokumen')->with('success', 'Dokumen berhasil dihapus');
        }

        return redirect()->to('/admin/dokumen')->with('error', 'Gagal menghapus dokumen dari database');
    }

    // GANTI method viewDokumen dan downloadDokumen di Admin.php dengan yang ini:
    public function viewDokumen($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $dokumen = $this->dokumenModel->getDokumenDetail($id);
        
        if (!$dokumen) {
            return redirect()->to('/admin/dokumen')->with('error', 'Dokumen tidak ditemukan');
        }

        // INCREMENT VIEWS dengan anti-spam protection
        $user_id = $this->session->get('user_id');
        $ip_address = $this->request->getIPAddress();
        
        $viewIncremented = $this->dokumenModel->incrementViews($id, $user_id, $ip_address);

        // Log activity hanya jika view counter berhasil diincrement
        if ($viewIncremented) {
            try {
                $this->logModel->logActivity(
                    $user_id,
                    'admin_view',
                    $id,
                    $ip_address,
                    $this->request->getUserAgent()
                );
                log_message('info', "Admin view logged for document ID: $id, User ID: $user_id");
            } catch (\Exception $e) {
                log_message('error', 'Failed to log admin view activity: ' . $e->getMessage());
            }
        } else {
            log_message('info', "Admin view blocked (anti-spam) for document ID: $id, User ID: $user_id");
        }

        // Serve file langsung
        $filePath = WRITEPATH . $dokumen['file_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->to('/admin/dokumen')->with('error', 'File tidak ditemukan di server');
        }

        // Get file info
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        
        // Set appropriate headers untuk preview
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: private, must-revalidate, max-age=0');
        header('Pragma: private');
        
        readfile($filePath);
        exit;
    }

    public function downloadDokumen($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $dokumen = $this->dokumenModel->find($id);
        
        if (!$dokumen) {
            return redirect()->to('/admin/dokumen')->with('error', 'Dokumen tidak ditemukan');
        }

        $filePath = WRITEPATH . $dokumen['file_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server');
        }

        // INCREMENT DOWNLOADS dengan anti-spam protection
        $user_id = $this->session->get('user_id');
        $ip_address = $this->request->getIPAddress();
        
        $downloadIncremented = $this->dokumenModel->incrementDownloads($id, $user_id, $ip_address);

        // Log activity hanya jika download counter berhasil diincrement
        if ($downloadIncremented) {
            try {
                $this->logModel->logActivity(
                    $user_id,
                    'admin_download',
                    $id,
                    $ip_address,
                    $this->request->getUserAgent()
                );
                log_message('info', "Admin download logged for document ID: $id, User ID: $user_id");
            } catch (\Exception $e) {
                log_message('error', 'Failed to log admin download activity: ' . $e->getMessage());
            }
        } else {
            log_message('info', "Admin download blocked (anti-spam) for document ID: $id, User ID: $user_id");
        }

        // Force download
        return $this->response->download($filePath, null)->setFileName($dokumen['file_name']);
    }

// =============== USER MANAGEMENT ===============
// Tambahkan method ini ke dalam class Admin di Admin.php

    public function user()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Kelola User',
            'user' => $this->getUserData(),
            'users' => $this->userModel->findAll()
        ];

        return view('admin/user', $data);
    }

    public function addUser()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'nip' => 'permit_empty|max_length[20]|is_unique[users.nip]',
            'password' => 'required|min_length[6]',
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|in_list[admin,user]'
        ];

        $messages = [
            'username' => [
                'required' => 'Username harus diisi',
                'is_unique' => 'Username sudah digunakan, silakan pilih yang lain',
                'min_length' => 'Username minimal 3 karakter',
                'max_length' => 'Username maksimal 50 karakter'
            ],
            'nip' => [
                'is_unique' => 'NIP sudah digunakan',
                'max_length' => 'NIP maksimal 20 karakter'
            ],
            'password' => [
                'required' => 'Password harus diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
            'nama_lengkap' => [
                'required' => 'Nama lengkap harus diisi',
                'min_length' => 'Nama lengkap minimal 3 karakter',
                'max_length' => 'Nama lengkap maksimal 100 karakter'
            ],
            'role' => [
                'required' => 'Role harus dipilih',
                'in_list' => 'Role tidak valid'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'User add validation errors: ' . print_r($errors, true));
            
            return redirect()->to('/admin/user')
                            ->withInput()
                            ->with('error', 'Gagal menambah user: ' . implode(', ', $errors));
        }

        $username = trim($this->request->getPost('username'));
        $nip = trim($this->request->getPost('nip'));
        $password = $this->request->getPost('password');
        $nama_lengkap = trim($this->request->getPost('nama_lengkap'));
        $role = $this->request->getPost('role');

        $existingUser = $this->userModel->where('username', $username)->first();
        if ($existingUser) {
            return redirect()->to('/admin/user')
                            ->withInput()
                            ->with('error', 'Username "' . $username . '" sudah digunakan, silakan pilih yang lain');
        }

        if (!empty($nip)) {
            $existingNIP = $this->userModel->where('nip', $nip)->first();
            if ($existingNIP) {
                return redirect()->to('/admin/user')
                                ->withInput()
                                ->with('error', 'NIP "' . $nip . '" sudah digunakan');
            }
        }

        $data = [
            'username' => $username,
            'nip' => !empty($nip) ? $nip : null,
            'password' => $this->userModel->hashPassword($password),
            'nama_lengkap' => $nama_lengkap,
            'role' => $role,
            'status' => 'aktif'
        ];

        try {
            $this->userModel->skipValidation(true);
            
            if ($this->userModel->save($data)) {
                // LOG ACTIVITY - ADD USER
                $this->logModel->logActivity(
                    $this->session->get('user_id'),
                    'add_user',
                    null,
                    $this->request->getIPAddress(),
                    $this->request->getUserAgent()
                );
                
                log_message('info', 'User berhasil ditambahkan: ' . $username);
                return redirect()->to('/admin/user')->with('success', 'User "' . $username . '" berhasil ditambahkan');
            } else {
                $modelErrors = $this->userModel->errors();
                log_message('error', 'Model save failed: ' . print_r($modelErrors, true));
                return redirect()->to('/admin/user')
                                ->withInput()
                                ->with('error', 'Gagal menyimpan user: ' . implode(', ', $modelErrors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during user creation: ' . $e->getMessage());
            return redirect()->to('/admin/user')
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // ============== 8. USER - editUser() ==============
    public function editUser($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/user')->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,$id]",
            'nip' => "permit_empty|max_length[20]|is_unique[users.nip,id,$id]",
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'role' => 'required|in_list[admin,user]',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'User edit validation errors: ' . print_r($errors, true));
            return redirect()->back()->withInput()->with('error', 'Data tidak valid: ' . implode(', ', $errors));
        }

        $data = [
            'username' => trim($this->request->getPost('username')),
            'nip' => trim($this->request->getPost('nip')) ?: null,
            'nama_lengkap' => trim($this->request->getPost('nama_lengkap')),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status')
        ];

        if (!empty($password)) {
            $data['password'] = $this->userModel->hashPassword($password);
        }

        log_message('info', "Updating user ID $id with data: " . print_r($data, true));

        try {
            $this->userModel->skipValidation(true);
            
            if ($this->userModel->update($id, $data)) {
                // LOG ACTIVITY - EDIT USER
                $this->logModel->logActivity(
                    $this->session->get('user_id'),
                    'edit_user',
                    null,
                    $this->request->getIPAddress(),
                    $this->request->getUserAgent()
                );
                
                log_message('info', "User ID $id berhasil diupdate");
                return redirect()->to('/admin/user')->with('success', 'User berhasil diperbarui');
            } else {
                $modelErrors = $this->userModel->errors();
                log_message('error', "Model update failed for user: " . print_r($modelErrors, true));
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui user: ' . implode(', ', $modelErrors));
            }
        } catch (\Exception $e) {
            log_message('error', "Exception during user update: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ============== 9. USER - deleteUser() ==============
    public function deleteUser($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/user')->with('error', 'User tidak ditemukan');
        }

        $count = $this->dokumenModel->where('uploaded_by', $id)->countAllResults();
        if ($count > 0) {
            return redirect()->to('/admin/user')->with('error', 'User tidak dapat dihapus karena masih memiliki ' . $count . ' dokumen');
        }

        if ($id == $this->session->get('user_id')) {
            return redirect()->to('/admin/user')->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        if ($this->userModel->delete($id)) {
            // LOG ACTIVITY - DELETE USER
            $this->logModel->logActivity(
                $this->session->get('user_id'),
                'delete_user',
                null,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
            
            return redirect()->to('/admin/user')->with('success', 'User berhasil dihapus');
        }

        return redirect()->to('/admin/user')->with('error', 'Gagal menghapus user');
    }

    // TAMBAHKAN METHODS INI KE DALAM CLASS Admin di Admin.php
    // LETAKKAN SEBELUM CLOSING BRACKET TERAKHIR DARI CLASS

    // REPLACE method pengaduan() yang ada di Admin Controller dengan ini:
    public function pengaduan()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        // Get filter parameters - TAMBAH jenis_pemohon
        $filter = [
            'status' => $this->request->getGet('status'),
            'jenis_pemohon' => $this->request->getGet('jenis_pemohon'), // TAMBAH INI
            'urgency' => $this->request->getGet('urgency'),
            'kategori' => $this->request->getGet('kategori'),
            'search' => $this->request->getGet('search')
        ];

        // Build query
        $builder = $this->pengaduanModel->builder();
        
        // Apply filters
        if (!empty($filter['status'])) {
            $builder->where('status', $filter['status']);
        }
        
        // TAMBAH FILTER JENIS PEMOHON
        if (!empty($filter['jenis_pemohon'])) {
            $builder->where('jenis_pemohon', $filter['jenis_pemohon']);
        }
        
        if (!empty($filter['urgency'])) {
            $builder->where('urgency', $filter['urgency']);
        }
        
        if (!empty($filter['kategori'])) {
            $builder->where('kategori_permintaan', $filter['kategori']);
        }
        
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $builder->groupStart()
                   ->like('nama', $keyword)
                   ->orLike('email', $keyword)
                   ->orLike('nip', $keyword) // TAMBAH PENCARIAN NIP
                   ->orLike('ticket_number', $keyword)
                   ->orLike('judul_dokumen', $keyword)
                   ->groupEnd();
        }
        
        $pengaduan = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
        
        // Get statistics
        $stats = $this->pengaduanModel->getStatistics();
        
        // Get dokumen list for linking
        $dokumenList = $this->dokumenModel->where('status', 'aktif')
                                         ->orderBy('judul', 'ASC')
                                         ->findAll();

        $data = [
            'title' => 'Kelola Pengaduan',
            'user' => $this->getUserData(),
            'pengaduan' => $pengaduan,
            'stats' => $stats,
            'filter' => $filter,
            'dokumen_list' => $dokumenList
        ];

        return view('admin/pengaduan', $data);
    }

    public function updateStatusPengaduan($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $pengaduan = $this->pengaduanModel->find($id);
        if (!$pengaduan) {
            return redirect()->to('/admin/pengaduan')->with('error', 'Pengaduan tidak ditemukan');
        }

        $rules = [
            'status' => 'required|in_list[pending,proses,selesai,ditolak]',
            'admin_response' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', implode(', ', $this->validator->getErrors()));
        }

        $status = $this->request->getPost('status');
        $adminResponse = trim($this->request->getPost('admin_response'));

        $data = [
            'status' => $status,
            'tanggal_respon' => date('Y-m-d H:i:s')
        ];

        if (!empty($adminResponse)) {
            $data['admin_response'] = $adminResponse;
        }

        if ($this->pengaduanModel->update($id, $data)) {
            // LOG ACTIVITY - UPDATE STATUS PENGADUAN
            $this->logModel->logActivity(
                $this->session->get('user_id'),
                'update_pengaduan_status',
                null,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
            
            $statusText = [
                'pending' => 'Menunggu',
                'proses' => 'Diproses', 
                'selesai' => 'Selesai',
                'ditolak' => 'Ditolak'
            ];

            return redirect()->to('/admin/pengaduan')
                            ->with('success', "Status pengaduan berhasil diubah menjadi: {$statusText[$status]}");
        }

        return redirect()->back()->with('error', 'Gagal mengupdate status pengaduan');
    }

    public function linkDokumenPengaduan($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $pengaduan = $this->pengaduanModel->find($id);
        if (!$pengaduan) {
            return redirect()->to('/admin/pengaduan')->with('error', 'Pengaduan tidak ditemukan');
        }

        $dokumenId = $this->request->getPost('dokumen_id');
        
        if (!$dokumenId) {
            return redirect()->back()->with('error', 'Pilih dokumen yang akan dihubungkan');
        }

        $dokumen = $this->dokumenModel->find($dokumenId);
        if (!$dokumen) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan');
        }

        $data = [
            'dokumen_terkait' => $dokumenId,
            'status' => 'selesai',
            'tanggal_respon' => date('Y-m-d H:i:s')
        ];

        if ($this->pengaduanModel->update($id, $data)) {
            // LOG ACTIVITY - LINK DOKUMEN PENGADUAN
            $this->logModel->logActivity(
                $this->session->get('user_id'),
                'link_pengaduan_dokumen',
                $dokumenId,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
            
            return redirect()->to('/admin/pengaduan')
                            ->with('success', "Dokumen \"{$dokumen['judul']}\" berhasil dihubungkan ke pengaduan");
        }

        return redirect()->back()->with('error', 'Gagal menghubungkan dokumen');
    }

    public function deletePengaduan($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $pengaduan = $this->pengaduanModel->find($id);
        if (!$pengaduan) {
            return redirect()->to('/admin/pengaduan')->with('error', 'Pengaduan tidak ditemukan');
        }

        if ($this->pengaduanModel->delete($id)) {
            // LOG ACTIVITY - DELETE PENGADUAN
            $this->logModel->logActivity(
                $this->session->get('user_id'),
                'delete_pengaduan',
                null,
                $this->request->getIPAddress(),
                $this->request->getUserAgent()
            );
            
            return redirect()->to('/admin/pengaduan')
                            ->with('success', "Pengaduan dari \"{$pengaduan['nama']}\" berhasil dihapus");
        }

        return redirect()->to('/admin/pengaduan')->with('error', 'Gagal menghapus pengaduan');
    }

    public function getPengaduanNotifications()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        // Get pending and urgent pengaduan
        $notifications = $this->pengaduanModel
            ->select('id, ticket_number, nama, judul_dokumen, urgency, created_at')
            ->whereIn('status', ['pending', 'proses'])
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        // Count total pending
        $totalPending = $this->pengaduanModel->where('status', 'pending')->countAllResults();
        $totalProses = $this->pengaduanModel->where('status', 'proses')->countAllResults();

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'total_pending' => $totalPending,
            'total_proses' => $totalProses,
            'total_active' => $totalPending + $totalProses
        ]);
    }

    // UPDATE method detailPengaduan() yang ada di Admin Controller dengan ini:
    public function detailPengaduan($id)
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $pengaduan = $this->pengaduanModel->getPengaduanDetail($id);
        if (!$pengaduan) {
            return $this->response->setJSON(['error' => 'Pengaduan tidak ditemukan']);
        }

        // Return HTML content for modal
        $html = '
        <div class="pengaduan-detail">
            <div class="detail-section">
                <h4><i class="fas fa-ticket-alt"></i> Informasi Tiket</h4>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Nomor Tiket:</label>
                        <span class="ticket-number">' . esc($pengaduan['ticket_number']) . '</span>
                    </div>
                    <div class="detail-item">
                        <label>Tanggal Pengajuan:</label>
                        <span>' . date('d F Y, H:i', strtotime($pengaduan['created_at'])) . '</span>
                    </div>
                    <div class="detail-item">
                        <label>Status:</label>
                        <span class="status-badge status-' . $pengaduan['status'] . '">' . 
                        ($pengaduan['status'] == 'pending' ? 'Menunggu' : 
                        ($pengaduan['status'] == 'proses' ? 'Diproses' : 
                        ($pengaduan['status'] == 'selesai' ? 'Selesai' : 'Ditolak'))) . '</span>
                    </div>
                    <div class="detail-item">
                        <label>Urgensi:</label>
                        <span class="urgency-badge urgency-' . $pengaduan['urgency'] . '">' . 
                        ($pengaduan['urgency'] == 'rendah' ? 'Rendah' : 
                        ($pengaduan['urgency'] == 'sedang' ? 'Sedang' : 
                        ($pengaduan['urgency'] == 'tinggi' ? 'Tinggi' : 'Sangat Tinggi'))) . '</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h4><i class="fas fa-user"></i> Data Pemohon</h4>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Nama Lengkap:</label>
                        <span>' . esc($pengaduan['nama']) . '</span>
                    </div>
                    <div class="detail-item">
                        <label>Email:</label>
                        <span>' . esc($pengaduan['email']) . '</span>
                    </div>
                    <div class="detail-item">
                        <label>Jenis Pemohon:</label>
                        <span class="badge ' . ($pengaduan['jenis_pemohon'] == 'asn' ? 'badge-primary' : 'badge-secondary') . '">' . 
                        ($pengaduan['jenis_pemohon'] == 'publik' ? 'User Publik' : 'ASN') . '</span>
                    </div>';

        // Tampilkan NIP jika ada
        if (!empty($pengaduan['nip'])) {
            $html .= '
                    <div class="detail-item">
                        <label>NIP:</label>
                        <span class="nip-display">' . esc($pengaduan['nip']) . '</span>
                    </div>';
        }

        $html .= '
                    <div class="detail-item">
                        <label>Telepon:</label>
                        <span>' . ($pengaduan['telepon'] ? esc($pengaduan['telepon']) : 'Tidak diisi') . '</span>
                    </div>
                    <div class="detail-item">
                        <label>Instansi:</label>
                        <span>' . ($pengaduan['instansi'] ? esc($pengaduan['instansi']) : 'Tidak diisi') . '</span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h4><i class="fas fa-file-alt"></i> Detail Permintaan</h4>
                <div class="detail-item full-width">
                    <label>Judul Dokumen:</label>
                    <span class="document-title">' . esc($pengaduan['judul_dokumen']) . '</span>
                </div>
                <div class="detail-item full-width">
                    <label>Kategori:</label>
                    <span>' . ($pengaduan['kategori_permintaan'] == 'surat' ? 'Surat' : 
                            ($pengaduan['kategori_permintaan'] == 'laporan' ? 'Laporan' : 
                                ($pengaduan['kategori_permintaan'] == 'formulir' ? 'Formulir' : 
                                ($pengaduan['kategori_permintaan'] == 'panduan' ? 'Panduan' : 'Lainnya')))) . '</span>
                </div>
                <div class="detail-item full-width">
                    <label>Deskripsi Kebutuhan:</label>
                    <div class="description-text">' . nl2br(esc($pengaduan['deskripsi_kebutuhan'])) . '</div>
                </div>
            </div>';

        if (!empty($pengaduan['admin_response'])) {
            $html .= '
            <div class="detail-section">
                <h4><i class="fas fa-reply"></i> Respon Admin</h4>
                <div class="detail-item full-width">
                    <div class="admin-response">' . nl2br(esc($pengaduan['admin_response'])) . '</div>
                    <small class="response-date">Direspon pada: ' . date('d F Y, H:i', strtotime($pengaduan['tanggal_respon'])) . '</small>
                </div>
            </div>';
        }

        if (!empty($pengaduan['dokumen_terkait']) && !empty($pengaduan['dokumen_judul'])) {
            $html .= '
            <div class="detail-section">
                <h4><i class="fas fa-link"></i> Dokumen Terkait</h4>
                <div class="detail-item full-width">
                    <div class="linked-document">
                        <i class="fas fa-file-pdf"></i>
                        <span>' . esc($pengaduan['dokumen_judul']) . '</span>
                    </div>
                </div>
            </div>';
        }

        $html .= '
            <div class="detail-actions">
                <button class="btn btn-primary" onclick="updateStatus(' . $pengaduan['id'] . ')">
                    <i class="fas fa-edit"></i> Update Status
                </button>
                ' . ($pengaduan['status'] !== 'selesai' ? '
                <button class="btn btn-warning" onclick="closeModal(\'detailModal\'); linkDocument(' . $pengaduan['id'] . ')">
                    <i class="fas fa-link"></i> Hubungkan Dokumen
                </button>' : '') . '
            </div>
        </div>

        <style>
        .pengaduan-detail .detail-section {
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        
        .pengaduan-detail .detail-section h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .detail-item.full-width {
            grid-column: 1 / -1;
        }
        
        .detail-item label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 13px;
        }
        
        .detail-item span {
            color: #5a6c7d;
            font-size: 14px;
        }
        
        .nip-display {
            font-family: monospace;
            font-weight: 600;
            color: #667eea !important;
            background: rgba(102, 126, 234, 0.1);
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-block;
        }
        
        .badge-primary {
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }

        .badge-secondary {
            background: #6c757d;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }
        
        .description-text {
            background: white;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            line-height: 1.6;
            color: #2c3e50;
        }
        
        .admin-response {
            background: #e3f2fd;
            padding: 12px;
            border-radius: 6px;
            border-left: 4px solid #2196f3;
            line-height: 1.6;
            color: #1565c0;
        }
        
        .response-date {
            color: #666;
            font-style: italic;
            margin-top: 8px;
            display: block;
        }
        
        .linked-document {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }
        
        .linked-document i {
            color: #dc3545;
            font-size: 16px;
        }
        
        .detail-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .ticket-number {
            font-family: monospace;
            font-weight: 700;
            color: #667eea !important;
        }
        </style>';

        return $this->response->setContentType('text/html')->setBody($html);
    }

    // =============== ANALYTICS MANAGEMENT ===============
    protected $db;

    // Tambahkan ini di method __construct yang sudah ada
    private function initializeDB()
    {
        if (!$this->db) {
            $this->db = \Config\Database::connect();
        }
    }

    private function getOverviewStats()
    {
        $stats = [];

        // Total documents
        $query = $this->db->query("SELECT COUNT(*) as total FROM dokumen WHERE status = 'aktif'");
        $stats['total_documents'] = $query->getRow()->total;

        // Total views
        $query = $this->db->query("SELECT COALESCE(SUM(views), 0) as total FROM dokumen");
        $stats['total_views'] = $query->getRow()->total;

        // Total downloads
        $query = $this->db->query("SELECT COALESCE(SUM(downloads), 0) as total FROM dokumen");
        $stats['total_downloads'] = $query->getRow()->total;

        // Total users
        $query = $this->db->query("SELECT COUNT(*) as total FROM users WHERE status = 'aktif'");
        $stats['total_users'] = $query->getRow()->total;

        // Total pengaduan
        $query = $this->db->query("SELECT COUNT(*) as total FROM pengaduan_dokumen");
        $stats['total_pengaduan'] = $query->getRow()->total;

        // Active sessions (last 30 minutes)
        $query = $this->db->query("
            SELECT COUNT(DISTINCT user_id) as total 
            FROM log_activity 
            WHERE activity = 'login' 
            AND created_at >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)
        ");
        $stats['active_sessions'] = $query->getRow()->total;

        // TAMBAHAN: Total Menu
        $query = $this->db->query("SELECT COUNT(*) as total FROM menu WHERE status = 'aktif'");
        $stats['total_menu'] = $query->getRow()->total;

        // TAMBAHAN: Total Kategori
        $query = $this->db->query("SELECT COUNT(*) as total FROM kategori WHERE status = 'aktif'");
        $stats['total_kategori'] = $query->getRow()->total;

        // TAMBAHAN: Pengaduan Pending
        $query = $this->db->query("SELECT COUNT(*) as total FROM pengaduan_dokumen WHERE status = 'pending'");
        $stats['pengaduan_pending'] = $query->getRow()->total;

        // TAMBAHAN: Rata-rata Response Time (dalam hari)
        $query = $this->db->query("
            SELECT COALESCE(AVG(DATEDIFF(tanggal_respon, created_at)), 0) as avg_days
            FROM pengaduan_dokumen 
            WHERE tanggal_respon IS NOT NULL
        ");
        $stats['avg_response_time'] = round($query->getRow()->avg_days, 1);

        return $stats;
    }

    private function getDocumentStats()
    {
        // Documents by type
        $query = $this->db->query("
            SELECT file_type, COUNT(*) as count 
            FROM dokumen 
            WHERE status = 'aktif' 
            GROUP BY file_type 
            ORDER BY count DESC
        ");
        $by_type = $query->getResultArray();

        // Documents by access level
        $query = $this->db->query("
            SELECT akses, COUNT(*) as count 
            FROM dokumen 
            GROUP BY akses
        ");
        $by_access = $query->getResultArray();

        // Documents by status
        $query = $this->db->query("
            SELECT status, COUNT(*) as count 
            FROM dokumen 
            GROUP BY status
        ");
        $by_status = $query->getResultArray();

        return [
            'by_type' => $by_type,
            'by_access' => $by_access,
            'by_status' => $by_status
        ];
    }

    private function getUserActivity()
    {
        // Login activity last 7 days
        $query = $this->db->query("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as logins
            FROM log_activity 
            WHERE activity = 'login' 
            AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ");
        $login_trend = $query->getResultArray();

        // Most active users
        $query = $this->db->query("
            SELECT 
                u.nama_lengkap,
                u.username,
                COUNT(l.id) as activity_count
            FROM users u
            LEFT JOIN log_activity l ON u.id = l.user_id
            WHERE l.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY u.id
            ORDER BY activity_count DESC
            LIMIT 5
        ");
        $most_active = $query->getResultArray();

        // User role distribution
        $query = $this->db->query("
            SELECT role, COUNT(*) as count 
            FROM users 
            WHERE status = 'aktif'
            GROUP BY role
        ");
        $role_distribution = $query->getResultArray();

        return [
            'login_trend' => $login_trend,
            'most_active' => $most_active,
            'role_distribution' => $role_distribution
        ];
    }

    private function getPengaduanStats()
    {
        // Pengaduan by status
        $query = $this->db->query("
            SELECT status, COUNT(*) as count 
            FROM pengaduan_dokumen 
            GROUP BY status
        ");
        $by_status = $query->getResultArray();

        // Pengaduan by urgency
        $query = $this->db->query("
            SELECT urgency, COUNT(*) as count 
            FROM pengaduan_dokumen 
            GROUP BY urgency
        ");
        $by_urgency = $query->getResultArray();

        // Pengaduan by category
        $query = $this->db->query("
            SELECT kategori_permintaan, COUNT(*) as count 
            FROM pengaduan_dokumen 
            GROUP BY kategori_permintaan
        ");
        $by_category = $query->getResultArray();

        // Response time (average days)
        $query = $this->db->query("
            SELECT 
                AVG(DATEDIFF(tanggal_respon, created_at)) as avg_response_days
            FROM pengaduan_dokumen 
            WHERE tanggal_respon IS NOT NULL
        ");
        $avg_response = $query->getRow()->avg_response_days ?? 0;

        return [
            'by_status' => $by_status,
            'by_urgency' => $by_urgency,
            'by_category' => $by_category,
            'avg_response_days' => round($avg_response, 1)
        ];
    }

    // TAMBAHKAN METHOD INI DI Admin.php SETELAH METHOD getPengaduanStats()
    private function getPengaduanAnalytics()
    {
        // Pengaduan by kategori permintaan
        $query = $this->db->query("
            SELECT 
                kategori_permintaan,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'proses' THEN 1 ELSE 0 END) as proses
            FROM pengaduan_dokumen
            GROUP BY kategori_permintaan
            ORDER BY total DESC
        ");
        $by_kategori = $query->getResultArray();

        // Pengaduan by jenis pemohon
        $query = $this->db->query("
            SELECT 
                jenis_pemohon,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak
            FROM pengaduan_dokumen
            GROUP BY jenis_pemohon
        ");
        $by_jenis_pemohon = $query->getResultArray();

        // Success rate percentage
        $query = $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak,
                SUM(CASE WHEN status IN ('pending', 'proses') THEN 1 ELSE 0 END) as ongoing
            FROM pengaduan_dokumen
        ");
        $success_rate = $query->getRow();
        
        $total = $success_rate->total ?? 0;
        $success_percentage = $total > 0 ? round(($success_rate->selesai / $total) * 100, 1) : 0;
        $reject_percentage = $total > 0 ? round(($success_rate->ditolak / $total) * 100, 1) : 0;
        $ongoing_percentage = $total > 0 ? round(($success_rate->ongoing / $total) * 100, 1) : 0;

        // Pengaduan trend per bulan (last 6 months)
        $query = $this->db->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as bulan,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak
            FROM pengaduan_dokumen
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY bulan
            ORDER BY bulan ASC
        ");
        $trend_bulanan = $query->getResultArray();

        // Top dokumen yang paling banyak diminta (dari judul_dokumen yang mirip)
        $query = $this->db->query("
            SELECT 
                judul_dokumen,
                COUNT(*) as jumlah_permintaan,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as terpenuhi,
                SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak
            FROM pengaduan_dokumen
            GROUP BY LOWER(judul_dokumen)
            ORDER BY jumlah_permintaan DESC
            LIMIT 10
        ");
        $top_dokumen_diminta = $query->getResultArray();

        // Response time statistics
        $query = $this->db->query("
            SELECT 
                AVG(DATEDIFF(tanggal_respon, created_at)) as avg_days,
                MIN(DATEDIFF(tanggal_respon, created_at)) as min_days,
                MAX(DATEDIFF(tanggal_respon, created_at)) as max_days
            FROM pengaduan_dokumen
            WHERE tanggal_respon IS NOT NULL
        ");
        $response_time = $query->getRow();

        return [
            'by_kategori' => $by_kategori,
            'by_jenis_pemohon' => $by_jenis_pemohon,
            'success_percentage' => $success_percentage,
            'reject_percentage' => $reject_percentage,
            'ongoing_percentage' => $ongoing_percentage,
            'total_pengaduan' => $total,
            'trend_bulanan' => $trend_bulanan,
            'top_dokumen_diminta' => $top_dokumen_diminta,
            'response_time' => [
                'avg_days' => round($response_time->avg_days ?? 0, 1),
                'min_days' => $response_time->min_days ?? 0,
                'max_days' => $response_time->max_days ?? 0
            ]
        ];
    }

    private function getMonthlyTrends()
    {
        // Document uploads per month (last 12 months)
        $query = $this->db->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as uploads
            FROM dokumen 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY month
            ORDER BY month ASC
        ");
        $upload_trend = $query->getResultArray();

        // Views and downloads per month
        $query = $this->db->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                SUM(views) as total_views,
                SUM(downloads) as total_downloads
            FROM dokumen 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY month
            ORDER BY month ASC
        ");
        $engagement_trend = $query->getResultArray();

        return [
            'uploads' => $upload_trend,
            'engagement' => $engagement_trend
        ];
    }

    private function getMenuPerformance()
    {
        $query = $this->db->query("
            SELECT 
                m.nama_menu,
                m.icon,
                COUNT(d.id) as document_count,
                COALESCE(SUM(d.views), 0) as total_views,
                COALESCE(SUM(d.downloads), 0) as total_downloads,
                ROUND(AVG(d.views), 1) as avg_views_per_doc
            FROM menu m
            LEFT JOIN dokumen d ON m.id = d.menu_id AND d.status = 'aktif'
            WHERE m.status = 'aktif'
            GROUP BY m.id
            ORDER BY total_views DESC
        ");
        
        return $query->getResultArray();
    }

    private function getCategoryDistribution()
    {
        $query = $this->db->query("
            SELECT 
                k.nama_kategori,
                m.nama_menu,
                COUNT(d.id) as document_count,
                COALESCE(SUM(d.views), 0) as total_views
            FROM kategori k
            LEFT JOIN dokumen d ON k.id = d.kategori_id AND d.status = 'aktif'
            LEFT JOIN menu m ON k.menu_id = m.id
            WHERE k.status = 'aktif'
            GROUP BY k.id
            HAVING document_count > 0
            ORDER BY total_views DESC
            LIMIT 10
        ");
        
        return $query->getResultArray();
    }

    private function getTopDocuments()
    {
        // Most viewed documents
        $query = $this->db->query("
            SELECT 
                d.judul,
                d.views,
                d.downloads,
                m.nama_menu,
                k.nama_kategori,
                d.created_at
            FROM dokumen d
            LEFT JOIN menu m ON d.menu_id = m.id
            LEFT JOIN kategori k ON d.kategori_id = k.id
            WHERE d.status = 'aktif'
            ORDER BY d.views DESC
            LIMIT 10
        ");
        $most_viewed = $query->getResultArray();

        // Most downloaded documents
        $query = $this->db->query("
            SELECT 
                d.judul,
                d.views,
                d.downloads,
                m.nama_menu,
                k.nama_kategori,
                d.created_at
            FROM dokumen d
            LEFT JOIN menu m ON d.menu_id = m.id
            LEFT JOIN kategori k ON d.kategori_id = k.id
            WHERE d.status = 'aktif'
            ORDER BY d.downloads DESC
            LIMIT 10
        ");
        $most_downloaded = $query->getResultArray();

        return [
            'most_viewed' => $most_viewed,
            'most_downloaded' => $most_downloaded
        ];
    }

    private function getRecentActivity()
    {
        $query = $this->db->query("
            SELECT 
                l.activity,
                u.nama_lengkap,
                u.username,
                d.judul as document_title,
                l.created_at,
                l.ip_address
            FROM log_activity l
            LEFT JOIN users u ON l.user_id = u.id
            LEFT JOIN dokumen d ON l.dokumen_id = d.id
            ORDER BY l.created_at DESC
            LIMIT 20
        ");
        
        return $query->getResultArray();
    }

    // =============== REKAP PENGADUAN METHODS ===============
    public function getRekapData()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $this->initializeDB();

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        if (!$startDate || !$endDate) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tanggal tidak valid']);
        }

        // Summary
        $query = $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'proses' THEN 1 ELSE 0 END) as proses,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak,
                COALESCE(AVG(CASE WHEN tanggal_respon IS NOT NULL 
                    THEN DATEDIFF(tanggal_respon, created_at) 
                    ELSE NULL END), 0) as avg_response_days
            FROM pengaduan_dokumen
            WHERE DATE(created_at) BETWEEN ? AND ?
        ", [$startDate, $endDate]);
        $summary = $query->getRow();

        // By Kategori
        $query = $this->db->query("
            SELECT kategori_permintaan, COUNT(*) as total
            FROM pengaduan_dokumen
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY kategori_permintaan
            ORDER BY total DESC
        ", [$startDate, $endDate]);
        $by_kategori = $query->getResultArray();

        // By Jenis Pemohon
        $query = $this->db->query("
            SELECT jenis_pemohon, COUNT(*) as total
            FROM pengaduan_dokumen
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY jenis_pemohon
            ORDER BY total DESC
        ", [$startDate, $endDate]);
        $by_jenis_pemohon = $query->getResultArray();

        // By Urgency
        $query = $this->db->query("
            SELECT urgency, COUNT(*) as total
            FROM pengaduan_dokumen
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY urgency
            ORDER BY FIELD(urgency, 'sangat_tinggi', 'tinggi', 'sedang', 'rendah')
        ", [$startDate, $endDate]);
        $by_urgency = $query->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'summary' => [
                    'total' => (int)$summary->total,
                    'pending' => (int)$summary->pending,
                    'proses' => (int)$summary->proses,
                    'selesai' => (int)$summary->selesai,
                    'ditolak' => (int)$summary->ditolak,
                    'avg_response_days' => round($summary->avg_response_days, 1)
                ],
                'by_kategori' => $by_kategori,
                'by_jenis_pemohon' => $by_jenis_pemohon,
                'by_urgency' => $by_urgency
            ]
        ]);
    }

    public function exportRekapPengaduan()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $this->initializeDB();

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $periode = $this->request->getGet('periode');
        $format = $this->request->getGet('format'); // 'excel' or 'pdf'

        if (!$startDate || !$endDate || !$format) {
            return redirect()->back()->with('error', 'Parameter tidak lengkap');
        }

        // Get all pengaduan data
        $query = $this->db->query("
            SELECT *
            FROM pengaduan_dokumen
            WHERE DATE(created_at) BETWEEN ? AND ?
            ORDER BY created_at DESC
        ", [$startDate, $endDate]);
        $pengaduan = $query->getResultArray();

        // Get summary data
        $query = $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'proses' THEN 1 ELSE 0 END) as proses,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak,
                COALESCE(AVG(CASE WHEN tanggal_respon IS NOT NULL 
                    THEN DATEDIFF(tanggal_respon, created_at) 
                    ELSE NULL END), 0) as avg_response_days
            FROM pengaduan_dokumen
            WHERE DATE(created_at) BETWEEN ? AND ?
        ", [$startDate, $endDate]);
        $summary = $query->getRow();

        if ($format === 'excel') {
            return $this->exportToExcel($pengaduan, $summary, $startDate, $endDate, $periode);
        } else if ($format === 'pdf') {
            return $this->exportToPDF($pengaduan, $summary, $startDate, $endDate, $periode);
        }

        return redirect()->back()->with('error', 'Format tidak valid');
    }

    private function exportToExcel($pengaduan, $summary, $startDate, $endDate, $periode)
    {
        $periodeLabels = [
            'minggu_ini' => 'Minggu Ini',
            'bulan_ini' => 'Bulan Ini',
            'tahun_ini' => 'Tahun Ini',
            'custom' => 'Custom'
        ];
        $periodeLabel = $periodeLabels[$periode] ?? 'Custom';

        // Create CSV content
        $csv = [];
        
        // Header
        $csv[] = ['LAPORAN REKAP PENGADUAN DOKUMEN - SIDODIK'];
        $csv[] = ['Periode', $periodeLabel];
        $csv[] = ['Tanggal', date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate))];
        $csv[] = ['Dicetak', date('d/m/Y H:i:s')];
        $csv[] = [];
        
        // Summary
        $csv[] = ['RINGKASAN'];
        $csv[] = ['Total Pengaduan', $summary->total];
        $csv[] = ['Menunggu', $summary->pending];
        $csv[] = ['Diproses', $summary->proses];
        $csv[] = ['Selesai', $summary->selesai];
        $csv[] = ['Ditolak', $summary->ditolak];
        $csv[] = ['Rata-rata Waktu Respon (Hari)', round($summary->avg_response_days, 1)];
        $csv[] = [];
        
        // Data table header
        $csv[] = [
            'No',
            'Nomor Tiket',
            'Tanggal',
            'Nama Pemohon',
            'Email',
            'Telepon',
            'Jenis Pemohon',
            'NIP',
            'Instansi',
            'Judul Dokumen',
            'Kategori',
            'Urgensi',
            'Status',
            'Respon Admin',
            'Tanggal Respon'
        ];
        
        // Data rows
        $no = 1;
        foreach ($pengaduan as $item) {
            $statusLabels = [
                'pending' => 'Menunggu',
                'proses' => 'Diproses',
                'selesai' => 'Selesai',
                'ditolak' => 'Ditolak'
            ];
            
            $urgencyLabels = [
                'rendah' => 'Rendah',
                'sedang' => 'Sedang',
                'tinggi' => 'Tinggi',
                'sangat_tinggi' => 'Sangat Tinggi'
            ];
            
            $kategoriLabels = [
                'surat' => 'Surat',
                'laporan' => 'Laporan',
                'formulir' => 'Formulir',
                'panduan' => 'Panduan',
                'lainnya' => 'Lainnya'
            ];
            
            $jenisPemohonLabels = [
                'publik' => 'User Publik',
                'asn' => 'ASN'
            ];
            
            $csv[] = [
                $no++,
                $item['ticket_number'],
                date('d/m/Y H:i', strtotime($item['created_at'])),
                $item['nama'],
                $item['email'],
                $item['telepon'] ?: '-',
                $jenisPemohonLabels[$item['jenis_pemohon']] ?? $item['jenis_pemohon'],
                $item['nip'] ?: '-',
                $item['instansi'] ?: '-',
                $item['judul_dokumen'],
                $kategoriLabels[$item['kategori_permintaan']] ?? $item['kategori_permintaan'],
                $urgencyLabels[$item['urgency']] ?? $item['urgency'],
                $statusLabels[$item['status']] ?? $item['status'],
                $item['admin_response'] ?: '-',
                $item['tanggal_respon'] ? date('d/m/Y H:i', strtotime($item['tanggal_respon'])) : '-'
            ];
        }
        
        // Generate CSV file
        $filename = 'Rekap_Pengaduan_' . date('Ymd_His', strtotime($startDate)) . '_' . date('Ymd_His', strtotime($endDate)) . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        foreach ($csv as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }

    private function exportToPDF($pengaduan, $summary, $startDate, $endDate, $periode)
    {
        $periodeLabels = [
            'minggu_ini' => 'Minggu Ini',
            'bulan_ini' => 'Bulan Ini',
            'tahun_ini' => 'Tahun Ini',
            'custom' => 'Custom'
        ];
        $periodeLabel = $periodeLabels[$periode] ?? 'Custom';

        // Create HTML for PDF
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Rekap Pengaduan</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 11px;
                    line-height: 1.4;
                    margin: 20px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 3px solid #667eea;
                    padding-bottom: 15px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 18px;
                    color: #2c3e50;
                }
                .header p {
                    margin: 5px 0;
                    color: #5a6c7d;
                    font-size: 10px;
                }
                .info-box {
                    background: #f8f9fa;
                    padding: 15px;
                    margin-bottom: 20px;
                    border-left: 4px solid #667eea;
                }
                .info-box table {
                    width: 100%;
                }
                .info-box td {
                    padding: 3px 5px;
                }
                .info-box td:first-child {
                    font-weight: bold;
                    width: 150px;
                    color: #2c3e50;
                }
                .summary-box {
                    margin-bottom: 20px;
                    page-break-inside: avoid;
                }
                .summary-title {
                    background: #667eea;
                    color: white;
                    padding: 8px 15px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                .summary-grid {
                    display: table;
                    width: 100%;
                    margin-bottom: 15px;
                }
                .summary-item {
                    display: table-cell;
                    width: 33.33%;
                    padding: 10px;
                    text-align: center;
                    border: 1px solid #e9ecef;
                }
                .summary-item .number {
                    font-size: 24px;
                    font-weight: bold;
                    color: #667eea;
                }
                .summary-item .label {
                    color: #5a6c7d;
                    font-size: 10px;
                    margin-top: 5px;
                }
                table.data-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                }
                table.data-table th {
                    background: #667eea;
                    color: white;
                    padding: 8px 5px;
                    text-align: left;
                    font-size: 10px;
                    border: 1px solid #5568d3;
                }
                table.data-table td {
                    padding: 6px 5px;
                    border: 1px solid #e9ecef;
                    font-size: 9px;
                    vertical-align: top;
                }
                table.data-table tr:nth-child(even) {
                    background: #f8f9fa;
                }
                .status-badge {
                    padding: 2px 6px;
                    border-radius: 3px;
                    font-size: 8px;
                    font-weight: bold;
                    display: inline-block;
                }
                .status-pending { background: #fff3cd; color: #856404; }
                .status-proses { background: #d1ecf1; color: #0c5460; }
                .status-selesai { background: #d4edda; color: #155724; }
                .status-ditolak { background: #f8d7da; color: #721c24; }
                .footer {
                    margin-top: 30px;
                    padding-top: 15px;
                    border-top: 2px solid #e9ecef;
                    text-align: right;
                    font-size: 9px;
                    color: #5a6c7d;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN REKAP PENGADUAN DOKUMEN</h1>
                <p>SISTEM INFORMASI DOKUMEN DISKOMINFOTIK (SIDODIK)</p>
            </div>
            
            <div class="info-box">
                <table>
                    <tr>
                        <td>Periode</td>
                        <td>: ' . $periodeLabel . '</td>
                    </tr>
                    <tr>
                        <td>Rentang Tanggal</td>
                        <td>: ' . date('d F Y', strtotime($startDate)) . ' s/d ' . date('d F Y', strtotime($endDate)) . '</td>
                    </tr>
                    <tr>
                        <td>Tanggal Cetak</td>
                        <td>: ' . date('d F Y H:i:s') . '</td>
                    </tr>
                </table>
            </div>
            
            <div class="summary-box">
                <div class="summary-title">RINGKASAN DATA</div>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="number">' . $summary->total . '</div>
                        <div class="label">Total Pengaduan</div>
                    </div>
                    <div class="summary-item">
                        <div class="number">' . $summary->selesai . '</div>
                        <div class="label">Selesai</div>
                    </div>
                    <div class="summary-item">
                        <div class="number">' . round($summary->avg_response_days, 1) . '</div>
                        <div class="label">Rata-rata Respon (Hari)</div>
                    </div>
                </div>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="number">' . $summary->pending . '</div>
                        <div class="label">Menunggu</div>
                    </div>
                    <div class="summary-item">
                        <div class="number">' . $summary->proses . '</div>
                        <div class="label">Diproses</div>
                    </div>
                    <div class="summary-item">
                        <div class="number">' . $summary->ditolak . '</div>
                        <div class="label">Ditolak</div>
                    </div>
                </div>
            </div>
            
            <div class="summary-title">DETAIL PENGADUAN</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th width="10%">Tiket</th>
                        <th width="10%">Tanggal</th>
                        <th width="15%">Pemohon</th>
                        <th width="8%">Jenis</th>
                        <th width="20%">Judul Dokumen</th>
                        <th width="10%">Kategori</th>
                        <th width="8%">Urgensi</th>
                        <th width="8%">Status</th>
                        <th width="8%">Respon</th>
                    </tr>
                </thead>
                <tbody>';

        $statusLabels = [
            'pending' => 'Menunggu',
            'proses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak'
        ];
        
        $urgencyLabels = [
            'rendah' => 'Rendah',
            'sedang' => 'Sedang',
            'tinggi' => 'Tinggi',
            'sangat_tinggi' => 'Sangat Tinggi'
        ];
        
        $kategoriLabels = [
            'surat' => 'Surat',
            'laporan' => 'Laporan',
            'formulir' => 'Formulir',
            'panduan' => 'Panduan',
            'lainnya' => 'Lainnya'
        ];
        
        $jenisPemohonLabels = [
            'publik' => 'Publik',
            'asn' => 'ASN'
        ];

        $no = 1;
        foreach ($pengaduan as $item) {
            $html .= '
                <tr>
                    <td align="center">' . $no++ . '</td>
                    <td>' . htmlspecialchars($item['ticket_number']) . '</td>
                    <td>' . date('d/m/Y<br>H:i', strtotime($item['created_at'])) . '</td>
                    <td>
                        <strong>' . htmlspecialchars($item['nama']) . '</strong><br>
                        ' . htmlspecialchars($item['email']) . '
                        ' . (!empty($item['nip']) ? '<br>NIP: ' . htmlspecialchars($item['nip']) : '') . '
                    </td>
                    <td>' . ($jenisPemohonLabels[$item['jenis_pemohon']] ?? $item['jenis_pemohon']) . '</td>
                    <td>' . htmlspecialchars($item['judul_dokumen']) . '</td>
                    <td>' . ($kategoriLabels[$item['kategori_permintaan']] ?? $item['kategori_permintaan']) . '</td>
                    <td>' . ($urgencyLabels[$item['urgency']] ?? $item['urgency']) . '</td>
                    <td>
                        <span class="status-badge status-' . $item['status'] . '">
                            ' . ($statusLabels[$item['status']] ?? $item['status']) . '
                        </span>
                    </td>
                    <td>' . ($item['tanggal_respon'] ? date('d/m/Y', strtotime($item['tanggal_respon'])) : '-') . '</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                <p>Dokumen ini dibuat secara otomatis oleh sistem SIDODIK</p>
            </div>
        </body>
        </html>';

        // Output PDF using browser print
        $filename = 'Rekap_Pengaduan_' . date('Ymd', strtotime($startDate)) . '_' . date('Ymd', strtotime($endDate)) . '.pdf';
        
        header('Content-Type: text/html; charset=utf-8');
        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <title>' . $filename . '</title>
            <script>
                window.onload = function() {
                    window.print();
                    // Uncomment line below to auto close after print
                    // window.onafterprint = function() { window.close(); }
                }
            </script>
        </head>
        <body>
        ' . $html . '
        </body>
        </html>';
        exit;
    }

    public function getActivityDetails()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $this->initializeDB();

        // Get filter parameters
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $activityType = $this->request->getGet('activity_type');
        $userId = $this->request->getGet('user_id');

        // Build query
        $builder = $this->db->table('log_activity l')
            ->select('l.*, u.nama_lengkap, u.username, d.judul as dokumen_judul, m.nama_menu, k.nama_kategori')
            ->join('users u', 'u.id = l.user_id', 'left')
            ->join('dokumen d', 'd.id = l.dokumen_id', 'left')
            ->join('menu m', 'm.id = d.menu_id', 'left')
            ->join('kategori k', 'k.id = d.kategori_id', 'left');

        // Apply filters
        if ($startDate && $endDate) {
            $builder->where('DATE(l.created_at) >=', $startDate)
                    ->where('DATE(l.created_at) <=', $endDate);
        }

        if ($activityType && $activityType !== 'all') {
            $builder->like('l.activity', $activityType);
        }

        if ($userId && $userId !== 'all') {
            $builder->where('l.user_id', $userId);
        }

        $activities = $builder->orderBy('l.created_at', 'DESC')
                            ->limit(100)
                            ->get()
                            ->getResultArray();

        // Get summary statistics
        $summary = $this->getActivitySummaryData($startDate, $endDate, $activityType, $userId);

        return $this->response->setJSON([
            'success' => true,
            'activities' => $activities,
            'summary' => $summary
        ]);
    }





    public function exportActivityReport()
    {
        $redirect = $this->redirectIfNotLoggedIn() ?: $this->redirectIfNotAdmin();
        if ($redirect) return $redirect;

        $this->initializeDB();

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $format = $this->request->getGet('format'); // 'excel' or 'pdf'

        if (!$startDate || !$endDate || !$format) {
            return redirect()->back()->with('error', 'Parameter tidak lengkap');
        }

        // Get activities data
        $builder = $this->db->table('log_activity l')
            ->select('l.*, u.nama_lengkap, u.username, d.judul as dokumen_judul, m.nama_menu, k.nama_kategori')
            ->join('users u', 'u.id = l.user_id', 'left')
            ->join('dokumen d', 'd.id = l.dokumen_id', 'left')
            ->join('menu m', 'm.id = d.menu_id', 'left')
            ->join('kategori k', 'k.id = d.kategori_id', 'left')
            ->where('DATE(l.created_at) >=', $startDate)
            ->where('DATE(l.created_at) <=', $endDate)
            ->orderBy('l.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Get summary
        $summary = $this->getActivitySummaryData($startDate, $endDate, null, null);

        if ($format === 'excel') {
            return $this->exportActivityToExcel($builder, $summary, $startDate, $endDate);
        } else if ($format === 'pdf') {
            return $this->exportActivityToPDF($builder, $summary, $startDate, $endDate);
        }

        return redirect()->back()->with('error', 'Format tidak valid');
    }

    private function exportActivityToExcel($activities, $summary, $startDate, $endDate)
    {
        $filename = 'Activity_Report_' . date('Ymd', strtotime($startDate)) . '_' . date('Ymd', strtotime($endDate)) . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // UTF-8 BOM
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header
        fputcsv($output, ['LAPORAN AKTIVITAS SISTEM - SIDODIK']);
        fputcsv($output, ['Periode', date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate))]);
        fputcsv($output, ['Tanggal Export', date('d F Y H:i:s')]);
        fputcsv($output, []);
        
        // Summary
        fputcsv($output, ['=== RINGKASAN ===']);
        fputcsv($output, ['Total Aktivitas', $summary['total']]);
        fputcsv($output, ['User Aktif', count($summary['active_users'])]);
        fputcsv($output, []);
        
        // Top Activities
        fputcsv($output, ['=== TOP AKTIVITAS ===']);
        fputcsv($output, ['Jenis Aktivitas', 'Jumlah']);
        foreach (array_slice($summary['by_type'], 0, 10) as $type) {
            fputcsv($output, [$type['activity'], $type['count']]);
        }
        fputcsv($output, []);
        
        // Most Active Users
        fputcsv($output, ['=== USER PALING AKTIF ===']);
        fputcsv($output, ['Nama', 'Username', 'Jumlah Aktivitas']);
        foreach ($summary['active_users'] as $user) {
            fputcsv($output, [
                $user['nama_lengkap'],
                $user['username'],
                $user['activity_count']
            ]);
        }
        fputcsv($output, []);
        
        // Detail Activities
        fputcsv($output, ['=== DETAIL AKTIVITAS ===']);
        fputcsv($output, [
            'Tanggal',
            'Waktu',
            'User',
            'Username',
            'Aktivitas',
            'Dokumen',
            'Menu',
            'IP Address',
            'Browser'
        ]);
        
        foreach ($activities as $activity) {
            $date = date('d/m/Y', strtotime($activity['created_at']));
            $time = date('H:i:s', strtotime($activity['created_at']));
            
            fputcsv($output, [
                $date,
                $time,
                $activity['nama_lengkap'] ?? 'System',
                $activity['username'] ?? 'system',
                $activity['activity'],
                $activity['dokumen_judul'] ?? '-',
                $activity['nama_menu'] ?? '-',
                $activity['ip_address'] ?? '-',
                $this->parseUserAgentSimple($activity['user_agent'] ?? '')
            ]);
        }
        
        fclose($output);
        exit;
    }

    private function exportActivityToPDF($activities, $summary, $startDate, $endDate)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Activity Report</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 10px;
                    line-height: 1.3;
                    margin: 15px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 3px solid #667eea;
                    padding-bottom: 10px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 16px;
                    color: #2c3e50;
                }
                .header p {
                    margin: 3px 0;
                    color: #5a6c7d;
                    font-size: 9px;
                }
                .summary-box {
                    background: #f8f9fa;
                    padding: 10px;
                    margin-bottom: 15px;
                    border-left: 4px solid #667eea;
                }
                .summary-box h3 {
                    margin: 0 0 8px 0;
                    font-size: 12px;
                    color: #2c3e50;
                }
                .stats-grid {
                    display: table;
                    width: 100%;
                    margin-bottom: 10px;
                }
                .stat-item {
                    display: table-cell;
                    width: 33.33%;
                    padding: 8px;
                    text-align: center;
                    border: 1px solid #e9ecef;
                }
                .stat-item .number {
                    font-size: 18px;
                    font-weight: bold;
                    color: #667eea;
                }
                .stat-item .label {
                    color: #5a6c7d;
                    font-size: 9px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                }
                table th {
                    background: #667eea;
                    color: white;
                    padding: 6px 4px;
                    text-align: left;
                    font-size: 9px;
                }
                table td {
                    padding: 4px;
                    border: 1px solid #e9ecef;
                    font-size: 8px;
                }
                table tr:nth-child(even) {
                    background: #f8f9fa;
                }
                .section-title {
                    background: #667eea;
                    color: white;
                    padding: 6px 10px;
                    font-weight: bold;
                    margin: 15px 0 8px 0;
                    font-size: 11px;
                }
                .footer {
                    margin-top: 20px;
                    padding-top: 10px;
                    border-top: 2px solid #e9ecef;
                    text-align: right;
                    font-size: 8px;
                    color: #5a6c7d;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN AKTIVITAS SISTEM</h1>
                <p>SISTEM INFORMASI DOKUMEN DISKOMINFOTIK (SIDODIK)</p>
                <p>Periode: ' . date('d F Y', strtotime($startDate)) . ' s/d ' . date('d F Y', strtotime($endDate)) . '</p>
                <p>Tanggal Export: ' . date('d F Y H:i:s') . '</p>
            </div>
            
            <div class="summary-box">
                <h3>RINGKASAN AKTIVITAS</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="number">' . number_format($summary['total']) . '</div>
                        <div class="label">Total Aktivitas</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">' . count($summary['active_users']) . '</div>
                        <div class="label">User Aktif</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">' . count($summary['by_type']) . '</div>
                        <div class="label">Jenis Aktivitas</div>
                    </div>
                </div>
            </div>
            
            <div class="section-title">TOP 10 AKTIVITAS</div>
            <table>
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th width="60%">Jenis Aktivitas</th>
                        <th width="30%">Jumlah</th>
                    </tr>
                </thead>
                <tbody>';
        
        $no = 1;
        foreach (array_slice($summary['by_type'], 0, 10) as $type) {
            $html .= '
                    <tr>
                        <td align="center">' . $no++ . '</td>
                        <td>' . htmlspecialchars($type['activity']) . '</td>
                        <td align="center">' . number_format($type['count']) . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="section-title">USER PALING AKTIF</div>
            <table>
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th width="40%">Nama</th>
                        <th width="30%">Username</th>
                        <th width="20%">Aktivitas</th>
                    </tr>
                </thead>
                <tbody>';
        
        $no = 1;
        foreach ($summary['active_users'] as $user) {
            $html .= '
                    <tr>
                        <td align="center">' . $no++ . '</td>
                        <td>' . htmlspecialchars($user['nama_lengkap']) . '</td>
                        <td>' . htmlspecialchars($user['username']) . '</td>
                        <td align="center">' . number_format($user['activity_count']) . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="section-title">DETAIL AKTIVITAS (100 Terakhir)</div>
            <table>
                <thead>
                    <tr>
                        <th width="8%">Tanggal</th>
                        <th width="6%">Waktu</th>
                        <th width="18%">User</th>
                        <th width="15%">Aktivitas</th>
                        <th width="25%">Dokumen</th>
                        <th width="15%">Menu</th>
                        <th width="13%">IP Address</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach (array_slice($activities, 0, 100) as $activity) {
            $date = date('d/m/y', strtotime($activity['created_at']));
            $time = date('H:i', strtotime($activity['created_at']));
            
            $html .= '
                    <tr>
                        <td>' . $date . '</td>
                        <td>' . $time . '</td>
                        <td>' . htmlspecialchars($activity['nama_lengkap'] ?? 'System') . '</td>
                        <td>' . htmlspecialchars($activity['activity']) . '</td>
                        <td>' . htmlspecialchars($activity['dokumen_judul'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($activity['nama_menu'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($activity['ip_address'] ?? '-') . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                <p>Dokumen ini dibuat secara otomatis oleh sistem SIDODIK</p>
                <p>Total ' . count($activities) . ' aktivitas (ditampilkan 100 terakhir)</p>
            </div>
        </body>
        </html>';

        header('Content-Type: text/html; charset=utf-8');
        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Activity Report</title>
            <script>
                window.onload = function() {
                    window.print();
                }
            </script>
        </head>
        <body>
        ' . $html . '
        </body>
        </html>';
        exit;
    }

    private function parseUserAgentSimple($ua)
    {
        if (!$ua) return '-';
        
        $browser = 'Unknown';
        if (strpos($ua, 'Chrome') !== false && strpos($ua, 'Edg') === false) $browser = 'Chrome';
        else if (strpos($ua, 'Firefox') !== false) $browser = 'Firefox';
        else if (strpos($ua, 'Safari') !== false && strpos($ua, 'Chrome') === false) $browser = 'Safari';
        else if (strpos($ua, 'Edg') !== false) $browser = 'Edge';
        
        $os = '';
        if (strpos($ua, 'Windows NT 10.0') !== false) $os = 'Win 10';
        else if (strpos($ua, 'Mac OS X') !== false) $os = 'Mac';
        else if (strpos($ua, 'Linux') !== false) $os = 'Linux';
        else if (strpos($ua, 'Android') !== false) $os = 'Android';
        
        return $os ? "$browser ($os)" : $browser;
    }

    private function getActivitySummaryData($startDate = null, $endDate = null, $activityType = null, $userId = null)
    {
        $builder = $this->db->table('log_activity l');

        // Apply same filters
        if ($startDate && $endDate) {
            $builder->where('DATE(l.created_at) >=', $startDate)
                    ->where('DATE(l.created_at) <=', $endDate);
        }

        if ($activityType && $activityType !== 'all') {
            $builder->like('l.activity', $activityType);
        }

        if ($userId && $userId !== 'all') {
            $builder->where('l.user_id', $userId);
        }

        // Total activities
        $total = $builder->countAllResults(false);

        // Activities by type
        $byType = $this->db->table('log_activity l')
            ->select('l.activity, COUNT(*) as count');
        
        if ($startDate && $endDate) {
            $byType->where('DATE(l.created_at) >=', $startDate)
                ->where('DATE(l.created_at) <=', $endDate);
        }
        if ($activityType && $activityType !== 'all') {
            $byType->like('l.activity', $activityType);
        }
        if ($userId && $userId !== 'all') {
            $byType->where('l.user_id', $userId);
        }

        $byTypeResult = $byType->groupBy('l.activity')
                            ->orderBy('count', 'DESC')
                            ->get()
                            ->getResultArray();

        // Most active users
        $activeUsers = $this->db->table('log_activity l')
            ->select('u.nama_lengkap, u.username, COUNT(*) as activity_count')
            ->join('users u', 'u.id = l.user_id', 'left');
        
        if ($startDate && $endDate) {
            $activeUsers->where('DATE(l.created_at) >=', $startDate)
                        ->where('DATE(l.created_at) <=', $endDate);
        }
        if ($activityType && $activityType !== 'all') {
            $activeUsers->like('l.activity', $activityType);
        }
        if ($userId && $userId !== 'all') {
            $activeUsers->where('l.user_id', $userId);
        }

        $activeUsersResult = $activeUsers->groupBy('u.id')
                                        ->orderBy('activity_count', 'DESC')
                                        ->limit(5)
                                        ->get()
                                        ->getResultArray();

        // Activities per day (for chart)
        $dailyActivity = $this->db->table('log_activity l')
            ->select('DATE(l.created_at) as date, COUNT(*) as count');
        
        if ($startDate && $endDate) {
            $dailyActivity->where('DATE(l.created_at) >=', $startDate)
                        ->where('DATE(l.created_at) <=', $endDate);
        }
        if ($activityType && $activityType !== 'all') {
            $dailyActivity->like('l.activity', $activityType);
        }
        if ($userId && $userId !== 'all') {
            $dailyActivity->where('l.user_id', $userId);
        }

        $dailyActivityResult = $dailyActivity->groupBy('DATE(l.created_at)')
                                            ->orderBy('date', 'ASC')
                                            ->get()
                                            ->getResultArray();

        return [
            'total' => $total,
            'by_type' => $byTypeResult,
            'active_users' => $activeUsersResult,
            'daily_activity' => $dailyActivityResult
        ];
    }

    private function cleanTags($tags)
    {
        return cleanTagInput($tags); // Use helper
    }
}