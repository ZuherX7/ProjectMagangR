<?php
helper('search'); // Load search helper
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Semua Dokumen' ?> - SIDODIK</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/user.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Add this to the existing styles */
        .dokumen-file-name {
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

        .dokumen-file-name i {
            color: #667eea;
        }

        /* Jika Anda ingin mengubah header dokumen juga */
        .dokumen-page-header {
            background: url('<?= base_url("assets/img/gedung2.png") ?>') center center;
            background-size: cover;
            background-repeat: no-repeat;
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
            position: relative;
        }

        .dokumen-page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4); /* Dark overlay */
            z-index: 1;
        }

        .dokumen-page-header .container {
            position: relative;
            z-index: 2;
        }

        .dokumen-page-header h1 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .search-form-container {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }

        .search-input-large {
            width: 100%;
            padding: 16px 60px 16px 24px;
            border: none;
            border-radius: 50px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            font-size: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .search-input-large:focus {
            outline: none;
            background: white;
            box-shadow: 0 12px 40px rgba(0,0,0,0.2);
        }

        .search-btn-large {
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

        .search-btn-large:hover {
            transform: translateY(-50%) scale(1.05);
        }

        .back-to-dashboard {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .back-to-dashboard:hover {
            color: white;
            text-decoration: none;
        }

        .dokumen-results-wrapper {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .dokumen-results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8f9fa;
        }

        .dokumen-results-info {
            color: #666;
            font-size: 16px;
        }

        .dokumen-results-count {
            font-weight: 600;
            color: #667eea;
        }

        .filter-info-highlight {
            font-weight: 600;
            color: #2c3e50;
        }

        /* INTEGRATED SEARCH RESULTS STYLING */
        .search-keyword-highlight {
            font-weight: 600;
            color: #2c3e50;
            background: rgba(102, 126, 234, 0.1);
            padding: 2px 6px;
            border-radius: 4px;
        }

        .search-active-indicator {
            background: #e8f4fd;
            border: 1px solid #667eea;
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #2c3e50;
        }

        .clear-search-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .clear-search-btn:hover {
            background: #5a6fd8;
        }

        .dokumen-filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .dokumen-filter-btn {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            color: #666;
        }

        .dokumen-filter-btn:hover {
            border-color: #667eea;
            background: #f0f2ff;
            color: #667eea;
            text-decoration: none;
        }

        .dokumen-filter-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .dokumen-sort-dropdown {
            padding: 8px 15px;
            border-radius: 20px;
            border: 2px solid #e9ecef;
            background: white;
            cursor: pointer;
            font-size: 14px;
        }

        .dokumen-sort-dropdown:focus {
            outline: none;
            border-color: #667eea;
        }

        .dokumen-document-grid {
            display: grid;
            gap: 20px;
        }

        .dokumen-document-card {
            background: white;
            border: 2px solid #f8f9fa;
            border-radius: 16px;
            padding: 25px;
            transition: all 0.3s ease;
        }

        .dokumen-document-card:hover {
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(102, 126, 234, 0.1);
        }

        /* SEARCH RESULT HIGHLIGHTING */
        .dokumen-document-card.search-result {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.02);
        }

        .dokumen-document-card.search-result::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px 16px 0 0;
        }

        .dokumen-doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .dokumen-file-type {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .dokumen-file-type.pdf {
            background: #fee;
            color: #dc3545;
        }

        .dokumen-file-type.docx,
        .dokumen-file-type.doc {
            background: #e3f2fd;
            color: #1976d2;
        }

        .dokumen-file-type.xlsx,
        .dokumen-file-type.xls {
            background: #e8f5e8;
            color: #388e3c;
        }

        .dokumen-file-type.pptx,
        .dokumen-file-type.ppt {
            background: #fff3e0;
            color: #f57c00;
        }

        .dokumen-doc-date {
            font-size: 12px;
            color: #999;
        }

        .dokumen-doc-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .dokumen-doc-title a {
            text-decoration: none;
            color: inherit;
        }

        .dokumen-doc-title a:hover {
            color: #667eea;
        }

        .dokumen-doc-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .dokumen-doc-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .dokumen-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #666;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 12px;
        }

        .dokumen-doc-actions {
            display: flex;
            gap: 10px;
        }

        .dokumen-action-btn {
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .dokumen-action-btn.view {
            background: #e3f2fd;
            color: #1976d2;
        }

        .dokumen-action-btn.download {
            background: #e8f5e8;
            color: #388e3c;
        }

        .dokumen-action-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: inherit;
        }

        .dokumen-no-results {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .dokumen-no-results i {
            font-size: 64px;
            margin-bottom: 20px;
            color: #ddd;
        }

        .dokumen-no-results h3 {
            margin-bottom: 10px;
            color: #333;
            font-size: 24px;
        }

        .dokumen-no-results p {
            margin-bottom: 10px;
            font-size: 16px;
        }

        /* Filter Buttons untuk Menu dan Kategori - UPDATED */
        .dokumen-category-filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 8px;
            padding: 12px 20px;
            background: #f8f9fa;
            border-radius: 15px;
        }
        
        /* Khusus untuk baris pertama (Semua) */
        .dokumen-category-filters:first-of-type {
            margin-bottom: 5px;
        }
        
        /* Khusus untuk baris terakhir sebelum file type filters */
        .dokumen-category-filters:last-of-type {
            margin-bottom: 15px;
        }

        .dokumen-category-filter {
            background: white;
            border: 2px solid #e9ecef;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            color: #666;
            transition: all 0.3s ease;
        }

        .dokumen-category-filter:hover {
            border-color: #667eea;
            background: #f0f2ff;
            color: #667eea;
            text-decoration: none;
        }

        .dokumen-category-filter.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        /* PERBAIKAN: Style untuk disabled/inactive filters */
        .dokumen-category-filter.inactive {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f8f9fa;
            color: #999;
        }

        .dokumen-category-filter.inactive:hover {
            border-color: #e9ecef;
            background: #f8f9fa;
            color: #999;
        }

        .filter-section-divider {
            width: 2px;
            background: #ddd;
            margin: 0 10px;
            align-self: stretch;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dokumen-results-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .dokumen-filters {
                justify-content: center;
            }

            .dokumen-doc-meta {
                justify-content: center;
            }

            .dokumen-doc-actions {
                justify-content: center;
            }

            .dokumen-category-filters {
                justify-content: center;
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

    <!-- Dokumen Header with Integrated Search -->
    <div class="dokumen-page-header">
        <div class="container">
            <a href="<?= base_url('user/dashboard') ?>" class="back-to-dashboard">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>
            
            <h1>
                <?php if (isset($filter_info)): ?>
                    <?= esc($filter_info) ?>
                <?php elseif (isset($keyword) && $keyword): ?>
                    Hasil Pencarian
                <?php else: ?>
                    Semua Dokumen
                <?php endif; ?>
            </h1>
            
            <!-- INTEGRATED SEARCH FORM -->
            <form action="<?= current_url() ?>" method="GET" id="dokumen-search-form">
                <!-- Preserve existing filters -->
                <?php if (isset($current_menu)): ?>
                    <input type="hidden" name="menu" value="<?= $current_menu ?>">
                <?php endif; ?>
                <?php if (isset($current_kategori)): ?>
                    <input type="hidden" name="kategori" value="<?= $current_kategori ?>">
                <?php endif; ?>
                
                <div class="search-form-container">
                    <input type="text" name="q" class="search-input-large" 
                           placeholder="Cari dokumen..." 
                           value="<?= esc($keyword ?? '') ?>">
                    <button type="submit" class="search-btn-large">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="dokumen-results-wrapper">
            <!-- SEARCH ACTIVE INDICATOR -->
            <?php if (isset($keyword) && $keyword): ?>
            <div class="search-active-indicator">
                <i class="fas fa-search"></i>
                <span>Menampilkan hasil pencarian untuk: <span class="search-keyword-highlight">"<?= esc($keyword) ?>"</span></span>
                <button class="clear-search-btn" onclick="clearSearch()">
                    <i class="fas fa-times"></i>
                    Hapus
                </button>
            </div>
            <?php endif; ?>

            <!-- Results Header -->
            <div class="dokumen-results-header">
                <div class="dokumen-results-info">
                    Menampilkan <span class="dokumen-results-count"><?= count($dokumen ?? []) ?></span> dokumen
                    <?php if (isset($keyword) && $keyword): ?>
                        untuk "<span class="search-keyword-highlight"><?= esc($keyword) ?></span>"
                    <?php elseif (isset($filter_info) && $filter_info !== 'Semua Dokumen'): ?>
                        dalam "<span class="filter-info-highlight"><?= esc($filter_info) ?></span>"
                    <?php endif; ?>
                </div>
                <div class="sort-section">
                    <select class="dokumen-sort-dropdown" onchange="sortDokumenResults(this.value)">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="views">Paling Dilihat</option>
                        <option value="downloads">Paling Diunduh</option>
                        <option value="title">Judul A-Z</option>
                        <?php if (isset($keyword) && $keyword): ?>
                        <option value="relevance">Relevansi</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Navigation Filters - 3 Rows Layout -->
            <?php if (isset($menu) && !empty($menu) || isset($kategori) && !empty($kategori)): ?>
            
            <!-- Baris 1: Tombol Semua saja -->
            <div class="dokumen-category-filters">
                <a href="<?= base_url('user/dokumen') . (isset($keyword) && $keyword ? '?q=' . urlencode($keyword) : '') ?>" 
                   class="dokumen-category-filter <?= !isset($current_menu) && !isset($current_kategori) ? 'active' : '' ?>">
                    <i class="fas fa-list"></i>
                    Semua Dokumen
                </a>
            </div>

            <!-- Baris 2: Menu Filters -->
            <?php if (isset($menu) && !empty($menu)): ?>
            <div class="dokumen-category-filters">
                <span style="color: #999; font-size: 12px; align-self: center; margin: 0 5px;">Menu:</span>
                <?php foreach ($menu as $m): ?>
                    <?php
                    $menuUrl = base_url('user/dokumen/menu/' . $m['id']);
                    if (isset($keyword) && $keyword) {
                        $menuUrl .= '?q=' . urlencode($keyword);
                    }
                    ?>
                    <a href="<?= $menuUrl ?>" 
                       class="dokumen-category-filter <?= isset($current_menu) && $current_menu == $m['id'] ? 'active' : '' ?>">
                        <?php if (!empty($m['icon'])): ?>
                            <i class="fas fa-<?= esc($m['icon']) ?>"></i>
                        <?php endif; ?>
                        <?= esc($m['nama_menu']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Baris 3: Category Filters -->
            <?php if (isset($kategori) && !empty($kategori)): ?>
            <div class="dokumen-category-filters">
                <span style="color: #999; font-size: 12px; align-self: center; margin: 0 5px;">Kategori:</span>
                
                <?php 
                // Logic untuk menampilkan kategori
                if (isset($current_menu) && !isset($current_kategori)):
                    // Mode: Menu dipilih, tampilkan kategori dari menu ini
                    foreach ($kategori as $k): 
                        $katUrl = base_url('user/dokumen/kategori/' . $k['id']);
                        if (isset($keyword) && $keyword) {
                            $katUrl .= '?q=' . urlencode($keyword);
                        }
                        ?>
                        <a href="<?= $katUrl ?>" class="dokumen-category-filter">
                            <i class="fas fa-folder"></i>
                            <?= esc($k['nama_kategori']) ?>
                        </a>
                    <?php endforeach;
                elseif (isset($current_kategori)):
                    // Mode: Kategori dipilih, tampilkan kategori dari menu yang sama
                    foreach ($kategori as $k): 
                        $katUrl = base_url('user/dokumen/kategori/' . $k['id']);
                        if (isset($keyword) && $keyword) {
                            $katUrl .= '?q=' . urlencode($keyword);
                        }
                        ?>
                        <a href="<?= $katUrl ?>" 
                           class="dokumen-category-filter <?= isset($current_kategori) && $current_kategori == $k['id'] ? 'active' : '' ?>">
                            <i class="fas fa-folder"></i>
                            <?= esc($k['nama_kategori']) ?>
                        </a>
                    <?php endforeach;
                else:
                    // Mode: Semua dokumen, tampilkan beberapa kategori pertama saja
                    foreach (array_slice($kategori, 0, 5) as $k): 
                        $katUrl = base_url('user/dokumen/kategori/' . $k['id']);
                        if (isset($keyword) && $keyword) {
                            $katUrl .= '?q=' . urlencode($keyword);
                        }
                        ?>
                        <a href="<?= $katUrl ?>" class="dokumen-category-filter">
                            <i class="fas fa-folder"></i>
                            <?= esc($k['nama_kategori']) ?>
                        </a>
                    <?php endforeach;
                    
                    // Tampilkan "Lihat Semua" jika kategori lebih dari 5
                    if (count($kategori) > 5): ?>
                        <a href="#" onclick="toggleAllCategories()" 
                           class="dokumen-category-filter" id="show-all-categories">
                            <i class="fas fa-ellipsis-h"></i>
                            Lihat Semua Kategori
                        </a>
                    <?php endif;
                endif; ?>
            </div>
            <?php endif; ?>
            
            <?php endif; ?>

            <!-- File Type Filter Buttons -->
            <div class="dokumen-filters">
                <button class="dokumen-filter-btn active" onclick="filterDokumenResults('all')">
                    <i class="fas fa-filter"></i>
                    Semua Tipe
                </button>
                <button class="dokumen-filter-btn" onclick="filterDokumenResults('pdf')">
                    <i class="fas fa-file-pdf"></i>
                    PDF
                </button>
                <button class="dokumen-filter-btn" onclick="filterDokumenResults('docx')">
                    <i class="fas fa-file-word"></i>
                    Word
                </button>
                <button class="dokumen-filter-btn" onclick="filterDokumenResults('xlsx')">
                    <i class="fas fa-file-excel"></i>
                    Excel
                </button>
                <button class="dokumen-filter-btn" onclick="filterDokumenResults('pptx')">
                    <i class="fas fa-file-powerpoint"></i>
                    PowerPoint
                </button>
            </div>

            <!-- Dokumen Results Grid -->
            <div class="dokumen-document-grid">
                <?php if (isset($dokumen) && !empty($dokumen)): ?>
                    <?php foreach ($dokumen as $doc): ?>
                        <div class="dokumen-document-card <?= isset($keyword) && $keyword ? 'search-result' : '' ?>" 
                             data-file-type="<?= esc($doc['file_type']) ?>" 
                             data-views="<?= $doc['views'] ?? 0 ?>" 
                             data-downloads="<?= $doc['downloads'] ?? 0 ?>"
                             data-date="<?= esc($doc['created_at']) ?>"
                             data-title="<?= esc($doc['judul']) ?>"
                             data-relevance="<?= isset($doc['search_score']) ? $doc['search_score'] : 0 ?>">
                            
                            <div class="dokumen-doc-header">
                                <div class="dokumen-file-type <?= esc($doc['file_type']) ?>">
                                    <?php
                                    $fileIcons = [
                                        'pdf' => 'fa-file-pdf',
                                        'doc' => 'fa-file-word',
                                        'docx' => 'fa-file-word',
                                        'xls' => 'fa-file-excel',
                                        'xlsx' => 'fa-file-excel',
                                        'ppt' => 'fa-file-powerpoint',
                                        'pptx' => 'fa-file-powerpoint'
                                    ];
                                    $iconClass = $fileIcons[$doc['file_type']] ?? 'fa-file';
                                    ?>
                                    <i class="fas <?= $iconClass ?>"></i>
                                    <?= strtoupper(esc($doc['file_type'])) ?>
                                </div>
                                <div class="dokumen-doc-date">
                                    <?= date('d M Y', strtotime($doc['created_at'])) ?>
                                </div>
                            </div>

                            <h4 class="dokumen-doc-title">
                                <a href="<?= base_url('user/dokumen/view/' . $doc['id']) ?>">
                                    <?php 
                                    // Highlight search terms in title
                                    if (isset($keyword) && $keyword) {
                                        echo highlightSearchTerm(esc($doc['judul']), $keyword);
                                    } else {
                                        echo esc($doc['judul']);
                                    }
                                    ?>
                                </a>
                            </h4>

                            <!-- Add file name section here -->
                            <div class="dokumen-file-name">
                                <i class="fas fa-file"></i>
                                <span>
                                    <?php 
                                    // Highlight search terms in file name
                                    if (isset($keyword) && $keyword) {
                                        echo highlightSearchTerm(esc($doc['file_name']), $keyword);
                                    } else {
                                        echo esc($doc['file_name']);
                                    }
                                    ?>
                                </span>
                            </div>

                            <?php if (!empty($doc['deskripsi'])): ?>
                                <p class="dokumen-doc-description">
                                    <?php 
                                    $description = esc(substr($doc['deskripsi'], 0, 150));
                                    // Highlight search terms in description
                                    if (isset($keyword) && $keyword) {
                                        echo highlightSearchTerm($description, $keyword);
                                    } else {
                                        echo $description;
                                    }
                                    ?>
                                    <?= strlen($doc['deskripsi']) > 150 ? '...' : '' ?>
                                </p>
                            <?php endif; ?>

                            <div class="dokumen-doc-meta">
                                <div class="dokumen-meta-item">
                                    <i class="fas fa-bars"></i>
                                    <span><?= esc($doc['nama_menu'] ?? 'Tidak ada menu') ?></span>
                                </div>
                                <div class="dokumen-meta-item">
                                    <i class="fas fa-folder"></i>
                                    <span><?= esc($doc['nama_kategori'] ?? 'Tidak ada kategori') ?></span>
                                </div>
                                <div class="dokumen-meta-item">
                                    <i class="fas fa-eye"></i>
                                    <span><?= number_format($doc['views'] ?? 0) ?> views</span>
                                </div>
                                <div class="dokumen-meta-item">
                                    <i class="fas fa-download"></i>
                                    <span><?= number_format($doc['downloads'] ?? 0) ?> downloads</span>
                                </div>
                                <?php if (isset($keyword) && $keyword && isset($doc['search_score'])): ?>
                                <div class="dokumen-meta-item">
                                    <i class="fas fa-star"></i>
                                    <span>Relevansi: <?= round($doc['search_score'] * 100) ?>%</span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="dokumen-doc-actions">
                                <a href="<?= base_url('files/' . $doc['file_path']) ?>" target="_blank" class="dokumen-action-btn view">
                                    <i class="fas fa-eye"></i>
                                    Lihat
                                </a>
                                <a href="<?= base_url('user/dokumen/download/' . $doc['id']) ?>" class="dokumen-action-btn download">
                                    <i class="fas fa-download"></i>
                                    Unduh
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="dokumen-no-results">
                        <?php if (isset($keyword) && $keyword): ?>
                            <i class="fas fa-search"></i>
                            <h3>Tidak ada dokumen ditemukan</h3>
                            <p>Tidak ada dokumen yang cocok dengan kata kunci "<strong><?= esc($keyword) ?></strong>"</p>
                            <p>Coba gunakan kata kunci yang berbeda atau periksa ejaan</p>
                        <?php else: ?>
                            <i class="fas fa-folder-open"></i>
                            <h3>Belum ada dokumen</h3>
                            <p>Belum ada dokumen yang tersedia untuk kategori ini</p>
                        <?php endif; ?>
                        <p><a href="<?= base_url('user/dashboard') ?>">Kembali ke Dashboard</a> atau pilih kategori lain</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
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
                                <li><a href="#">Surat Keputusan</a></li>
                                <li><a href="#">Laporan</a></li>
                                <li><a href="#">Panduan</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h5>Layanan</h5>
                        <ul>
                            <li><a href="#">Download</a></li>
                            <li><a href="#">Print</a></li>
                            <li><a href="#">View Online</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h5>Informasi</h5>
                        <ul>
                            <li><a href="<?= base_url('/about') ?>">Tentang Kami</a></li>
                            <li><a href="#">Kontak</a></li>
                            <li><a href="#">Bantuan</a></li>
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
    <script>
        function filterDokumenResults(fileType) {
            // Remove active class from all filter buttons
            document.querySelectorAll('.dokumen-filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to clicked button
            event.target.classList.add('active');
            
            const documentCards = document.querySelectorAll('.dokumen-document-card');
            let visibleCount = 0;
            
            documentCards.forEach(card => {
                const cardType = card.getAttribute('data-file-type');
                if (fileType === 'all' || cardType === fileType) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update results count
            document.querySelector('.dokumen-results-count').textContent = visibleCount;
            
            // Show/hide no results message if needed
            const noResults = document.querySelector('.dokumen-no-results');
            if (noResults && visibleCount === 0 && fileType !== 'all') {
                // Create temporary no results for filter
                if (!document.querySelector('.filter-no-results')) {
                    const filterNoResults = document.createElement('div');
                    filterNoResults.className = 'dokumen-no-results filter-no-results';
                    filterNoResults.innerHTML = `
                        <i class="fas fa-filter"></i>
                        <h3>Tidak ada dokumen dengan tipe ${fileType.toUpperCase()}</h3>
                        <p>Coba pilih filter lain atau hapus filter untuk melihat semua dokumen</p>
                    `;
                    document.querySelector('.dokumen-document-grid').appendChild(filterNoResults);
                }
                document.querySelector('.filter-no-results').style.display = 'block';
            } else if (document.querySelector('.filter-no-results')) {
                document.querySelector('.filter-no-results').style.display = 'none';
            }
        }
        
        function sortDokumenResults(sortBy) {
            const grid = document.querySelector('.dokumen-document-grid');
            const cards = Array.from(grid.querySelectorAll('.dokumen-document-card'));
            
            cards.sort((a, b) => {
                switch(sortBy) {
                    case 'newest':
                        return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
                    case 'oldest':
                        return new Date(a.getAttribute('data-date')) - new Date(b.getAttribute('data-date'));
                    case 'views':
                        return parseInt(b.getAttribute('data-views')) - parseInt(a.getAttribute('data-views'));
                    case 'downloads':
                        return parseInt(b.getAttribute('data-downloads')) - parseInt(a.getAttribute('data-downloads'));
                    case 'title':
                        return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'));
                    case 'relevance':
                        return parseFloat(b.getAttribute('data-relevance')) - parseFloat(a.getAttribute('data-relevance'));
                    default:
                        return 0;
                }
            });
            
            // Remove all cards
            cards.forEach(card => card.remove());
            
            // Re-append sorted cards
            cards.forEach(card => {
                if (card.classList.contains('dokumen-document-card')) {
                    grid.appendChild(card);
                }
            });
        }

        // Clear search function
        function clearSearch() {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.delete('q');
            window.location.href = currentUrl.toString();
        }

        // Function to toggle all categories
        function toggleAllCategories() {
            console.log('Toggle all categories clicked');
            // Implementation can be added here to show/hide additional categories
        }
    </script>

    <?php
    // PHP Helper function for highlighting search terms
    if (!function_exists('highlightSearchTerm')) {
        function highlightSearchTerm($text, $searchTerm) {
            if (empty($searchTerm)) {
                return $text;
            }
            
            // Split search term into individual words
            $searchWords = explode(' ', trim($searchTerm));
            
            foreach ($searchWords as $word) {
                if (strlen($word) > 2) { // Only highlight words longer than 2 characters
                    $text = preg_replace('/(' . preg_quote($word, '/') . ')/i', 
                                       '<mark style="background: rgba(102, 126, 234, 0.2); padding: 1px 3px; border-radius: 3px;">$1</mark>', 
                                       $text);
                }
            }
            
            return $text;
        }
    }
    ?>
</body>
</html>