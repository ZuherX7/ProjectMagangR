<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIDODIK</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Main Content Background dengan Image */
        .main-content {
            background: url('<?= base_url("assets/img/gedung2.png") ?>') center center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
            min-height: 100vh;
            flex: 1;
        }

        /* Overlay untuk readability */
        .main-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: 1;
        }

        /* Semua konten di atas overlay */
        .content-header,
        .dashboard-content {
            position: relative;
            z-index: 2;
        }

        /* Header dengan background semi-transparent */
        .content-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left .header-info h1 {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 5px 0;
        }

        .header-left .header-info p {
            color: #5a6c7d;
            margin: 0;
            font-size: 14px;
        }

        .header-right .breadcrumb {
            color: #667eea;
            font-weight: 500;
            font-size: 14px;
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 30px;
        }

        /* Welcome Section dengan background dan kontras yang baik */
        .welcome-section {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .welcome-content h2 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .welcome-content p {
            color: #5a6c7d;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .breadcrumb-welcome {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        /* Stat Cards dengan background semi-transparent */
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            background: rgba(255, 255, 255, 0.98);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-content h3 {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 5px 0;
        }

        .stat-content p {
            color: #5a6c7d;
            font-size: 16px;
            font-weight: 500;
            margin: 0 0 8px 0;
        }

        .stat-trend {
            font-size: 12px;
            color: #7f8c8d;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .trend-up {
            color: #27ae60;
        }

        /* Quick Actions dengan background */
        .quick-actions {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .quick-actions h3 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        /* Action Buttons Enhancement */
        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 20px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        .action-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .action-secondary {
            background: rgba(255, 255, 255, 0.9);
            color: #2c3e50;
        }

        /* Recent Section dengan background */
        .recent-section {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .recent-section .section-header h3 {
            color: #2c3e50;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            margin: 0;
        }

        .view-all-btn {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        .view-all-btn:hover {
            color: #764ba2;
            text-decoration: none;
        }

        /* Recent Items dengan background */
        .recent-item {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
        }

        .recent-item:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateX(5px);
        }

        .recent-item:last-child {
            margin-bottom: 0;
        }

        .document-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }

        .doc-pdf { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .doc-word { background: linear-gradient(135deg, #3498db, #2980b9); }
        .doc-excel { background: linear-gradient(135deg, #27ae60, #229954); }
        .doc-ppt { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .doc-default { background: linear-gradient(135deg, #95a5a6, #7f8c8d); }

        .recent-content {
            flex: 1;
        }

        .recent-content h4 {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }

        .recent-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 5px;
        }

        .meta-item {
            font-size: 12px;
            color: #7f8c8d;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .recent-date {
            font-size: 12px;
            color: #bdc3c7;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content::before {
                background: rgba(255, 255, 255, 0.9);
            }
            
            .content-header {
                padding: 15px 20px;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .dashboard-content {
                padding: 20px 15px;
            }
            
            .welcome-section,
            .quick-actions,
            .recent-section {
                padding: 20px;
                margin-bottom: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .action-grid {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="<?= base_url('assets/img/kbb.png') ?>" alt="Logo" class="header-logo">
                <h3>SIDODIK</h3>
                <p>Admin Control Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item active">
                        <a href="<?= base_url('admin/dashboard') ?>" class="nav-link">
                            <i class="fas fa-chart-bar"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('admin/dokumen') ?>" class="nav-link">
                            <i class="fas fa-file-alt"></i>
                            <span>Kelola Dokumen</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('admin/menu') ?>" class="nav-link">
                            <i class="fas fa-bars"></i>
                            <span>Kelola Menu</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('admin/kategori') ?>" class="nav-link">
                            <i class="fas fa-tags"></i>
                            <span>Kelola Kategori</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('admin/user') ?>" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span>Kelola User</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="<?= base_url('auth/logout') ?>" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="content-header">
                <div class="header-left">
                    <div class="header-info">
                        <h1>Sistem Informasi Dokumen Diskominfotik</h1>
                        <p>Kabupaten Bandung Barat - Admin Panel</p>
                    </div>
                </div>
                <div class="header-right">
                    <span class="breadcrumb">Dashboard</span>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <div class="welcome-content">
                        <h2>Selamat Datang di Dashboard Admin</h2>
                        <p>Kelola sistem informasi dokumen Diskominfotik Kabupaten Bandung Barat</p>
                        <span class="breadcrumb-welcome">
                            <i class="fas fa-user-shield"></i>
                            Administrator Panel
                        </span>
                    </div>
                </div>

                <!-- Menu Statistics Cards -->
                <div class="stats-grid">
                    <!-- Total Dokumen Card -->
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?= $stats['total_dokumen'] ?? 0 ?></h3>
                            <p>Total Dokumen</p>
                            <div class="stat-trend trend-up">
                                <i class="fas fa-chart-line"></i>
                                Semua kategori
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Menu Cards dari Database (sama seperti halaman menu) -->
                    <?php if (!empty($stats['menu_stats'])): ?>
                        <?php foreach ($stats['menu_stats'] as $menu): ?>
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-<?= !empty($menu['icon']) ? esc($menu['icon']) : 'folder' ?>"></i>
                                </div>
                                <div class="stat-content">
                                    <h3><?= $menu['jumlah_dokumen'] ?? 0 ?></h3>
                                    <p><?= esc($menu['nama_menu']) ?></p>
                                    <div class="stat-trend">
                                        <i class="fas fa-folder"></i>
                                        Menu dokumen
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback jika tidak ada menu -->
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div class="stat-content">
                                <h3>0</h3>
                                <p>Menu Belum Ada</p>
                                <div class="stat-trend">
                                    <i class="fas fa-info-circle"></i>
                                    Tambah menu terlebih dahulu
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h3>
                        <i class="fas fa-bolt"></i>
                        Aksi Cepat
                    </h3>
                    <div class="action-grid">
                        <a href="<?= base_url('admin/dokumen') ?>" class="action-btn action-primary">
                            <i class="fas fa-plus"></i>
                            Tambah Dokumen Baru
                        </a>
                        <a href="<?= base_url('admin/menu') ?>" class="action-btn action-secondary">
                            <i class="fas fa-bars"></i>
                            Kelola Menu
                        </a>
                        <a href="<?= base_url('admin/kategori') ?>" class="action-btn action-secondary">
                            <i class="fas fa-tags"></i>
                            Kelola Kategori
                        </a>
                        <a href="<?= base_url('admin/user') ?>" class="action-btn action-secondary">
                            <i class="fas fa-users"></i>
                            Kelola User
                        </a>
                    </div>
                </div>

                <!-- Recent Documents -->
                <div class="recent-section">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-clock"></i>
                            Dokumen Terbaru
                        </h3>
                        <a href="<?= base_url('admin/dokumen') ?>" class="view-all-btn">
                            Lihat Semua
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <?php if (!empty($recent_docs)): ?>
                        <?php foreach ($recent_docs as $doc): ?>
                            <div class="recent-item">
                                <div class="document-icon doc-<?php 
                                    // Normalisasi file type untuk CSS class
                                    $fileType = strtolower($doc['file_type'] ?? 'default');
                                    
                                    // Mapping file type ke CSS class yang sudah ada
                                    $cssClassMap = [
                                        'pdf' => 'pdf',
                                        'doc' => 'word',    // doc -> word
                                        'docx' => 'word',   // docx -> word  
                                        'xls' => 'excel',   // xls -> excel
                                        'xlsx' => 'excel',  // xlsx -> excel
                                        'ppt' => 'ppt',     // ppt -> ppt
                                        'pptx' => 'ppt'     // pptx -> ppt
                                    ];
                                    
                                    echo $cssClassMap[$fileType] ?? 'default';
                                ?>">
                                    <?php
                                    // Icon mapping yang konsisten
                                    $iconMap = [
                                        'pdf' => 'fa-file-pdf',
                                        'doc' => 'fa-file-word',
                                        'docx' => 'fa-file-word',
                                        'xls' => 'fa-file-excel', 
                                        'xlsx' => 'fa-file-excel',
                                        'ppt' => 'fa-file-powerpoint',
                                        'pptx' => 'fa-file-powerpoint'
                                    ];
                                    
                                    $fileType = strtolower($doc['file_type'] ?? 'default');
                                    $iconClass = $iconMap[$fileType] ?? 'fa-file';
                                    ?>
                                    <i class="fas <?= $iconClass ?>"></i>
                                </div>
                                <div class="recent-content">
                                    <h4><?= esc($doc['judul']) ?></h4>
                                    <div class="recent-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-folder"></i>
                                            <?= esc($doc['nama_menu'] ?? 'Unknown') ?>
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-tag"></i>
                                            <?= esc($doc['nama_kategori'] ?? 'Unknown') ?>
                                        </span>
                                    </div>
                                    <span class="recent-date"><?= date('d M Y', strtotime($doc['created_at'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="recent-item" style="text-align: center; padding: 40px 0; border: none;">
                            <div style="color: #bdc3c7; margin-bottom: 12px;">
                                <i class="fas fa-inbox" style="font-size: 48px;"></i>
                            </div>
                            <h4 style="color: #7f8c8d; margin-bottom: 8px;">Belum Ada Dokumen</h4>
                            <p style="color: #bdc3c7; font-size: 14px; margin: 0;">
                                Dokumen yang baru ditambahkan akan muncul di sini
                            </p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </main>
    </div>

    <script src="<?= base_url('assets/js/admin.js') ?>"></script>
</body>
</html>