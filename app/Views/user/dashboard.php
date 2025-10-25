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
        /* Hero Section*/
        .hero-section {
            background: url('<?= base_url("assets/img/gedung2.png") ?>') center center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
            padding: 80px 0 100px 0;
            position: relative;
            overflow: hidden;
            min-height: 85vh;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .hero-section h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 18px;
            text-shadow: 0 4px 12px rgba(0,0,0,0.2);
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .hero-section .lead {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 45px;
            line-height: 1.6;
            font-weight: 400;
        }

        .search-highlight {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(15px);
            padding: 35px;
            border-radius: 20px;
            margin-bottom: 25px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .search-highlight h3 {
            font-size: 18px;
            margin-bottom: 22px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .hero-search-wrapper {
            position: relative;
            max-width: 550px;
            margin: 0 auto;
        }

        .hero-search-input {
            width: 100%;
            padding: 16px 60px 16px 24px;
            border: none;
            border-radius: 30px;
            background: white;
            font-size: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            transition: all 0.3s ease;
        }

        .hero-search-input:focus {
            outline: none;
            box-shadow: 0 12px 35px rgba(0,0,0,0.18);
            transform: translateY(-2px);
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
            box-shadow: 0 4px 12px rgba(74, 111, 165, 0.3);
        }

        .hero-search-btn:hover {
            transform: translateY(-50%) scale(1.05);
            box-shadow: 0 6px 16px rgba(74, 111, 165, 0.4);
        }

        .user-greeting-hero {
            background: rgba(255,255,255,0.18);
            backdrop-filter: blur(10px);
            padding: 12px 24px;
            border-radius: 25px;
            display: inline-block;
            font-size: 16px;
            font-weight: 500;
            margin-top: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Stats Section - Cleaned & Consistent */
        .stats-section {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 0;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .stat-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            color: white;
            font-size: 26px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Menu Section - Clean Layout */
        .menu-section {
            padding: 60px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 12px;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .section-title p {
            font-size: 18px;
            color: #7f8c8d;
            max-width: 650px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .menu-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
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
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 30px;
            text-decoration: none;
            color: inherit;
            gap: 22px;
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

        .menu-info {
            flex: 1;
        }

        .menu-info h3 {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .doc-count {
            color: #667eea;
            font-size: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .doc-count i {
            font-size: 14px;
        }

        /* Documents Section - Consistent Styling */
        .documents-section {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .document-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
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
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        /* Doc File Name - Clean Design */
        .doc-file-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #7f8c8d;
            background: #f8f9fa;
            padding: 8px 14px;
            border-radius: 10px;
            margin-bottom: 12px;
            width: fit-content;
        }

        .doc-file-name i {
            color: #667eea;
            font-size: 14px;
        }

        /* Tags - Consistent Style */
        .doc-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 14px;
        }

        .tag-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 12px;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .tag-pill:hover {
            background: #667eea;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .tag-pill i {
            font-size: 9px;
        }

        /* Responsive - Clean Breakpoints */
        @media (max-width: 768px) {
            .hero-section {
                padding: 50px 0 70px;
                min-height: auto;
            }
            
            .hero-section h1 {
                font-size: 32px;
            }
            
            .hero-section .lead {
                font-size: 18px;
            }
            
            .search-highlight {
                padding: 25px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
            
            .section-title h2 {
                font-size: 28px;
            }
            
            .section-title p {
                font-size: 16px;
            }

            .menu-link {
                padding: 24px;
            }

            .menu-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }

            .menu-info h3 {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .hero-content {
                padding: 40px 15px;
            }
            
            .hero-section h1 {
                font-size: 28px;
            }

            .hero-section .lead {
                font-size: 16px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .menu-link {
                padding: 20px;
                gap: 16px;
            }
            
            .menu-icon {
                width: 56px;
                height: 56px;
                font-size: 22px;
            }

            .menu-info h3 {
                font-size: 16px;
            }

            .stat-icon {
                width: 56px;
                height: 56px;
                font-size: 22px;
            }

            .stat-number {
                font-size: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation - Same structure as landing page -->
    <nav class="navbar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 15px 0; position: fixed; top: 0; width: 100%; z-index: 1000;">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <a href="<?= base_url('/') ?>" class="navbar-brand" style="display: flex; align-items: center; gap: 12px; font-size: 24px; font-weight: 700; color: white; text-decoration: none;">
                <img src="<?= base_url('assets/img/kbb.png') ?>" alt="Logo KBB" style="width: 40px; height: 40px; object-fit: contain;">
                SIDODIK
            </a>
            <ul class="navbar-nav" style="display: flex; list-style: none; gap: 25px; align-items: center; margin: 0; padding: 0;">
                <li><a href="<?= base_url('/') ?>" style="color: white; text-decoration: none; font-weight: 500; padding: 8px 16px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='transparent'">Beranda</a></li>
                <li><a href="<?= base_url('user/dokumen') ?>" style="color: white; text-decoration: none; font-weight: 500; padding: 8px 16px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='transparent'">Dokumen</a></li>
                <li><a href="<?= base_url('/#pengaduan') ?>" style="color: white; text-decoration: none; font-weight: 500; padding: 8px 16px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='transparent'">Pengaduan</a></li>
                <li><a href="<?= base_url('user/dashboard') ?>" style="color: white; text-decoration: none; font-weight: 500; padding: 8px 16px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='transparent'">Dashboard User</a></li>
            </ul>
            <div style="display: flex; align-items: center; gap: 15px;">
                <span style="color: white; font-size: 14px;">Selamat datang, <strong><?= esc($user['nama_lengkap']) ?></strong></span>
                <a href="<?= base_url('auth/logout') ?>" style="background: #dc3545; color: white; padding: 8px 16px; border-radius: 20px; text-decoration: none; font-weight: 500; font-size: 14px; transition: all 0.3s ease;" onmouseover="this.style.background='#c82333'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#dc3545'; this.style.transform='translateY(0)'">Logout</a>
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
                        <div class="stat-label">Total Kategori</div>
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
                                    <?php if (!empty($doc['tags'])): ?>
                                        <div class="doc-tags">
                                            <?php 
                                            $tags = array_filter(array_map('trim', explode(',', $doc['tags'])));
                                            foreach ($tags as $tag): 
                                            ?>
                                                <a href="<?= base_url('/dokumen-publik?q=' . urlencode($tag)) ?>" class="tag-pill">
                                                    <i class="fas fa-tag"></i>
                                                    <?= esc($tag) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
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
                    <h4>Sistem Informasi Dokumen Diskominfotik Kabupaten Bandung Barat. Menyediakan akses mudah dan transparan untuk semua dokumen resmi pemerintahan dengan standar keamanan tinggi.</h4>
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