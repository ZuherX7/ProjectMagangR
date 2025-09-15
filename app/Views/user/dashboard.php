<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard User' ?> - SIDODIK</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/user.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Add this to the existing styles */
        .doc-file-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            color: #666;
            background: #f8f9fa;
            padding: 8px 16px;
            border-radius: 12px;
            margin-bottom: 10px;
            width: fit-content;
        }

        /* .doc-file-name {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: #666;
            background: #f8f9fa;
            padding: 6px 12px;
            border-radius: 12px;
            margin-bottom: 10px;
            width: fit-content;
        } */

        .doc-file-name i {
            color: #667eea;
        }
        
        /* Update hero-section di dashboard.php - Ganti yang lama dengan ini */
        .hero-section {
            background: url('<?= base_url("assets/img/gedung2.png") ?>') center center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
            padding: 60px 0 80px 0; /* Tambah padding bottom lebih besar */
            position: relative;
            overflow: hidden;
            min-height: 100vh; /* Pastikan full viewport height */
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4); /* Dark overlay untuk readability */
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 1000px;
            margin: 0 auto;
            padding: 50px 0; /* Tambah padding untuk content */
        }
        
        .hero-section h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 16px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .hero-section .lead {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 40px;
            line-height: 1.5;
        }
        
        .search-highlight {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 20px;
        }
        
        .search-highlight h3 {
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .hero-search-wrapper {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .hero-search-input {
            width: 100%;
            padding: 16px 60px 16px 24px;
            border: none;
            border-radius: 30px;
            background: white;
            font-size: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .hero-search-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #4a6fa5 0%, #166ba0 100%);
            border: none;
            color: white;
            padding: 12px 16px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .hero-search-btn:hover {
            transform: translateY(-50%) scale(1.05);
        }
        
        .user-greeting-hero {
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
            font-size: 16px;
            font-weight: 500;
            margin-top: 20px;
        }
        
        /* Enhanced Stats Section */
        .stats-section {
            padding: 50px 0;
            background: #f8f9fa;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 24px;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 500;
        }
        
        /* Enhanced Menu Section */
        .menu-section {
            padding: 50px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 36px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .section-title p {
            font-size: 18px;
            color: #7f8c8d;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .menu-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
        }
        
        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .menu-link {
            display: flex;
            align-items: center;
            padding: 35px;
            text-decoration: none;
            color: inherit;
            gap: 25px;
        }
        
        .menu-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            flex-shrink: 0;
        }
        
        .menu-icon i {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1;
        }
        
        .menu-info h3 {
            font-size: 22px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .doc-count {
            color: #667eea;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Enhanced Documents Section */
        .documents-section {
            padding: 50px 0;
            background: #f8f9fa;
        }
        
        .document-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
        }
        
        .document-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .document-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section {
                padding: 40px 0;
            }
            
            .hero-section h1 {
                font-size: 32px;
            }
            
            .hero-section .lead {
                font-size: 18px;
            }
            
            .search-highlight {
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
            
            .section-title h2 {
                font-size: 28px;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .menu-link {
                padding: 25px;
                gap: 20px;
            }
            
            .menu-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="top-navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="navbar-left">
                    <div class="logo-section">
                        <img src="<?= base_url('assets/img/kbb.png') ?>" alt="Logo" class="navbar-logo">
                        <span class="app-name">SIDODIK</span>
                    </div>
                </div>
                
                <div class="navbar-right">
                    <div class="nav-links">
                        <a href="<?= base_url('user/dokumen') ?>" class="nav-link">Semua Dokumen</a>
                        <span class="user-greeting">Selamat datang, <strong><?= esc($user['nama_lengkap']) ?></strong></span>
                        <a href="<?= base_url('auth/logout') ?>" class="logout-btn">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>Sistem Informasi Dokumen Diskominfotik</h1>
                <p class="lead">Akses mudah ke berbagai dokumen resmi Diskominfotik Kabupaten Bandung Barat</p>
                
                <div class="search-highlight">
                    <h3>Cari Dokumen</h3>
                    <form action="<?= base_url('user/dokumen') ?>" method="GET">
                        <div class="hero-search-wrapper">
                            <input type="text" name="q" class="hero-search-input" placeholder="Masukkan kata kunci pencarian..." value="<?= $keyword ?? '' ?>">
                            <button type="submit" class="hero-search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <span class="user-greeting-hero">
                    <i class="fas fa-user"></i>
                    <?= esc($user['nama_lengkap']) ?>
                </span>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Stats Section -->
        <?php if (isset($menu) && !empty($menu)): ?>
        <section class="stats-section">
            <div class="container">
                <div class="stats-grid">
                    <!-- Total Documents -->
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-number"><?= array_sum(array_column($menu, 'jumlah_dokumen')) ?></div>
                        <div class="stat-label">Total Dokumen</div>
                    </div>

                    <!-- Total Menu -->
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-bars"></i>
                        </div>
                        <div class="stat-number"><?= count($menu) ?></div>
                        <div class="stat-label">Total Menu</div>
                    </div>
                    
                    <!-- Total Categories -->
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="stat-number"><?= count($kategori) ?></div>
                        <div class="stat-label">Kategori Tersedia</div>
                    </div>
                    
                    <!-- Total Users -->
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number"><?= $total_users ?? 0 ?></div>
                        <div class="stat-label">Total User</div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <div class="container">
            <!-- Menu Categories -->
            <section class="menu-section">
                <div class="section-title">
                    <h2>Menu Dokumen</h2>
                    <p>Pilih menu untuk mengakses dokumen sesuai kebutuhan Anda</p>
                </div>
                
                <div class="menu-grid">
                    <?php if (isset($menu) && !empty($menu)): ?>
                        <?php foreach ($menu as $item): ?>
                            <div class="menu-card">
                                <a href="<?= base_url('user/dokumen/menu/' . $item['id']) ?>" class="menu-link">
                                    <div class="menu-icon">
                                        <?php if (!empty($item['icon'])): ?>
                                            <i class="fas fa-<?= esc($item['icon']) ?>"></i>
                                        <?php else: ?>
                                            <i class="fas fa-folder"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="menu-info">
                                        <h3><?= esc($item['nama_menu']) ?></h3>
                                        <span class="doc-count">
                                            <i class="fas fa-file"></i>
                                            <?= $item['jumlah_dokumen'] ?? 0 ?> dokumen
                                        </span>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="menu-card">
                            <a href="#" class="menu-link">
                                <div class="menu-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="menu-info">
                                    <h3>E-book</h3>
                                    <span class="doc-count">
                                        <i class="fas fa-file"></i>
                                        5 dokumen
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="menu-card">
                            <a href="#" class="menu-link">
                                <div class="menu-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="menu-info">
                                    <h3>Laporan</h3>
                                    <span class="doc-count">
                                        <i class="fas fa-file"></i>
                                        8 dokumen
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="menu-card">
                            <a href="#" class="menu-link">
                                <div class="menu-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="menu-info">
                                    <h3>Surat</h3>
                                    <span class="doc-count">
                                        <i class="fas fa-file"></i>
                                        12 dokumen
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="menu-card">
                            <a href="#" class="menu-link">
                                <div class="menu-icon">
                                    <i class="fas fa-wpforms"></i>
                                </div>
                                <div class="menu-info">
                                    <h3>Formulir</h3>
                                    <span class="doc-count">
                                        <i class="fas fa-file"></i>
                                        6 dokumen
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Recent Documents -->
            <section class="documents-section">
                <div class="section-title">
                    <h2>Dokumen Terbaru</h2>
                    <p>Dokumen yang baru saja ditambahkan ke dalam sistem</p>
                </div>
                
                <div class="section-header">
                    <a href="<?= base_url('user/dokumen') ?>" class="view-all-btn">Lihat Semua</a>
                </div>
                
                <div class="documents-grid">
                    <?php if (isset($recent_docs) && !empty($recent_docs)): ?>
                        <?php foreach ($recent_docs as $doc): ?>
                            <div class="document-card">
                                <div class="doc-header">
                                    <div class="doc-type">
                                        <i class="fas fa-file-<?= $doc['file_type'] === 'pdf' ? 'pdf' : 'alt' ?>"></i>
                                        <span><?= strtoupper($doc['file_type']) ?></span>
                                    </div>
                                    <div class="doc-date">
                                        <?= date('d M Y', strtotime($doc['created_at'])) ?>
                                    </div>
                                </div>
                                <div class="doc-content">
                                    <h4 class="doc-title">
                                        <a href="<?= base_url('user/dokumen/view/' . $doc['id']) ?>">
                                            <?= esc($doc['judul']) ?>
                                        </a>
                                    </h4>
                                    <!-- Add file name section here -->
                                    <div class="doc-file-name">
                                        <i class="fas fa-file"></i>
                                        <span><?= esc($doc['file_name']) ?></span>
                                    </div>
                                    <p class="doc-description">
                                        <?= esc(substr($doc['deskripsi'] ?? '', 0, 100)) ?>
                                        <?= strlen($doc['deskripsi'] ?? '') > 100 ? '...' : '' ?>
                                    </p>
                                    <div class="doc-meta">
                                        <span class="doc-menu">
                                            <i class="fas fa-bars"></i>
                                            <?= esc($doc['nama_menu'] ?? 'Tidak ada menu') ?>
                                        </span>
                                        <span class="doc-category">  
                                            <i class="fas fa-folder"></i>
                                            <?= esc($doc['nama_kategori'] ?? 'Tidak ada kategori') ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="doc-actions">
                                    <a href="<?= base_url('files/' . $doc['file_path']) ?>" target="_blank" class="btn-action view">
                                        <i class="fas fa-eye"></i>
                                        Lihat
                                    </a>

                                    <a href="<?= base_url('user/dokumen/download/' . $doc['id']) ?>" class="btn-action download">
                                        <i class="fas fa-download"></i>
                                        Unduh
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-documents">
                            <i class="fas fa-folder-open"></i>
                            <h3>Belum ada dokumen</h3>
                            <p>Dokumen akan ditampilkan di sini setelah admin mengunggah</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer - Updated (untuk semua halaman) -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Layanan digital untuk pengelolaan data dan informasi yang mendukung transformasi digital instansi pemerintah, khususnya Kominfo, secara cepat, aman, dan terintegrasi.</h4>
                </div>
                
                <div class="footer-links">
                    <div class="footer-column">
                        <h5>Kategori</h5>
                        <ul>
                            <?php if (isset($kategori) && !empty($kategori)): ?>
                                <?php foreach (array_slice($kategori, 0, 3) as $kat): ?>
                                    <li><a href="<?= base_url('user/dokumen/kategori/' . $kat['id']) ?>"><?= esc($kat['nama_kategori']) ?></a></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><a href="<?= base_url('user/dokumen') ?>">Semua Dokumen</a></li>
                                <li><a href="<?= base_url('user/search') ?>">Pencarian</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h5>Layanan</h5>
                        <ul>
                            <li><a href="<?= base_url('user/dokumen') ?>">Browse Dokumen</a></li>
                            <li><a href="<?= base_url('user/search') ?>">Pencarian</a></li>
                            <li><a href="<?= base_url('user/dashboard') ?>">Dashboard</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h5>Informasi</h5>
                        <ul>
                            <li><a href="<?= base_url('user/dashboard') ?>">Dashboard</a></li>
                            <li><span style="color: #666;">Kontak: Diskominfotik KBB</span></li>
                            <li><span style="color: #666;">Bantuan: Hubungi Admin</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 SIDODIK - Sistem Informasi Dokumen Diskominfotik Kabupaten Bandung Barat</p>
            </div>
        </div>
    </footer>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/user.js') ?>"></script>
</body>
</html>