<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIDODIK</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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

        .content-header,
        .dashboard-content {
            position: relative;
            z-index: 2;
        }

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

        .dashboard-content {
            padding: 30px;
        }

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

        /* Charts Section */
        .charts-section {
            margin-bottom: 40px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .chart-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-canvas {
            position: relative;
            height: 300px;
        }

        /* Tables Section */
        .tables-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .analytics-table {
            width: 100%;
            border-collapse: collapse;
        }

        .analytics-table th,
        .analytics-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .analytics-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        .analytics-table td {
            font-size: 13px;
            color: #5a6c7d;
        }

        .analytics-table tr:hover {
            background: #f8f9fa;
        }

        .number-highlight {
            color: #667eea;
            font-weight: 700;
        }

        .progress-bar {
            background: #e2e8f0;
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        /* Overview Cards untuk Pengaduan */
        .overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .overview-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .overview-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .overview-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .overview-content h3 {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 5px 0;
        }

        .overview-content p {
            color: #5a6c7d;
            font-size: 14px;
            font-weight: 500;
            margin: 0;
        }

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

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .charts-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .tables-section {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .overview-card,
            .chart-container,
            .table-container {
                padding: 20px;
            }
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 25px 30px;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-btn {
            background: #f8f9fa;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: #5a6c7d;
            font-size: 18px;
        }

        .close-btn:hover {
            background: #e9ecef;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        /* Filter Section */
        .filter-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .filter-section h3 {
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .filter-item label {
            display: block;
            color: #5a6c7d;
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .filter-input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #e9ecef;
            color: #5a6c7d;
        }

        .btn-secondary:hover {
            background: #dee2e6;
        }

        .btn-success {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 233, 123, 0.3);
        }

        /* Summary Section */
        .summary-section {
            margin-bottom: 30px;
        }

        .summary-section h3 {
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .summary-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .summary-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .summary-content h4 {
            margin: 0 0 5px 0;
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }

        .summary-content p {
            margin: 0;
            font-size: 13px;
            color: #5a6c7d;
        }

        /* Activity Detail Section */
        .activity-detail-section h3 {
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .loading-state {
            text-align: center;
            padding: 40px;
            color: #5a6c7d;
        }

        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #e2e8f0;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .activity-detail-table {
            width: 100%;
            font-size: 13px;
        }

        .activity-detail-table td {
            vertical-align: top;
        }

        .activity-type {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }

        .activity-login { background: #e3f2fd; color: #1976d2; }
        .activity-logout { background: #fff3e0; color: #f57c00; }
        .activity-admin { background: #f3e5f5; color: #7b1fa2; }
        .activity-view { background: #e8f5e9; color: #388e3c; }
        .activity-download { background: #e1f5fe; color: #0288d1; }

        .ip-badge {
            background: #f8f9fa;
            padding: 3px 8px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 11px;
            color: #5a6c7d;
        }

        .browser-info {
            font-size: 11px;
            color: #5a6c7d;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

            .activity-detail-section h3 {
                color: #2c3e50;
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .loading-state {
                text-align: center;
                padding: 40px;
                color: #5a6c7d;
            }

            .loading-spinner {
                width: 20px;
                height: 20px;
                border: 2px solid #e2e8f0;
                border-top: 2px solid #667eea;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 10px;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            .activity-detail-table {
                width: 100%;
                font-size: 13px;
            }

            .activity-detail-table td {
                vertical-align: top;
            }

            .activity-type {
                padding: 4px 10px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
                display: inline-block;
            }

            .activity-login { background: #e3f2fd; color: #1976d2; }
            .activity-logout { background: #fff3e0; color: #f57c00; }
            .activity-admin { background: #f3e5f5; color: #7b1fa2; }
            .activity-view { background: #e8f5e9; color: #388e3c; }
            .activity-download { background: #e1f5fe; color: #0288d1; }

            .ip-badge {
                background: #f8f9fa;
                padding: 3px 8px;
                border-radius: 6px;
                font-family: monospace;
                font-size: 11px;
                color: #5a6c7d;
            }

            .browser-info {
                font-size: 11px;
                color: #5a6c7d;
                max-width: 150px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
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

                    <li class="nav-item">
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
                        <h1>Dashboard Analytics SIDODIK</h1>
                        <p>Sistem Informasi Dokumen Diskominfotik Kabupaten Bandung Barat</p>
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
                        <h2>Dashboard Admin</h2>
                        <p>Kelola dan analisa sistem informasi dokumen secara real-time</p>
                        <span class="breadcrumb-welcome">
                            <i class="fas fa-user-shield"></i>
                            Administrator Panel
                        </span>
                    </div>
                </div>

                <!-- Overview Stats Cards -->
                <div class="overview-grid">
                    <!-- 1. Total Dokumen -->
                    <div class="overview-card">
                        <div class="overview-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="overview-content">
                            <h3><?= number_format($overview_stats['total_documents']) ?></h3>
                            <p>Total Dokumen</p>
                        </div>
                    </div>

                    <!-- 2. Total Menu -->
                    <div class="overview-card">
                        <div class="overview-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-bars"></i>
                        </div>
                        <div class="overview-content">
                            <h3><?= number_format($overview_stats['total_menu']) ?></h3>
                            <p>Total Menu</p>
                        </div>
                    </div>

                    <!-- 3. Total Kategori -->
                    <div class="overview-card">
                        <div class="overview-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="overview-content">
                            <h3><?= number_format($overview_stats['total_kategori']) ?></h3>
                            <p>Total Kategori</p>
                        </div>
                    </div>

                    <!-- 4. Total Views -->
                    <div class="overview-card">
                        <div class="overview-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="overview-content">
                            <h3><?= number_format($overview_stats['total_views']) ?></h3>
                            <p>Total Views</p>
                        </div>
                    </div>

                    <!-- 5. Total Downloads -->
                    <div class="overview-card">
                        <div class="overview-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="overview-content">
                            <h3><?= number_format($overview_stats['total_downloads']) ?></h3>
                            <p>Total Downloads</p>
                        </div>
                    </div>

                    <!-- 6. Total Users -->
                    <div class="overview-card">
                        <div class="overview-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="overview-content">
                            <h3><?= number_format($overview_stats['total_users']) ?></h3>
                            <p>Total Users</p>
                        </div>
                    </div>

                    <!-- 7. Pengaduan Pending (ALERT) -->
                    <div class="overview-card">
                        <div class="overview-icon" style="background: linear-gradient(135deg, #ffa500 0%, #ff6b6b 100%);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="overview-content">
                            <h3><?= number_format($overview_stats['pengaduan_pending']) ?></h3>
                            <p>Pengaduan Pending</p>
                        </div>
                    </div>

                    <!-- 8. Total Pengaduan -->
                    <div class="overview-card">
                        <div class="overview-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="overview-content">
                            <h3><?= number_format($overview_stats['total_pengaduan']) ?></h3>
                            <p>Total Pengaduan</p>
                        </div>
                    </div>

                    <!-- 9. Rata-rata Response Time -->
                    <div class="overview-card">
                        <div class="overview-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="overview-content">
                            <h3><?= $overview_stats['avg_response_time'] ?></h3>
                            <p>Rata-rata Respon (Hari)</p>
                        </div>
                    </div>

                    <!-- 10. Sesi Aktif -->
                    <div class="overview-card">
                        <div class="overview-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                            <i class="fas fa-circle"></i>
                        </div>
                        <div class="overview-content">
                            <h3><?= number_format($overview_stats['active_sessions']) ?></h3>
                            <p>Sesi Aktif</p>
                        </div>
                    </div>
                </div>

                <!-- Charts Section - Document Analytics -->
                <div class="charts-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-chart-pie"></i>
                            Analisis Dokumen
                        </h2>
                    </div>

                    <div class="charts-grid">
                        <!-- Document Type Distribution -->
                        <div class="chart-container">
                            <h3 class="chart-title">
                                <i class="fas fa-file-alt"></i>
                                Distribusi Tipe Dokumen
                            </h3>
                            <div class="chart-canvas">
                                <canvas id="documentTypeChart"></canvas>
                            </div>
                        </div>

                        <!-- Document Access Level -->
                        <div class="chart-container">
                            <h3 class="chart-title">
                                <i class="fas fa-lock"></i>
                                Tingkat Akses Dokumen
                            </h3>
                            <div class="chart-canvas">
                                <canvas id="accessLevelChart"></canvas>
                            </div>
                        </div>

                        <!-- User Activity Trend -->
                        <div class="chart-container">
                            <h3 class="chart-title">
                                <i class="fas fa-chart-line"></i>
                                Tren Aktivitas Login (7 Hari)
                            </h3>
                            <div class="chart-canvas">
                                <canvas id="loginTrendChart"></canvas>
                            </div>
                        </div>

                        <!-- Menu Performance -->
                        <div class="chart-container">
                            <h3 class="chart-title">
                                <i class="fas fa-bars"></i>
                                Performa Menu (Total Views)
                            </h3>
                            <div class="chart-canvas">
                                <canvas id="menuPerformanceChart"></canvas>
                            </div>
                        </div>

                        <!-- Monthly Upload Trend -->
                        <div class="chart-container" style="grid-column: 1 / -1;">
                            <h3 class="chart-title">
                                <i class="fas fa-upload"></i>
                                Tren Upload Dokumen (12 Bulan)
                            </h3>
                            <div class="chart-canvas">
                                <canvas id="uploadTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengaduan Analytics Section -->
                <div class="charts-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-headset"></i>
                            Analisis Pengaduan Dokumen
                        </h2>
                    </div>

                    <!-- Success Rate Cards -->
                    <div class="overview-grid" style="margin-bottom: 30px;">
                        <div class="overview-card">
                            <div class="overview-icon" style="background: linear-gradient(135deg, #27ae60, #229954);">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="overview-content">
                                <h3><?= number_format($pengaduan_analytics['success_percentage'], 1) ?>%</h3>
                                <p>Tingkat Keberhasilan</p>
                            </div>
                        </div>

                        <div class="overview-card">
                            <div class="overview-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="overview-content">
                                <h3><?= number_format($pengaduan_analytics['reject_percentage'], 1) ?>%</h3>
                                <p>Tingkat Penolakan</p>
                            </div>
                        </div>

                        <div class="overview-card">
                            <div class="overview-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="overview-content">
                                <h3><?= number_format($pengaduan_analytics['ongoing_percentage'], 1) ?>%</h3>
                                <p>Sedang Diproses</p>
                            </div>
                        </div>

                        <div class="overview-card">
                            <div class="overview-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="overview-content">
                                <h3><?= number_format($pengaduan_analytics['response_time']['avg_days'], 1) ?></h3>
                                <p>Rata-rata Waktu Respon (Hari)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pengaduan Charts -->
                    <div class="charts-grid">
                        <!-- Pengaduan Status -->
                        <div class="chart-container">
                            <h3 class="chart-title">
                                <i class="fas fa-headset"></i>
                                Status Pengaduan
                            </h3>
                            <div class="chart-canvas">
                                <canvas id="pengaduanStatusChart"></canvas>
                            </div>
                        </div>

                        <!-- Pengaduan by Kategori -->
                        <div class="chart-container">
                            <h3 class="chart-title">
                                <i class="fas fa-chart-bar"></i>
                                Pengaduan Berdasarkan Kategori
                            </h3>
                            <div class="chart-canvas">
                                <canvas id="pengaduanKategoriChart"></canvas>
                            </div>
                        </div>

                        <!-- Pengaduan by Jenis Pemohon -->
                        <div class="chart-container">
                            <h3 class="chart-title">
                                <i class="fas fa-users"></i>
                                Pengaduan Berdasarkan Jenis Pemohon
                            </h3>
                            <div class="chart-canvas">
                                <canvas id="pengaduanJenisPemohonChart"></canvas>
                            </div>
                        </div>

                        <!-- Success vs Reject Rate -->
                        <div class="chart-container">
                            <h3 class="chart-title">
                                <i class="fas fa-chart-pie"></i>
                                Rasio Keberhasilan vs Penolakan
                            </h3>
                            <div class="chart-canvas">
                                <canvas id="successRejectChart"></canvas>
                            </div>
                        </div>

                        <!-- Pengaduan Trend -->
                        <div class="chart-container" style="grid-column: 1 / -1;">
                            <h3 class="chart-title">
                                <i class="fas fa-chart-line"></i>
                                Tren Pengaduan 6 Bulan Terakhir
                            </h3>
                            <div class="chart-canvas">
                                <canvas id="pengaduanTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tables Section -->
                <div class="tables-section">
                    <!-- Top Documents -->
                    <div class="table-container">
                        <h3 class="table-title">
                            <i class="fas fa-trophy"></i>
                            Dokumen Paling Populer
                        </h3>
                        <table class="analytics-table">
                            <thead>
                                <tr>
                                    <th>Judul Dokumen</th>
                                    <th>Views</th>
                                    <th>Downloads</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($top_documents['most_viewed'], 0, 5) as $doc): ?>
                                    <tr>
                                        <td>
                                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                <?= esc($doc['judul']) ?>
                                            </div>
                                            <div style="font-size: 11px; color: #888;">
                                                <?= esc($doc['nama_kategori']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="number-highlight"><?= number_format($doc['views']) ?></span>
                                            <div class="progress-bar">
                                                <?php 
                                                    $maxViews = !empty($top_documents['most_viewed']) ? max(array_column($top_documents['most_viewed'], 'views')) : 1;
                                                    $percentage = $maxViews > 0 ? min(100, ($doc['views'] / $maxViews * 100)) : 0;
                                                ?>
                                                <div class="progress-fill" style="width: <?= $percentage ?>%"></div>
                                            </div>
                                        </td>
                                        <td><span class="number-highlight"><?= number_format($doc['downloads']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Menu Performance Details -->
                    <div class="table-container">
                        <h3 class="table-title">
                            <i class="fas fa-chart-bar"></i>
                            Detail Performa Menu
                        </h3>
                        <table class="analytics-table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Dokumen</th>
                                    <th>Total Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($menu_performance, 0, 5) as $menu): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-<?= $menu['icon'] ?: 'folder' ?>"></i>
                                            <?= esc($menu['nama_menu']) ?>
                                        </td>
                                        <td><span class="number-highlight"><?= number_format($menu['document_count']) ?></span></td>
                                        <td><span class="number-highlight"><?= number_format($menu['total_views']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Top Dokumen yang Paling Banyak Diminta -->
                    <div class="table-container" style="grid-column: 1 / -1;">
                        <h3 class="table-title">
                            <i class="fas fa-fire"></i>
                            Top 5 Dokumen Paling Banyak Diminta
                        </h3>
                        <table class="analytics-table">
                            <thead>
                                <tr>
                                    <th>Judul Dokumen</th>
                                    <th>Total Permintaan</th>
                                    <th>Terpenuhi</th>
                                    <th>Ditolak</th>
                                    <th>Tingkat Pemenuhan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pengaduan_analytics['top_dokumen_diminta'])): ?>
                                    <?php foreach (array_slice($pengaduan_analytics['top_dokumen_diminta'], 0, 5) as $dok): ?>
                                        <?php 
                                            $fulfillment_rate = $dok['jumlah_permintaan'] > 0 
                                                ? round(($dok['terpenuhi'] / $dok['jumlah_permintaan']) * 100, 1) 
                                                : 0;
                                        ?>
                                        <tr>
                                            <td>
                                                <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; font-weight: 600;">
                                                    <?= esc($dok['judul_dokumen']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="number-highlight"><?= $dok['jumlah_permintaan'] ?></span>
                                            </td>
                                            <td>
                                                <span style="color: #27ae60; font-weight: 600;"><?= $dok['terpenuhi'] ?></span>
                                            </td>
                                            <td>
                                                <span style="color: #e74c3c; font-weight: 600;"><?= $dok['ditolak'] ?></span>
                                            </td>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <span class="number-highlight"><?= $fulfillment_rate ?>%</span>
                                                    <div class="progress-bar" style="flex: 1;">
                                                        <div class="progress-fill" style="width: <?= $fulfillment_rate ?>%; background: <?= $fulfillment_rate >= 70 ? 'linear-gradient(135deg, #27ae60, #229954)' : ($fulfillment_rate >= 40 ? 'linear-gradient(135deg, #f39c12, #e67e22)' : 'linear-gradient(135deg, #e74c3c, #c0392b)') ?>;"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #888; padding: 40px;">
                                            Belum ada data pengaduan
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Activity Details Section - MENGGANTI MODAL -->
                <div class="table-container" style="grid-column: 1 / -1; margin-top: 40px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 class="table-title">
                            <i class="fas fa-history"></i>
                            Aktivitas Terbaru
                        </h3>
                        <button class="btn btn-primary" onclick="toggleActivitySection()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
                            <i class="fas fa-filter"></i> Filter & Detail
                        </button>
                    </div>
                    
                    <table class="analytics-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Aktivitas</th>
                                <th>Dokumen</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($recent_activity, 0, 10) as $activity): ?>
                                <tr>
                                    <td>
                                        <div><?= esc($activity['nama_lengkap'] ?? 'System') ?></div>
                                        <div style="font-size: 11px; color: #888;">@<?= esc($activity['username'] ?? 'system') ?></div>
                                    </td>
                                    <td>
                                        <span class="status-badge <?= ($activity['activity'] ?? '') === 'login' ? 'badge-low' : (($activity['activity'] ?? '') === 'logout' ? 'badge-medium' : 'badge-high') ?>">
                                            <?= ucfirst($activity['activity'] ?? 'unknown') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($activity['document_title'])): ?>
                                            <div style="max-width: 150px; overflow: hidden; text-overflow: ellipsis;">
                                                <?= esc($activity['document_title']) ?>
                                            </div>
                                        <?php else: ?>
                                            <span style="color: #888;">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="font-size: 11px;">
                                            <?= date('d/m/y', strtotime($activity['created_at'])) ?>
                                        </div>
                                        <div style="font-size: 11px; color: #888;">
                                            <?= date('H:i', strtotime($activity['created_at'])) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Activity Filter & Detail Section (Hidden by default) -->
                <div id="activityDetailSection" class="table-container" style="grid-column: 1 / -1; margin-top: 40px; display: none;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 class="table-title">
                            <i class="fas fa-chart-line"></i> Detail Aktivitas Sistem
                        </h3>
                        <button class="btn btn-secondary" onclick="toggleActivitySection()" style="background: #6c757d; color: white; padding: 10px 15px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                    </div>

                    <!-- Filter Section -->
                    <div class="filter-section">
                        <h3><i class="fas fa-filter"></i> Filter Aktivitas</h3>
                        <div class="filter-grid">
                            <div class="filter-item">
                                <label>Tanggal Mulai:</label>
                                <input type="date" id="filterStartDate" class="filter-input">
                            </div>
                            <div class="filter-item">
                                <label>Tanggal Akhir:</label>
                                <input type="date" id="filterEndDate" class="filter-input">
                            </div>
                            <div class="filter-item">
                                <label>Jenis Aktivitas:</label>
                                <select id="filterActivityType" class="filter-input">
                                    <option value="all">Semua</option>
                                    <option value="admin">Admin (Add/Edit/Delete)</option>
                                    <option value="view">View</option>
                                    <option value="download">Download</option>
                                    <option value="login">Login/Logout</option>
                                </select>
                            </div>
                            <div class="filter-item">
                                <label>User:</label>
                                <select id="filterUser" class="filter-input">
                                    <option value="all">Semua User</option>
                                    <?php foreach ($all_users as $u): ?>
                                        <option value="<?= $u['id'] ?>"><?= esc($u['nama_lengkap']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Quick Filters -->
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #e9ecef;">
                            <p style="font-size: 13px; color: #5a6c7d; font-weight: 600; margin-bottom: 10px;">
                                <i class="fas fa-bolt"></i> Filter Cepat:
                            </p>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <button class="btn btn-secondary" style="font-size: 12px; padding: 8px 15px;" onclick="setQuickFilter('today')">
                                    Hari Ini
                                </button>
                                <button class="btn btn-secondary" style="font-size: 12px; padding: 8px 15px;" onclick="setQuickFilter('week')">
                                    Minggu Ini
                                </button>
                                <button class="btn btn-secondary" style="font-size: 12px; padding: 8px 15px;" onclick="setQuickFilter('month')">
                                    Bulan Ini
                                </button>
                                <button class="btn btn-secondary" style="font-size: 12px; padding: 8px 15px;" onclick="setQuickFilter('last7')">
                                    7 Hari Terakhir
                                </button>
                                <button class="btn btn-secondary" style="font-size: 12px; padding: 8px 15px;" onclick="setQuickFilter('last30')">
                                    30 Hari Terakhir
                                </button>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 10px; margin-top: 20px;">
                            <button class="btn btn-primary" onclick="applyActivityFilter()">
                                <i class="fas fa-search"></i> Terapkan Filter
                            </button>
                            <button class="btn btn-secondary" onclick="resetActivityFilter()">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            
                            <!-- Dropdown Export -->
                            <div style="position: relative; display: inline-block;">
                                <button class="btn btn-success" onclick="toggleExportMenu(event)" id="exportBtn">
                                    <i class="fas fa-file-export"></i> Export
                                    <i class="fas fa-caret-down" style="margin-left: 5px;"></i>
                                </button>
                                <div id="exportMenu" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 5px; background: white; border: 1px solid #e9ecef; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); min-width: 150px; z-index: 1000;">
                                    <a href="#" onclick="exportActivity('excel'); return false;" style="display: block; padding: 10px 15px; color: #27ae60; text-decoration: none; border-bottom: 1px solid #e9ecef;">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </a>
                                    <a href="#" onclick="exportActivity('pdf'); return false;" style="display: block; padding: 10px 15px; color: #e74c3c; text-decoration: none;">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div id="activitySummary" class="summary-section" style="display: none;">
                        <h3><i class="fas fa-chart-pie"></i> Ringkasan</h3>
                        <div class="summary-grid">
                            <div class="summary-card">
                                <div class="summary-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div class="summary-content">
                                    <h4 id="summaryTotal">0</h4>
                                    <p>Total Aktivitas</p>
                                </div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="summary-content">
                                    <h4 id="summaryUsers">0</h4>
                                    <p>User Aktif</p>
                                </div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div class="summary-content">
                                    <h4 id="summaryTopActivity">-</h4>
                                    <p>Aktivitas Terbanyak</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Table -->
                    <div class="activity-detail-section">
                        <h3><i class="fas fa-list"></i> Detail Aktivitas</h3>
                        <div id="activityLoading" class="loading-state">
                            <div class="loading-spinner"></div>
                            <p>Memuat data...</p>
                        </div>
                        <div id="activityTableContainer" style="display: none;">
                            <table class="analytics-table activity-detail-table">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>User</th>
                                        <th>Aktivitas</th>
                                        <th>Target</th>
                                        <th>IP Address</th>
                                        <th>Browser</th>
                                    </tr>
                                </thead>
                                <tbody id="activityTableBody">
                                    <!-- Data will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Export Section -->
                <div class="table-container" style="grid-column: 1 / -1; text-align: center;">
                    <h3 class="table-title" style="justify-content: center;">
                        <i class="fas fa-download"></i>
                        Export Laporan Dashboard
                    </h3>
                    <p style="color: #5a6c7d; margin-bottom: 25px;">
                        Download laporan lengkap analytics dashboard dalam format Excel (CSV) atau PDF
                    </p>
                    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                        <a href="<?= base_url('admin/dashboard/export?format=excel') ?>" class="btn-export" style="background: linear-gradient(135deg, #27ae60, #229954);">
                            <i class="fas fa-file-excel"></i>
                            Download Excel (CSV)
                        </a>
                        <a href="<?= base_url('admin/dashboard/export?format=pdf') ?>" class="btn-export" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                            <i class="fas fa-file-pdf"></i>
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
        .btn-export {
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-export:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            text-decoration: none;
            color: white;
        }

        .btn-export i {
            font-size: 18px;
        }
    </style>

    <script>
        // Chart.js Configuration
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#5a6c7d';

        // Color palette
        const colors = [
            '#667eea', '#764ba2', '#f093fb', '#f5576c', 
            '#4facfe', '#00f2fe', '#43e97b', '#38f9d7',
            '#fa709a', '#fee140', '#a8edea', '#fed6e3'
        ];

        // Document Type Chart
        const docTypeCtx = document.getElementById('documentTypeChart').getContext('2d');
        const docTypeData = <?= json_encode($document_stats['by_type']) ?>;
        new Chart(docTypeCtx, {
            type: 'doughnut',
            data: {
                labels: docTypeData.map(item => item.file_type.toUpperCase()),
                datasets: [{
                    data: docTypeData.map(item => item.count),
                    backgroundColor: colors.slice(0, docTypeData.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                }
            }
        });

        // Access Level Chart
        const accessCtx = document.getElementById('accessLevelChart').getContext('2d');
        const accessData = <?= json_encode($document_stats['by_access']) ?>;
        new Chart(accessCtx, {
            type: 'pie',
            data: {
                labels: accessData.map(item => item.akses === 'publik' ? 'Publik' : 'Privat'),
                datasets: [{
                    data: accessData.map(item => item.count),
                    backgroundColor: ['#43e97b', '#fa709a'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                }
            }
        });

        // Login Trend Chart
        const loginCtx = document.getElementById('loginTrendChart').getContext('2d');
        const loginData = <?= json_encode($user_activity['login_trend']) ?>;
        new Chart(loginCtx, {
            type: 'line',
            data: {
                labels: loginData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('id-ID', { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Login',
                    data: loginData.map(item => item.logins),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Menu Performance Chart
        const menuCtx = document.getElementById('menuPerformanceChart').getContext('2d');
        const menuData = <?= json_encode(array_slice($menu_performance, 0, 6)) ?>;
        new Chart(menuCtx, {
            type: 'bar',
            data: {
                labels: menuData.map(item => item.nama_menu),
                datasets: [{
                    label: 'Total Views',
                    data: menuData.map(item => item.total_views),
                    backgroundColor: colors.slice(0, menuData.length),
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Upload Trend Chart
        const uploadCtx = document.getElementById('uploadTrendChart').getContext('2d');
        const uploadData = <?= json_encode($monthly_trends['uploads']) ?>;
        new Chart(uploadCtx, {
            type: 'line',
            data: {
                labels: uploadData.map(item => {
                    const [year, month] = item.month.split('-');
                    const date = new Date(year, month - 1);
                    return date.toLocaleDateString('id-ID', { month: 'short', year: '2-digit' });
                }),
                datasets: [{
                    label: 'Upload',
                    data: uploadData.map(item => item.uploads),
                    borderColor: '#f5576c',
                    backgroundColor: 'rgba(245, 87, 108, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Pengaduan Status Chart
        const pengaduanCtx = document.getElementById('pengaduanStatusChart').getContext('2d');
        const pengaduanData = <?= json_encode($pengaduan_stats['by_status']) ?>;
        const statusLabels = {
            'pending': 'Menunggu',
            'proses': 'Diproses', 
            'selesai': 'Selesai',
            'ditolak': 'Ditolak'
        };
        new Chart(pengaduanCtx, {
            type: 'doughnut',
            data: {
                labels: pengaduanData.map(item => statusLabels[item.status] || item.status),
                datasets: [{
                    data: pengaduanData.map(item => item.count),
                    backgroundColor: ['#fee140', '#4facfe', '#43e97b', '#f5576c'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                }
            }
        });

        // Pengaduan by Kategori Chart
        const pengaduanKategoriCtx = document.getElementById('pengaduanKategoriChart').getContext('2d');
        const pengaduanKategoriData = <?= json_encode($pengaduan_analytics['by_kategori']) ?>;
        new Chart(pengaduanKategoriCtx, {
            type: 'bar',
            data: {
                labels: pengaduanKategoriData.map(item => {
                    const labels = {
                        'surat': 'Surat',
                        'laporan': 'Laporan',
                        'formulir': 'Formulir',
                        'panduan': 'Panduan',
                        'lainnya': 'Lainnya'
                    };
                    return labels[item.kategori_permintaan] || item.kategori_permintaan;
                }),
                datasets: [{
                    label: 'Total',
                    data: pengaduanKategoriData.map(item => item.total),
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderRadius: 8
                }, {
                    label: 'Selesai',
                    data: pengaduanKategoriData.map(item => item.selesai),
                    backgroundColor: 'rgba(39, 174, 96, 0.8)',
                    borderRadius: 8
                }, {
                    label: 'Ditolak',
                    data: pengaduanKategoriData.map(item => item.ditolak),
                    backgroundColor: 'rgba(231, 76, 60, 0.8)',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Pengaduan by Jenis Pemohon Chart
        const pengaduanJenisPemohonCtx = document.getElementById('pengaduanJenisPemohonChart').getContext('2d');
        const pengaduanJenisPemohonData = <?= json_encode($pengaduan_analytics['by_jenis_pemohon']) ?>;
        new Chart(pengaduanJenisPemohonCtx, {
            type: 'doughnut',
            data: {
                labels: pengaduanJenisPemohonData.map(item => item.jenis_pemohon === 'publik' ? 'User Publik' : 'ASN'),
                datasets: [{
                    data: pengaduanJenisPemohonData.map(item => item.total),
                    backgroundColor: ['#667eea', '#f5576c'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                }
            }
        });

        // Success vs Reject Rate Chart
        const successRejectCtx = document.getElementById('successRejectChart').getContext('2d');
        new Chart(successRejectCtx, {
            type: 'pie',
            data: {
                labels: ['Selesai', 'Ditolak', 'Sedang Diproses'],
                datasets: [{
                    data: [
                        <?= $pengaduan_analytics['success_percentage'] ?>,
                        <?= $pengaduan_analytics['reject_percentage'] ?>,
                        <?= $pengaduan_analytics['ongoing_percentage'] ?>
                    ],
                    backgroundColor: ['#27ae60', '#e74c3c', '#f39c12'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                }
            }
        });

        // Pengaduan Trend Chart
        const pengaduanTrendCtx = document.getElementById('pengaduanTrendChart').getContext('2d');
        const pengaduanTrendData = <?= json_encode($pengaduan_analytics['trend_bulanan']) ?>;
        new Chart(pengaduanTrendCtx, {
            type: 'line',
            data: {
                labels: pengaduanTrendData.map(item => {
                    const [year, month] = item.bulan.split('-');
                    const date = new Date(year, month - 1);
                    return date.toLocaleDateString('id-ID', { month: 'short', year: '2-digit' });
                }),
                datasets: [{
                    label: 'Total Pengaduan',
                    data: pengaduanTrendData.map(item => item.total),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Selesai',
                    data: pengaduanTrendData.map(item => item.selesai),
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39, 174, 96, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Ditolak',
                    data: pengaduanTrendData.map(item => item.ditolak),
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // ========== ACTIVITY FILTER & DETAIL FUNCTIONS ==========
    function openActivityFilter() {
        document.getElementById('activityModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Set default date range (last 7 days)
        const endDate = new Date();
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - 7);
        
        document.getElementById('filterStartDate').value = startDate.toISOString().split('T')[0];
        document.getElementById('filterEndDate').value = endDate.toISOString().split('T')[0];
        
        // Load initial data
        applyActivityFilter();
    }

    function closeActivityModal() {
        document.getElementById('activityModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function applyActivityFilter() {
        const startDate = document.getElementById('filterStartDate').value;
        const endDate = document.getElementById('filterEndDate').value;
        const activityType = document.getElementById('filterActivityType').value;
        const userId = document.getElementById('filterUser').value;

        // Show loading
        document.getElementById('activityLoading').style.display = 'block';
        document.getElementById('activityTableContainer').style.display = 'none';
        document.getElementById('activitySummary').style.display = 'none';

        // Build query params
        const params = new URLSearchParams({
            start_date: startDate,
            end_date: endDate,
            activity_type: activityType,
            user_id: userId
        });

        // Fetch data
        fetch(`<?= base_url('admin/analytics/activity-details') ?>?${params}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayActivityData(data.activities);
                    displaySummary(data.summary);
                    
                    document.getElementById('activityLoading').style.display = 'none';
                    document.getElementById('activityTableContainer').style.display = 'block';
                    document.getElementById('activitySummary').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('activityLoading').innerHTML = '<p style="color: red;">Gagal memuat data. Silakan coba lagi.</p>';
            });
    }

    function displayActivityData(activities) {
        const tbody = document.getElementById('activityTableBody');
        tbody.innerHTML = '';

        if (activities.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px; color: #888;">Tidak ada data aktivitas</td></tr>';
            return;
        }

        activities.forEach(activity => {
            const row = document.createElement('tr');
            
            // Format date
            const date = new Date(activity.created_at);
            const dateStr = date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            const timeStr = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            
            // Determine activity type class
            let activityClass = 'activity-admin';
            if (activity.activity.includes('login')) activityClass = 'activity-login';
            else if (activity.activity.includes('logout')) activityClass = 'activity-logout';
            else if (activity.activity.includes('view')) activityClass = 'activity-view';
            else if (activity.activity.includes('download')) activityClass = 'activity-download';
            
            // Get target info
            let target = '-';
            if (activity.dokumen_judul) {
                target = `<div style="font-weight: 600;">${escapeHtml(activity.dokumen_judul)}</div>`;
                if (activity.nama_menu) {
                    target += `<div style="font-size: 11px; color: #888;">${escapeHtml(activity.nama_menu)}</div>`;
                }
            } else if (activity.activity.includes('kategori') || activity.activity.includes('menu') || activity.activity.includes('user') || activity.activity.includes('pengaduan')) {
                const activityParts = activity.activity.split('_');
                target = `<span style="text-transform: capitalize;">${activityParts[0] || 'System'}</span>`;
            }
            
            // Parse user agent
            const browser = parseUserAgent(activity.user_agent || '');
            
            row.innerHTML = `
                <td>
                    <div style="font-weight: 600;">${dateStr}</div>
                    <div style="font-size: 11px; color: #888;">${timeStr}</div>
                </td>
                <td>
                    <div style="font-weight: 600;">${escapeHtml(activity.nama_lengkap || 'System')}</div>
                    <div style="font-size: 11px; color: #888;">@${escapeHtml(activity.username || 'system')}</div>
                </td>
                <td>
                    <span class="activity-type ${activityClass}">${activity.activity}</span>
                </td>
                <td>${target}</td>
                <td>
                    <span class="ip-badge">${escapeHtml(activity.ip_address || '-')}</span>
                </td>
                <td>
                    <div class="browser-info" title="${escapeHtml(activity.user_agent || '-')}">${browser}</div>
                </td>
            `;
            
            tbody.appendChild(row);
        });
    }

    function displaySummary(summary) {
        document.getElementById('summaryTotal').textContent = summary.total.toLocaleString('id-ID');
        document.getElementById('summaryUsers').textContent = summary.active_users.length;
        
        if (summary.by_type && summary.by_type.length > 0) {
            document.getElementById('summaryTopActivity').textContent = summary.by_type[0].activity + ' (' + summary.by_type[0].count + ')';
        } else {
            document.getElementById('summaryTopActivity').textContent = '-';
        }
    }

    function resetActivityFilter() {
        // Reset to last 7 days
        const endDate = new Date();
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - 7);
        
        document.getElementById('filterStartDate').value = startDate.toISOString().split('T')[0];
        document.getElementById('filterEndDate').value = endDate.toISOString().split('T')[0];
        document.getElementById('filterActivityType').value = 'all';
        document.getElementById('filterUser').value = 'all';
        
        applyActivityFilter();
    }

    function exportActivityReport() {
        const startDate = document.getElementById('filterStartDate').value;
        const endDate = document.getElementById('filterEndDate').value;
        
        const url = `<?= base_url('admin/analytics/activity-export') ?>?start_date=${startDate}&end_date=${endDate}`;
        window.location.href = url;
    }

    function setQuickFilter(type) {
        const endDate = new Date();
        let startDate = new Date();
        
        switch(type) {
            case 'today':
                startDate = new Date();
                break;
            case 'week':
                // Start of week (Monday)
                const day = startDate.getDay();
                const diff = startDate.getDate() - day + (day === 0 ? -6 : 1);
                startDate.setDate(diff);
                break;
            case 'month':
                startDate.setDate(1);
                break;
            case 'last7':
                startDate.setDate(startDate.getDate() - 7);
                break;
            case 'last30':
                startDate.setDate(startDate.getDate() - 30);
                break;
        }
        
        document.getElementById('filterStartDate').value = startDate.toISOString().split('T')[0];
        document.getElementById('filterEndDate').value = endDate.toISOString().split('T')[0];
        
        applyActivityFilter();
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function parseUserAgent(ua) {
        if (!ua) return '-';
        
        // Detect browser
        let browser = 'Unknown';
        if (ua.includes('Chrome') && !ua.includes('Edg')) browser = 'Chrome';
        else if (ua.includes('Firefox')) browser = 'Firefox';
        else if (ua.includes('Safari') && !ua.includes('Chrome')) browser = 'Safari';
        else if (ua.includes('Edg')) browser = 'Edge';
        else if (ua.includes('MSIE') || ua.includes('Trident')) browser = 'IE';
        
        // Detect OS
        let os = '';
        if (ua.includes('Windows NT 10.0')) os = 'Win 10';
        else if (ua.includes('Windows NT 6.3')) os = 'Win 8.1';
        else if (ua.includes('Windows NT 6.2')) os = 'Win 8';
        else if (ua.includes('Windows NT 6.1')) os = 'Win 7';
        else if (ua.includes('Mac OS X')) os = 'Mac';
        else if (ua.includes('Linux')) os = 'Linux';
        else if (ua.includes('Android')) os = 'Android';
        else if (ua.includes('iPhone') || ua.includes('iPad')) os = 'iOS';
        
        return os ? `${browser} (${os})` : browser;
    }

    // ========== EVENT LISTENERS ==========

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('activityModal');
        if (event.target === modal) {
            closeActivityModal();
        }
    });

    // Keyboard shortcut to close modal (ESC)
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('activityModal');
            if (modal && modal.style.display === 'flex') {
                closeActivityModal();
            }
        }
    });

    // Export Menu Toggle
    function toggleExportMenu(event) {
        event.stopPropagation();
        const menu = document.getElementById('exportMenu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }

    // Close export menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('exportMenu');
        const btn = document.getElementById('exportBtn');
        if (menu && btn && !btn.contains(event.target)) {
            menu.style.display = 'none';
        }
    });

    // Export Activity Function
    function exportActivity(format) {
        const startDate = document.getElementById('filterStartDate').value;
        const endDate = document.getElementById('filterEndDate').value;
        
        if (!startDate || !endDate) {
            alert('Silakan pilih tanggal terlebih dahulu');
            return;
        }
        
        const url = `<?= base_url('admin/analytics/activity-export') ?>?start_date=${startDate}&end_date=${endDate}&format=${format}`;
        window.open(url, '_blank');
        
        // Close menu
        document.getElementById('exportMenu').style.display = 'none';
    }

    function toggleActivitySection() {
        const section = document.getElementById('activityDetailSection');
        if (section.style.display === 'none') {
            section.style.display = 'block';
            setDefaultDateRange();
            applyActivityFilter();
        } else {
            section.style.display = 'none';
        }
    }

    function setDefaultDateRange() {
        const endDate = new Date();
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - 7);
        
        document.getElementById('filterStartDate').value = startDate.toISOString().split('T')[0];
        document.getElementById('filterEndDate').value = endDate.toISOString().split('T')[0];
    }

    function applyActivityFilter() {
        const startDate = document.getElementById('filterStartDate').value;
        const endDate = document.getElementById('filterEndDate').value;
        const activityType = document.getElementById('filterActivityType').value;
        const userId = document.getElementById('filterUser').value;

        document.getElementById('activityLoading').style.display = 'block';
        document.getElementById('activityTableContainer').style.display = 'none';
        document.getElementById('activitySummary').style.display = 'none';

        const params = new URLSearchParams({
            start_date: startDate,
            end_date: endDate,
            activity_type: activityType,
            user_id: userId
        });

        fetch(`<?= base_url('admin/analytics/activity-details') ?>?${params}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayActivityData(data.activities);
                    displaySummary(data.summary);
                    
                    document.getElementById('activityLoading').style.display = 'none';
                    document.getElementById('activityTableContainer').style.display = 'block';
                    document.getElementById('activitySummary').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('activityLoading').innerHTML = '<p style="color: red;">Gagal memuat data. Silakan coba lagi.</p>';
            });
    }

    function displayActivityData(activities) {
        const tbody = document.getElementById('activityTableBody');
        tbody.innerHTML = '';

        if (activities.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px; color: #888;">Tidak ada data aktivitas</td></tr>';
            return;
        }

        activities.forEach(activity => {
            const row = document.createElement('tr');
            
            const date = new Date(activity.created_at);
            const dateStr = date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            const timeStr = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            
            let activityClass = 'activity-admin';
            if (activity.activity.includes('login')) activityClass = 'activity-login';
            else if (activity.activity.includes('logout')) activityClass = 'activity-logout';
            else if (activity.activity.includes('view')) activityClass = 'activity-view';
            else if (activity.activity.includes('download')) activityClass = 'activity-download';
            
            let target = '-';
            if (activity.dokumen_judul) {
                target = `<div style="font-weight: 600;">${escapeHtml(activity.dokumen_judul)}</div>`;
                if (activity.nama_menu) {
                    target += `<div style="font-size: 11px; color: #888;">${escapeHtml(activity.nama_menu)}</div>`;
                }
            } else if (activity.activity.includes('kategori') || activity.activity.includes('menu') || activity.activity.includes('user') || activity.activity.includes('pengaduan')) {
                const activityParts = activity.activity.split('_');
                target = `<span style="text-transform: capitalize;">${activityParts[0] || 'System'}</span>`;
            }
            
            const browser = parseUserAgent(activity.user_agent || '');
            
            row.innerHTML = `
                <td>
                    <div style="font-weight: 600;">${dateStr}</div>
                    <div style="font-size: 11px; color: #888;">${timeStr}</div>
                </td>
                <td>
                    <div style="font-weight: 600;">${escapeHtml(activity.nama_lengkap || 'System')}</div>
                    <div style="font-size: 11px; color: #888;">@${escapeHtml(activity.username || 'system')}</div>
                </td>
                <td>
                    <span class="activity-type ${activityClass}">${activity.activity}</span>
                </td>
                <td>${target}</td>
                <td>
                    <span class="ip-badge">${escapeHtml(activity.ip_address || '-')}</span>
                </td>
                <td>
                    <div class="browser-info" title="${escapeHtml(activity.user_agent || '-')}">${browser}</div>
                </td>
            `;
            
            tbody.appendChild(row);
        });
    }

    function displaySummary(summary) {
        document.getElementById('summaryTotal').textContent = summary.total.toLocaleString('id-ID');
        document.getElementById('summaryUsers').textContent = summary.active_users.length;
        
        if (summary.by_type && summary.by_type.length > 0) {
            document.getElementById('summaryTopActivity').textContent = summary.by_type[0].activity + ' (' + summary.by_type[0].count + ')';
        } else {
            document.getElementById('summaryTopActivity').textContent = '-';
        }
    }

    function resetActivityFilter() {
        const endDate = new Date();
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - 7);
        
        document.getElementById('filterStartDate').value = startDate.toISOString().split('T')[0];
        document.getElementById('filterEndDate').value = endDate.toISOString().split('T')[0];
        document.getElementById('filterActivityType').value = 'all';
        document.getElementById('filterUser').value = 'all';
        
        applyActivityFilter();
    }

    function setQuickFilter(type) {
        const endDate = new Date();
        let startDate = new Date();
        
        switch(type) {
            case 'today':
                startDate = new Date();
                break;
            case 'week':
                const day = startDate.getDay();
                const diff = startDate.getDate() - day + (day === 0 ? -6 : 1);
                startDate.setDate(diff);
                break;
            case 'month':
                startDate.setDate(1);
                break;
            case 'last7':
                startDate.setDate(startDate.getDate() - 7);
                break;
            case 'last30':
                startDate.setDate(startDate.getDate() - 30);
                break;
        }
        
        document.getElementById('filterStartDate').value = startDate.toISOString().split('T')[0];
        document.getElementById('filterEndDate').value = endDate.toISOString().split('T')[0];
        
        applyActivityFilter();
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function parseUserAgent(ua) {
        if (!ua) return '-';
        
        let browser = 'Unknown';
        if (ua.includes('Chrome') && !ua.includes('Edg')) browser = 'Chrome';
        else if (ua.includes('Firefox')) browser = 'Firefox';
        else if (ua.includes('Safari') && !ua.includes('Chrome')) browser = 'Safari';
        else if (ua.includes('Edg')) browser = 'Edge';
        else if (ua.includes('MSIE') || ua.includes('Trident')) browser = 'IE';
        
        let os = '';
        if (ua.includes('Windows NT 10.0')) os = 'Win 10';
        else if (ua.includes('Windows NT 6.3')) os = 'Win 8.1';
        else if (ua.includes('Windows NT 6.2')) os = 'Win 8';
        else if (ua.includes('Windows NT 6.1')) os = 'Win 7';
        else if (ua.includes('Mac OS X')) os = 'Mac';
        else if (ua.includes('Linux')) os = 'Linux';
        else if (ua.includes('Android')) os = 'Android';
        else if (ua.includes('iPhone') || ua.includes('iPad')) os = 'iOS';
        
        return os ? `${browser} (${os})` : browser;
    }

    function toggleExportMenu(event) {
        event.stopPropagation();
        const menu = document.getElementById('exportMenu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }

    document.addEventListener('click', function(event) {
        const menu = document.getElementById('exportMenu');
        const btn = document.getElementById('exportBtn');
        if (menu && btn && !btn.contains(event.target)) {
            menu.style.display = 'none';
        }
    });

    function exportActivity(format) {
        const startDate = document.getElementById('filterStartDate').value;
        const endDate = document.getElementById('filterEndDate').value;
        
        if (!startDate || !endDate) {
            alert('Silakan pilih tanggal terlebih dahulu');
            return;
        }
        
        const url = `<?= base_url('admin/analytics/activity-export') ?>?start_date=${startDate}&end_date=${endDate}&format=${format}`;
        window.open(url, '_blank');
        
        document.getElementById('exportMenu').style.display = 'none';
    }
    </script>

    <script src="<?= base_url('assets/js/admin.js') ?>"></script>
</body>
</html>