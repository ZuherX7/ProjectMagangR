<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengaduan - SIDODIK</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 22px;
            color: white;
        }

        .stat-pending { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .stat-proses { background: linear-gradient(135deg, #3498db, #2980b9); }
        .stat-selesai { background: linear-gradient(135deg, #27ae60, #229954); }
        .stat-ditolak { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .stat-urgent { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 500;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .form-group {
            margin: 0;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-decoration: none;
            color: white;
        }

        /* Pengaduan Table */
        .pengaduan-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f8f9fa;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th,
        .table td {
            padding: 15px 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
            position: sticky;
            top: 0;
        }

        .table td {
            font-size: 14px;
            color: #5a6c7d;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-proses {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .status-selesai {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-ditolak {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f1b0b7;
        }

        /* Urgency Badges */
        .urgency-badge {
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .urgency-rendah { background: #d4edda; color: #155724; }
        .urgency-sedang { background: #fff3cd; color: #856404; }
        .urgency-tinggi { background: #f8d7da; color: #721c24; }
        .urgency-sangat_tinggi { background: #d4edda; color: #721c24; background: #721c24; color: white; }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 8px;
        }

        .btn-info { background: #17a2b8; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-danger { background: #dc3545; color: white; }

        /* Ticket Info */
        .ticket-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .ticket-number {
            font-family: monospace;
            font-weight: 700;
            color: #667eea;
            font-size: 13px;
        }

        .requester-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .requester-email {
            font-size: 12px;
            color: #7f8c8d;
        }

        /* Document Title */
        .document-title {
            font-weight: 600;
            color: #2c3e50;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 14px;
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .section-header {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }
            
            .table-responsive {
                font-size: 12px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }
        }

        .badge-primary {
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-secondary {
            background: #6c757d;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .requester-nip {
            font-size: 11px;
            color: #667eea;
            font-weight: 600;
            margin-top: 2px;
            background: rgba(102, 126, 234, 0.1);
            padding: 2px 6px;
            border-radius: 8px;
            display: inline-block;
        }

        /* Update existing badge styles */
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-light {
            background: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
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
                    <li class="nav-item">
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
                    
                    <li class="nav-item active">
                        <a href="<?= base_url('admin/pengaduan') ?>" class="nav-link">
                            <i class="fas fa-headset"></i>
                            <span>Kelola Pengaduan</span>
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
                        <h1>Kelola Pengaduan Dokumen</h1>
                        <p>Kelola permintaan dokumen dari masyarakat</p>
                    </div>
                </div>
                <div class="header-right">
                    <span class="breadcrumb">Admin / Pengaduan</span>
                </div>
            </header>

            <!-- Content -->
            <div class="content-wrapper">
                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon stat-pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-number"><?= $stats['pending'] ?? 0 ?></div>
                        <div class="stat-label">Menunggu</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon stat-proses">
                            <i class="fas fa-spinner"></i>
                        </div>
                        <div class="stat-number"><?= $stats['proses'] ?? 0 ?></div>
                        <div class="stat-label">Diproses</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon stat-selesai">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="stat-number"><?= $stats['selesai'] ?? 0 ?></div>
                        <div class="stat-label">Selesai</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon stat-ditolak">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="stat-number"><?= $stats['ditolak'] ?? 0 ?></div>
                        <div class="stat-label">Ditolak</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon stat-urgent">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <div class="stat-number"><?= $stats['urgent'] ?? 0 ?></div>
                        <div class="stat-label">Mendesak</div>
                    </div>
                </div>

                <!-- UPDATE filter section di pengaduan.php -->
                <div class="filter-section">
                    <form method="GET" action="<?= current_url() ?>">
                        <div class="filter-row">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="pending" <?= ($filter['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Menunggu</option>
                                    <option value="proses" <?= ($filter['status'] ?? '') === 'proses' ? 'selected' : '' ?>>Diproses</option>
                                    <option value="selesai" <?= ($filter['status'] ?? '') === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                    <option value="ditolak" <?= ($filter['status'] ?? '') === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Jenis Pemohon</label>
                                <select name="jenis_pemohon" class="form-control">
                                    <option value="">Semua Jenis</option>
                                    <option value="publik" <?= ($filter['jenis_pemohon'] ?? '') === 'publik' ? 'selected' : '' ?>>User Publik</option>
                                    <option value="asn" <?= ($filter['jenis_pemohon'] ?? '') === 'asn' ? 'selected' : '' ?>>ASN</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Urgensi</label>
                                <select name="urgency" class="form-control">
                                    <option value="">Semua Urgensi</option>
                                    <option value="rendah" <?= ($filter['urgency'] ?? '') === 'rendah' ? 'selected' : '' ?>>Rendah</option>
                                    <option value="sedang" <?= ($filter['urgency'] ?? '') === 'sedang' ? 'selected' : '' ?>>Sedang</option>
                                    <option value="tinggi" <?= ($filter['urgency'] ?? '') === 'tinggi' ? 'selected' : '' ?>>Tinggi</option>
                                    <option value="sangat_tinggi" <?= ($filter['urgency'] ?? '') === 'sangat_tinggi' ? 'selected' : '' ?>>Sangat Tinggi</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    <option value="surat" <?= ($filter['kategori'] ?? '') === 'surat' ? 'selected' : '' ?>>Surat</option>
                                    <option value="laporan" <?= ($filter['kategori'] ?? '') === 'laporan' ? 'selected' : '' ?>>Laporan</option>
                                    <option value="formulir" <?= ($filter['formulir'] ?? '') === 'formulir' ? 'selected' : '' ?>>Formulir</option>
                                    <option value="panduan" <?= ($filter['panduan'] ?? '') === 'panduan' ? 'selected' : '' ?>>Panduan</option>
                                    <option value="lainnya" <?= ($filter['lainnya'] ?? '') === 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Pencarian</label>
                                <input type="text" name="search" class="form-control" 
                                    placeholder="Cari nama, email, NIP, atau tiket..." 
                                    value="<?= esc($filter['search'] ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                    Filter
                                </button>
                                <a href="<?= base_url('admin/pengaduan') ?>" class="btn btn-secondary">
                                    <i class="fas fa-refresh"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Pengaduan Table -->
                <div class="pengaduan-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-headset"></i>
                            Daftar Pengaduan
                        </h3>
                        <div class="section-actions">
                            <span class="text-muted">Total: <?= count($pengaduan) ?> pengaduan</span>
                        </div>
                    </div>

                    <?php if (!empty($pengaduan)): ?>
                        <div class="table-responsive">
                            <!-- UPDATE bagian table pengaduan di pengaduan.php -->
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tiket & Pemohon</th>
                                        <th>Dokumen Diminta</th>
                                        <th>Kategori</th>
                                        <th>Jenis Pemohon</th>
                                        <th>Urgensi</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pengaduan as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="ticket-info">
                                                    <div class="ticket-number"><?= esc($item['ticket_number']) ?></div>
                                                    <div class="requester-name"><?= esc($item['nama']) ?></div>
                                                    <div class="requester-email"><?= esc($item['email']) ?></div>
                                                    <?php if (!empty($item['nip'])): ?>
                                                        <div class="requester-nip" style="font-size: 11px; color: #667eea; font-weight: 600;">
                                                            NIP: <?= esc($item['nip']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="document-title" title="<?= esc($item['judul_dokumen']) ?>">
                                                    <?= esc($item['judul_dokumen']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-light">
                                                    <?php
                                                    $kategoriLabels = [
                                                        'surat' => 'Surat',
                                                        'laporan' => 'Laporan',
                                                        'formulir' => 'Formulir',
                                                        'panduan' => 'Panduan',
                                                        'lainnya' => 'Lainnya'
                                                    ];
                                                    echo $kategoriLabels[$item['kategori_permintaan']] ?? 'Unknown';
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?= $item['jenis_pemohon'] == 'asn' ? 'badge-primary' : 'badge-secondary' ?>">
                                                    <?php
                                                    $jenisPemohonLabels = [
                                                        'publik' => 'Publik',
                                                        'asn' => 'ASN'
                                                    ];
                                                    echo $jenisPemohonLabels[$item['jenis_pemohon']] ?? 'Unknown';
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="urgency-badge urgency-<?= $item['urgency'] ?>">
                                                    <?php
                                                    $urgencyLabels = [
                                                        'rendah' => 'Rendah',
                                                        'sedang' => 'Sedang',
                                                        'tinggi' => 'Tinggi',
                                                        'sangat_tinggi' => 'Sangat Tinggi'
                                                    ];
                                                    echo $urgencyLabels[$item['urgency']] ?? 'Unknown';
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?= $item['status'] ?>">
                                                    <?php
                                                    $statusLabels = [
                                                        'pending' => 'Menunggu',
                                                        'proses' => 'Diproses',
                                                        'selesai' => 'Selesai',
                                                        'ditolak' => 'Ditolak'
                                                    ];
                                                    echo $statusLabels[$item['status']] ?? 'Unknown';
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div style="font-size: 12px;">
                                                    <div><?= date('d M Y', strtotime($item['created_at'])) ?></div>
                                                    <div class="text-muted"><?= date('H:i', strtotime($item['created_at'])) ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-info btn-sm" onclick="viewDetail(<?= $item['id'] ?>)" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    
                                                    <?php if ($item['status'] !== 'selesai'): ?>
                                                        <button class="btn btn-success btn-sm" onclick="updateStatus(<?= $item['id'] ?>, 'proses')" title="Proses">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                        
                                                        <button class="btn btn-warning btn-sm" onclick="linkDocument(<?= $item['id'] ?>)" title="Hubungkan Dokumen">
                                                            <i class="fas fa-link"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <button class="btn btn-danger btn-sm" onclick="deletePengaduan(<?= $item['id'] ?>)" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>Belum Ada Pengaduan</h3>
                            <p>Pengaduan dari masyarakat akan muncul di sini</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Rekap Laporan Section -->
                <div class="pengaduan-section" style="margin-top: 30px;">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-chart-bar"></i>
                            Rekap Laporan Pengaduan
                        </h3>
                    </div>

                    <!-- Filter Rekap -->
                    <div class="filter-section" style="margin-bottom: 25px;">
                        <form id="rekapForm" method="GET" action="<?= base_url('admin/pengaduan/rekap') ?>">
                            <div class="filter-row">
                                <div class="form-group">
                                    <label class="form-label">Periode</label>
                                    <select name="periode" id="periodePicker" class="form-control" onchange="toggleCustomDate()">
                                        <option value="minggu_ini">Minggu Ini</option>
                                        <option value="bulan_ini">Bulan Ini</option>
                                        <option value="tahun_ini">Tahun Ini</option>
                                        <option value="custom">Custom (Pilih Tanggal)</option>
                                    </select>
                                </div>

                                <div class="form-group" id="customDateStart" style="display: none;">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="start_date" class="form-control" id="startDate">
                                </div>

                                <div class="form-group" id="customDateEnd" style="display: none;">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" name="end_date" class="form-control" id="endDate">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-primary" onclick="showRekap()">
                                        <i class="fas fa-search"></i>
                                        Lihat Rekap
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Rekap Results (Hidden by default) -->
                    <div id="rekapResults" style="display: none;">
                        <!-- Loading State -->
                        <div id="rekapLoading" style="text-align: center; padding: 40px;">
                            <div class="loading-spinner" style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                            <p style="margin-top: 15px; color: #5a6c7d;">Memuat rekap data...</p>
                        </div>

                        <!-- Rekap Content -->
                        <div id="rekapContent" style="display: none;">
                            <!-- Summary Cards -->
                            <div class="stats-grid" style="margin-bottom: 25px;">
                                <div class="stat-card">
                                    <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                        <i class="fas fa-list"></i>
                                    </div>
                                    <div class="stat-number" id="rekapTotal">0</div>
                                    <div class="stat-label">Total Pengaduan</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon stat-pending">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stat-number" id="rekapPending">0</div>
                                    <div class="stat-label">Menunggu</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon stat-proses">
                                        <i class="fas fa-spinner"></i>
                                    </div>
                                    <div class="stat-number" id="rekapProses">0</div>
                                    <div class="stat-label">Diproses</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon stat-selesai">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="stat-number" id="rekapSelesai">0</div>
                                    <div class="stat-label">Selesai</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon stat-ditolak">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div class="stat-number" id="rekapDitolak">0</div>
                                    <div class="stat-label">Ditolak</div>
                                </div>
                            </div>

                            <!-- Detail Tables -->
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px;">
                                <!-- By Kategori -->
                                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                                    <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 15px; color: #2c3e50;">
                                        <i class="fas fa-tags"></i> Per Kategori
                                    </h4>
                                    <div id="rekapKategori"></div>
                                </div>

                                <!-- By Jenis Pemohon -->
                                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                                    <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 15px; color: #2c3e50;">
                                        <i class="fas fa-users"></i> Per Jenis Pemohon
                                    </h4>
                                    <div id="rekapJenis"></div>
                                </div>

                                <!-- By Urgency -->
                                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                                    <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 15px; color: #2c3e50;">
                                        <i class="fas fa-exclamation-triangle"></i> Per Urgensi
                                    </h4>
                                    <div id="rekapUrgency"></div>
                                </div>
                            </div>

                            <!-- Response Time -->
                            <div style="background: #e3f2fd; padding: 20px; border-radius: 10px; margin-bottom: 25px; text-align: center;">
                                <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 10px; color: #1976d2;">
                                    <i class="fas fa-clock"></i> Rata-rata Waktu Respon
                                </h4>
                                <div style="font-size: 36px; font-weight: 700; color: #1976d2;">
                                    <span id="rekapAvgResponse">0</span> <span style="font-size: 18px;">hari</span>
                                </div>
                            </div>

                            <!-- Export Buttons -->
                            <div style="text-align: center; padding: 20px; border-top: 2px solid #e9ecef;">
                                <p style="color: #5a6c7d; margin-bottom: 15px; font-weight: 500;">
                                    <i class="fas fa-download"></i> Export Laporan Rekap
                                </p>
                                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                                    <button type="button" class="btn btn-success" onclick="exportRekap('excel')">
                                        <i class="fas fa-file-excel"></i>
                                        Export ke Excel
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="exportRekap('pdf')">
                                        <i class="fas fa-file-pdf"></i>
                                        Export ke PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Detail Pengaduan</h3>
                <button class="modal-close" onclick="closeModal('detailModal')">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="statusModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Status Pengaduan</h3>
                <button class="modal-close" onclick="closeModal('statusModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="statusForm" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pending">Menunggu</option>
                            <option value="proses">Diproses</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Respon Admin (Opsional)</label>
                        <textarea name="admin_response" class="form-control" rows="4" 
                                  placeholder="Berikan keterangan atau respon untuk pemohon..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Link Document Modal -->
    <div id="linkModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Hubungkan Dokumen</h3>
                <button class="modal-close" onclick="closeModal('linkModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="linkForm" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label class="form-label">Pilih Dokumen</label>
                        <select name="dokumen_id" class="form-control" required>
                            <option value="">-- Pilih Dokumen --</option>
                            <?php foreach ($dokumen_list as $doc): ?>
                                <option value="<?= $doc['id'] ?>"><?= esc($doc['judul']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Setelah menghubungkan dokumen, status akan otomatis berubah menjadi "Selesai"
                    </p>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Hubungkan Dokumen</button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal('linkModal')">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 18px;
            color: #2c3e50;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
            margin-left: auto;
        }

        .modal-body {
            padding: 25px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-light {
            background: #f8f9fa;
            color: #495057;
        }

        .text-muted {
            color: #6c757d;
            font-size: 13px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
    </style>

    <script>
        // Modal functions
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // View detail
        function viewDetail(id) {
            fetch(`<?= base_url('admin/pengaduan/detail') ?>/${id}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modalBody').innerHTML = data;
                    showModal('detailModal');
                })
                .catch(error => {
                    alert('Gagal memuat detail pengaduan');
                });
        }

        // Update status
        function updateStatus(id, defaultStatus = '') {
            const form = document.getElementById('statusForm');
            form.action = `<?= base_url('admin/pengaduan/update-status') ?>/${id}`;
            
            if (defaultStatus) {
                form.querySelector('[name="status"]').value = defaultStatus;
            }
            
            showModal('statusModal');
        }

        // Link document
        function linkDocument(id) {
            const form = document.getElementById('linkForm');
            form.action = `<?= base_url('admin/pengaduan/link-dokumen') ?>/${id}`;
            showModal('linkModal');
        }

        // Delete pengaduan
        function deletePengaduan(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')) {
                window.location.href = `<?= base_url('admin/pengaduan/delete') ?>/${id}`;
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Auto refresh every 30 seconds for new notifications
        setInterval(function() {
            // Optional: refresh page or update notification counter
        }, 30000);

        // Rekap Functions
        function toggleCustomDate() {
            const periode = document.getElementById('periodePicker').value;
            const startDiv = document.getElementById('customDateStart');
            const endDiv = document.getElementById('customDateEnd');
            
            if (periode === 'custom') {
                startDiv.style.display = 'block';
                endDiv.style.display = 'block';
            } else {
                startDiv.style.display = 'none';
                endDiv.style.display = 'none';
            }
        }

        function showRekap() {
            const periode = document.getElementById('periodePicker').value;
            let startDate = '';
            let endDate = '';
            
            // Set dates based on periode
            const today = new Date();
            if (periode === 'minggu_ini') {
                const firstDay = new Date(today.setDate(today.getDate() - today.getDay() + 1));
                startDate = firstDay.toISOString().split('T')[0];
                endDate = new Date().toISOString().split('T')[0];
            } else if (periode === 'bulan_ini') {
                startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                endDate = new Date().toISOString().split('T')[0];
            } else if (periode === 'tahun_ini') {
                startDate = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
                endDate = new Date().toISOString().split('T')[0];
            } else if (periode === 'custom') {
                startDate = document.getElementById('startDate').value;
                endDate = document.getElementById('endDate').value;
                
                if (!startDate || !endDate) {
                    alert('Pilih tanggal mulai dan tanggal akhir!');
                    return;
                }
            }
            
            // Show results section
            document.getElementById('rekapResults').style.display = 'block';
            document.getElementById('rekapLoading').style.display = 'block';
            document.getElementById('rekapContent').style.display = 'none';
            
            // Fetch data
            const params = new URLSearchParams({
                start_date: startDate,
                end_date: endDate
            });
            
            fetch(`<?= base_url('admin/pengaduan/rekap-data') ?>?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayRekapData(data.data);
                        
                        document.getElementById('rekapLoading').style.display = 'none';
                        document.getElementById('rekapContent').style.display = 'block';
                        
                        // Store data for export
                        window.rekapData = {
                            start_date: startDate,
                            end_date: endDate,
                            periode: periode
                        };
                    } else {
                        alert('Gagal memuat data rekap');
                        document.getElementById('rekapResults').style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data');
                    document.getElementById('rekapResults').style.display = 'none';
                });
        }

        function displayRekapData(data) {
            // Summary
            document.getElementById('rekapTotal').textContent = data.summary.total;
            document.getElementById('rekapPending').textContent = data.summary.pending;
            document.getElementById('rekapProses').textContent = data.summary.proses;
            document.getElementById('rekapSelesai').textContent = data.summary.selesai;
            document.getElementById('rekapDitolak').textContent = data.summary.ditolak;
            document.getElementById('rekapAvgResponse').textContent = data.summary.avg_response_days;
            
            // By Kategori
            let kategoriHTML = '';
            data.by_kategori.forEach(item => {
                const labels = {
                    'surat': 'Surat',
                    'laporan': 'Laporan',
                    'formulir': 'Formulir',
                    'panduan': 'Panduan',
                    'lainnya': 'Lainnya'
                };
                kategoriHTML += `
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #dee2e6;">
                        <span style="color: #5a6c7d;">${labels[item.kategori_permintaan] || item.kategori_permintaan}</span>
                        <strong style="color: #2c3e50;">${item.total}</strong>
                    </div>
                `;
            });
            document.getElementById('rekapKategori').innerHTML = kategoriHTML || '<p style="color: #888; text-align: center;">Tidak ada data</p>';
            
            // By Jenis Pemohon
            let jenisHTML = '';
            data.by_jenis_pemohon.forEach(item => {
                const labels = {
                    'publik': 'User Publik',
                    'asn': 'ASN'
                };
                jenisHTML += `
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #dee2e6;">
                        <span style="color: #5a6c7d;">${labels[item.jenis_pemohon] || item.jenis_pemohon}</span>
                        <strong style="color: #2c3e50;">${item.total}</strong>
                    </div>
                `;
            });
            document.getElementById('rekapJenis').innerHTML = jenisHTML || '<p style="color: #888; text-align: center;">Tidak ada data</p>';
            
            // By Urgency
            let urgencyHTML = '';
            data.by_urgency.forEach(item => {
                const labels = {
                    'rendah': 'Rendah',
                    'sedang': 'Sedang',
                    'tinggi': 'Tinggi',
                    'sangat_tinggi': 'Sangat Tinggi'
                };
                urgencyHTML += `
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #dee2e6;">
                        <span style="color: #5a6c7d;">${labels[item.urgency] || item.urgency}</span>
                        <strong style="color: #2c3e50;">${item.total}</strong>
                    </div>
                `;
            });
            document.getElementById('rekapUrgency').innerHTML = urgencyHTML || '<p style="color: #888; text-align: center;">Tidak ada data</p>';
        }

        function exportRekap(format) {
            if (!window.rekapData) {
                alert('Silakan tampilkan rekap terlebih dahulu!');
                return;
            }
            
            const params = new URLSearchParams({
                start_date: window.rekapData.start_date,
                end_date: window.rekapData.end_date,
                periode: window.rekapData.periode,
                format: format
            });
            
            window.location.href = `<?= base_url('admin/pengaduan/export-rekap') ?>?${params}`;
        }
    </script>
</body>
</html>