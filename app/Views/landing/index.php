<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIDODIK - Sistem Informasi Dokumen Diskominfotik</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Landing Page Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* Navigation - Samakan dengan dashboard user */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .navbar-brand img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .navbar-nav {
            display: flex;
            list-style: none;
            gap: 25px;
            align-items: center;
        }

        .navbar-nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-nav a:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
        }

        .login-btn, .user-info {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-btn:hover, .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .user-greeting {
            color: white;
            font-size: 14px;
            margin-right: 15px;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c82333;
            transform: translateY(-1px);
            color: white;
            text-decoration: none;
        }

        /* Hero Section */
        .hero-section {
            background: url('<?= base_url("assets/img/gedung2.png") ?>') center center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding-top: 80px;
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
            color: white;
            max-width: 900px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .hero-content h1 {
            font-size: 3.8rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
            letter-spacing: -0.02em;
        }

        .hero-content .subtitle {
            font-size: 1.4rem;
            margin-bottom: 15px;
            opacity: 0.95;
            font-weight: 600;
        }

        .hero-content .description {
            font-size: 1.1rem;
            margin-bottom: 50px;
            opacity: 0.9;
            line-height: 1.8;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero {
            padding: 18px 35px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .btn-primary-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary-hero {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(10px);
        }

        .btn-hero:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            color: white;
            text-decoration: none;
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 70px;
        }

        .section-title h2 {
            font-size: 3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .section-title p {
            font-size: 1.2rem;
            color: #666;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.8;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .feature-card {
            background: white;
            padding: 45px 35px;
            border-radius: 25px;
            text-align: center;
            box-shadow: 0 10px 35px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 85px;
            height: 85px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.2rem;
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .feature-card h3 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 18px;
            color: #2c3e50;
        }

        .feature-card p {
            color: #666;
            line-height: 1.7;
            font-size: 1rem;
        }

        /* Pengaduan Section */
        .pengaduan-section {
            padding: 100px 0;
            background: white;
        }

        .pengaduan-container {
            max-width: 900px;
            margin: 0 auto;
            background: #f8f9fa;
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 50px;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        /* Status Check Section */
        .status-section {
            background: #2c3e50;
            color: white;
            padding: 80px 0;
        }

        .status-form {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            gap: 20px;
            align-items: stretch;
        }

        .status-form input {
            flex: 1;
            padding: 18px 25px;
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 30px;
            font-size: 16px;
            background: rgba(255,255,255,0.1);
            color: white;
            backdrop-filter: blur(10px);
        }

        .status-form input::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .status-form input:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255,255,255,0.15);
        }

        .status-form button {
            background: #667eea;
            color: white;
            border: none;
            padding: 18px 30px;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .status-form button:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        /* Alert Styles */
        .alert {
            padding: 20px 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            font-weight: 500;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c8e6c9 100%);
            color: #1b5e20;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #ffcdd2 100%);
            color: #c62828;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 70px 0 40px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 50px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: white;
        }

        .footer-section p,
        .footer-section a {
            color: #bdc3c7;
            text-decoration: none;
            line-height: 1.8;
            margin-bottom: 8px;
            display: block;
            transition: all 0.3s ease;
        }

        .footer-section a:hover {
            color: #667eea;
            padding-left: 5px;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid #34495e;
            color: #bdc3c7;
            font-size: 0.9rem;
        }

        /* Status Modal Styles */
        .status-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-modal-content {
            background: white;
            border-radius: 20px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .status-modal-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
        }

        .status-modal-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .status-modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            padding: 0;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .status-modal-close:hover {
            background: rgba(255,255,255,0.2);
        }

        .status-modal-body {
            padding: 30px;
        }

        /* Status Content Styles */
        .status-content {
            text-align: center;
        }

        .status-ticket {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            font-family: monospace;
        }

        .status-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }

        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .status-row:last-child {
            border-bottom: none;
        }

        .status-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .status-value {
            color: #5a6c7d;
        }

        .status-badge-large {
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
            margin: 20px 0;
            display: inline-block;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-proses { background: #d1ecf1; color: #0c5460; }
        .status-selesai { background: #d4edda; color: #155724; }
        .status-ditolak { background: #f8d7da; color: #721c24; }

        .status-description {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            margin: 15px 0;
            text-align: left;
            line-height: 1.6;
            color: #2c3e50;
        }

        .admin-response {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            color: #1565c0;
        }

        .error-message {
            color: #dc3545;
            text-align: center;
            padding: 20px;
            background: #f8d7da;
            border-radius: 10px;
            border: 1px solid #f1b0b7;
        }

        .loading-message {
            color: #667eea;
            text-align: center;
            padding: 20px;
            font-weight: 500;
        }

        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Tag Suggestions */
        .tag-suggestions,
        .keyword-tags {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .tag-help {
            display: block;
            color: #666;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .popular-tags,
        .keyword-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .suggest-tag,
        .keyword-tag {
            background: white;
            border: 2px solid #e9ecef;
            color: #667eea;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .suggest-tag:hover,
        .keyword-tag:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .suggest-tag i {
            font-size: 11px;
        }

        .keyword-tag.selected {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .selected-keywords {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .selected-keyword {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .selected-keyword .remove-keyword {
            cursor: pointer;
            font-weight: bold;
            margin-left: 5px;
        }

        .selected-keyword .remove-keyword:hover {
            color: #ffc107;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.8rem;
            }
            
            .hero-content .subtitle {
                font-size: 1.2rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-hero {
                width: 100%;
                max-width: 350px;
                justify-content: center;
            }
            
            .navbar-nav {
                display: none;
            }
            
            .status-form {
                flex-direction: column;
                gap: 15px;
            }
            
            .pengaduan-container {
                margin: 0 20px;
                padding: 40px 25px;
            }
            
            .section-title h2 {
                font-size: 2.2rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .status-modal-content {
                width: 95%;
                margin: 20px;
            }
            
            .status-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .status-modal-body {
                padding: 20px;
            }
            
            .popular-tags,
            .keyword-list {
                flex-direction: column;
            }
            
            .suggest-tag,
            .keyword-tag {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .hero-content {
                padding: 40px 15px;
            }
            
            .hero-content h1 {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="<?= base_url('/') ?>" class="navbar-brand">
                <img src="<?= base_url('assets/img/kbb.png') ?>" alt="Logo KBB">
                SIDODIK
            </a>
            <ul class="navbar-nav">
                <li><a href="<?= base_url('/') ?>">Beranda</a></li>
                <li><a href="<?= base_url('/dokumen-publik') ?>">Dokumen</a></li>
                <li><a href="#pengaduan">Pengaduan</a></li>
                <li><a href="<?= base_url('user/dashboard') ?>">Dashboard User</a></li>
            </ul>
            <?php if (session()->get('logged_in')): ?>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span class="user-greeting">Selamat datang, <strong><?= esc(session()->get('nama_lengkap')) ?></strong></span>
                    <a href="<?= base_url('auth/logout') ?>" class="logout-btn">Logout</a>
                </div>
            <?php else: ?>
                <a href="<?= base_url('/login') ?>" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>SIDODIK</h1>
            <p class="subtitle">Sistem Informasi Dokumen Diskominfotik</p>
            <p class="description">Kabupaten Bandung Barat - Akses mudah dan transparan untuk semua dokumen resmi pemerintahan dengan sistem yang terintegrasi dan user-friendly</p>
            
            <div class="hero-buttons">
                <a href="#pengaduan" class="btn-hero btn-primary-hero">
                    <i class="fas fa-paper-plane"></i>
                    Ajukan Permintaan Dokumen
                </a>
                <a href="<?= base_url('/dokumen-publik') ?>" class="btn-hero btn-secondary-hero">
                    <i class="fas fa-search"></i>
                    Jelajahi Dokumen
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-title">
                <h2>Layanan SIDODIK</h2>
                <p>Sistem yang dirancang untuk memudahkan akses dan pengelolaan dokumen pemerintah dengan teknologi terdepan</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Pencarian Dokumen</h3>
                    <p>Temukan dokumen yang Anda butuhkan dengan sistem pencarian yang canggih, akurat, dan responsif dalam hitungan detik</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h3>Permintaan Dokumen</h3>
                    <p>Ajukan permintaan untuk dokumen yang belum tersedia dengan sistem tracking yang transparan dan notifikasi real-time</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <h3>Download Mudah</h3>
                    <p>Download dokumen resmi dengan format asli, kualitas terjamin, dan sistem keamanan yang terintegrasi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pengaduan Section -->
    <section id="pengaduan" class="pengaduan-section">
        <div class="container">
            <div class="section-title">
                <h2>Ajukan Permintaan Dokumen</h2>
                <p>Tidak menemukan dokumen yang Anda butuhkan? Ajukan permintaan dan tim kami akan membantu Anda dengan segera</p>
            </div>
            
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

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul style="margin: 15px 0 0 25px; padding: 0;">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="pengaduan-container">
                <form action="<?= base_url('/pengaduan/submit') ?>" method="POST" id="pengaduanForm">
                    <?= csrf_field() ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                value="<?= old('nama') ?>" required maxlength="100" 
                                placeholder="Masukkan nama lengkap Anda">
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                value="<?= old('email') ?>" required maxlength="100" 
                                placeholder="email@example.com">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telepon" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="telepon" name="telepon" 
                                value="<?= old('telepon') ?>" maxlength="20" 
                                placeholder="08xxxxxxxxx">
                        </div>
                        
                        <div class="form-group">
                            <label for="jenis_pemohon" class="form-label">Jenis Pemohon *</label>
                            <select class="form-control" id="jenis_pemohon" name="jenis_pemohon" required onchange="toggleNipField()">
                                <option value="">Pilih jenis pemohon</option>
                                <option value="publik" <?= old('jenis_pemohon') == 'publik' ? 'selected' : '' ?>>User Publik</option>
                                <option value="asn" <?= old('jenis_pemohon') == 'asn' ? 'selected' : '' ?>>ASN (Aparatur Sipil Negara)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group" id="nip-field" style="display: none;">
                            <label for="nip" class="form-label">NIP *</label>
                            <input type="text" class="form-control" id="nip" name="nip" 
                                value="<?= old('nip') ?>" maxlength="20" 
                                placeholder="Nomor Induk Pegawai (18 digit)"
                                pattern="[0-9]{18,20}" title="NIP harus berupa angka 18-20 digit">
                        </div>
                        
                        <div class="form-group">
                            <label for="instansi" class="form-label">Instansi/Organisasi</label>
                            <input type="text" class="form-control" id="instansi" name="instansi" 
                                value="<?= old('instansi') ?>" maxlength="100" 
                                placeholder="Nama instansi (opsional)">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Contoh Dokumen yang Sering Diminta</label>
                        <div class="tag-suggestions">
                            <small class="tag-help">Klik tag untuk mengisi judul dokumen:</small>
                            <div class="popular-tags">
                                <button type="button" class="suggest-tag" onclick="fillJudul('Surat Keterangan')">
                                    <i class="fas fa-tag"></i> Surat Keterangan
                                </button>
                                <button type="button" class="suggest-tag" onclick="fillJudul('Laporan Keuangan')">
                                    <i class="fas fa-tag"></i> Laporan Keuangan
                                </button>
                                <button type="button" class="suggest-tag" onclick="fillJudul('Formulir Pendaftaran')">
                                    <i class="fas fa-tag"></i> Formulir Pendaftaran
                                </button>
                                <button type="button" class="suggest-tag" onclick="fillJudul('SK Pengangkatan')">
                                    <i class="fas fa-tag"></i> SK Pengangkatan
                                </button>
                                <button type="button" class="suggest-tag" onclick="fillJudul('Panduan Teknis')">
                                    <i class="fas fa-tag"></i> Panduan Teknis
                                </button>
                                <button type="button" class="suggest-tag" onclick="fillJudul('Surat Edaran')">
                                    <i class="fas fa-tag"></i> Surat Edaran
                                </button>
                                <button type="button" class="suggest-tag" onclick="fillJudul('Laporan Tahunan')">
                                    <i class="fas fa-tag"></i> Laporan Tahunan
                                </button>
                                <button type="button" class="suggest-tag" onclick="fillJudul('Dokumen Anggaran')">
                                    <i class="fas fa-tag"></i> Dokumen Anggaran
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="judul_dokumen" class="form-label">Judul/Nama Dokumen yang Dibutuhkan *</label>
                        <input type="text" class="form-control" id="judul_dokumen" name="judul_dokumen" 
                            value="<?= old('judul_dokumen') ?>" required maxlength="200" 
                            placeholder="Contoh: SK Pengangkatan PNS, Laporan APBD 2024">
                    </div>
                    
                    <div class="form-group">
                        <label for="deskripsi_kebutuhan" class="form-label">Deskripsi Kebutuhan *</label>
                        <textarea class="form-control" id="deskripsi_kebutuhan" name="deskripsi_kebutuhan" 
                                rows="4" required maxlength="1000" 
                                placeholder="Jelaskan secara detail dokumen apa yang Anda butuhkan, untuk keperluan apa, dan informasi tambahan lainnya..."><?= old('deskripsi_kebutuhan') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Kata Kunci Tambahan (Opsional)</label>
                        <div class="keyword-tags">
                            <small class="tag-help">Pilih kata kunci yang relevan:</small>
                            <div class="keyword-list">
                                <button type="button" class="keyword-tag" onclick="addKeyword('Mendesak')">Mendesak</button>
                                <button type="button" class="keyword-tag" onclick="addKeyword('Administrasi')">Administrasi</button>
                                <button type="button" class="keyword-tag" onclick="addKeyword('Kepegawaian')">Kepegawaian</button>
                                <button type="button" class="keyword-tag" onclick="addKeyword('Keuangan')">Keuangan</button>
                                <button type="button" class="keyword-tag" onclick="addKeyword('Pengadaan')">Pengadaan</button>
                                <button type="button" class="keyword-tag" onclick="addKeyword('Perizinan')">Perizinan</button>
                                <button type="button" class="keyword-tag" onclick="addKeyword('Pelaporan')">Pelaporan</button>
                                <button type="button" class="keyword-tag" onclick="addKeyword('Teknis')">Teknis</button>
                            </div>
                            <input type="hidden" id="keywords" name="keywords" value="">
                            <div id="selected-keywords" class="selected-keywords"></div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="kategori_permintaan" class="form-label">Kategori Dokumen *</label>
                            <select class="form-control" id="kategori_permintaan" name="kategori_permintaan" required>
                                <option value="">Pilih kategori</option>
                                <option value="surat" <?= old('kategori_permintaan') == 'surat' ? 'selected' : '' ?>>Surat</option>
                                <option value="laporan" <?= old('kategori_permintaan') == 'laporan' ? 'selected' : '' ?>>Laporan</option>
                                <option value="formulir" <?= old('kategori_permintaan') == 'formulir' ? 'selected' : '' ?>>Formulir</option>
                                <option value="panduan" <?= old('kategori_permintaan') == 'panduan' ? 'selected' : '' ?>>Panduan/Manual</option>
                                <option value="lainnya" <?= old('kategori_permintaan') == 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="urgency" class="form-label">Tingkat Urgensi *</label>
                            <select class="form-control" id="urgency" name="urgency" required>
                                <option value="">Pilih urgensi</option>
                                <option value="rendah" <?= old('urgency') == 'rendah' ? 'selected' : '' ?>>Rendah</option>
                                <option value="sedang" <?= old('urgency') == 'sedang' ? 'selected' : '' ?>>Sedang</option>
                                <option value="tinggi" <?= old('urgency') == 'tinggi' ? 'selected' : '' ?>>Tinggi</option>
                                <option value="sangat_tinggi" <?= old('urgency') == 'sangat_tinggi' ? 'selected' : '' ?>>Sangat Tinggi</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Permintaan
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Status Check Section -->
    <section class="status-section">
        <div class="container">
            <div class="section-title">
                <h2 style="color: white;">Cek Status Permintaan</h2>
                <p style="color: rgba(255,255,255,0.9);">Masukkan nomor tiket untuk melihat status permintaan dokumen Anda</p>
            </div>
            
            <div class="status-form">
                <input type="text" id="ticketInput" placeholder="Masukkan nomor tiket (REQ-20241201-0001)" 
                    value="<?= isset($_GET['ticket']) ? esc($_GET['ticket']) : '' ?>">
                <button type="button" onclick="cekStatusPengaduan()">
                    <i class="fas fa-search"></i>
                    Cek Status
                </button>
            </div>
        </div>
    </section>

    <!-- Status Modal -->
    <div id="statusModal" class="status-modal" style="display: none;">
        <div class="status-modal-content">
            <div class="status-modal-header">
                <h3>Status Pengaduan</h3>
                <button class="status-modal-close" onclick="closeStatusModal()">&times;</button>
            </div>
            <div class="status-modal-body" id="statusModalBody">
                <!-- Content akan dimuat di sini -->
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>SIDODIK</h3>
                    <p>Sistem Informasi Dokumen Diskominfotik Kabupaten Bandung Barat. Menyediakan akses mudah dan transparan untuk semua dokumen resmi pemerintahan dengan standar keamanan tinggi.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Menu</h3>
                    <a href="<?= base_url('/') ?>">Beranda</a>
                    <a href="<?= base_url('/dokumen-publik') ?>">Dokumen Publik</a>
                    <a href="#pengaduan">Pengaduan Dokumen</a>
                    <a href="<?= base_url('/about') ?>">Tentang Kami</a>
                </div>
                
                <div class="footer-section">
                    <h3>Kontak</h3>
                    <p>Diskominfotik Kabupaten Bandung Barat</p>
                    <p>Email: info@bandungbaratkab.go.id</p>
                    <p>Telepon: (022) 6866xxx</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 SIDODIK - Diskominfotik Kabupaten Bandung Barat. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Parallax effect for hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            const heroSection = document.querySelector('.hero-section');
            if (heroSection) {
                heroSection.style.backgroundPosition = 'center ' + rate + 'px';
            }
        });

        function cekStatusPengaduan() {
            const ticketNumber = document.getElementById('ticketInput').value.trim();
            
            if (!ticketNumber) {
                alert('Mohon masukkan nomor tiket');
                return;
            }
            
            // Show modal with loading
            showStatusModal();
            document.getElementById('statusModalBody').innerHTML = `
                <div class="loading-message">
                    <div class="loading-spinner"></div>
                    Mencari status pengaduan...
                </div>
            `;
            
            // Fetch status from server
            fetch('<?= base_url("/api/status") ?>?ticket=' + encodeURIComponent(ticketNumber))
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('statusModalBody').innerHTML = `
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                <h4>Tiket Tidak Ditemukan</h4>
                                <p>${data.message}</p>
                            </div>
                        `;
                    } else {
                        showStatusContent(data.data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('statusModalBody').innerHTML = `
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <h4>Terjadi Kesalahan</h4>
                            <p>Tidak dapat mengakses sistem saat ini. Silakan coba lagi nanti.</p>
                        </div>
                    `;
                });
        }

        function showStatusModal() {
            document.getElementById('statusModal').style.display = 'flex';
        }

        function closeStatusModal() {
            document.getElementById('statusModal').style.display = 'none';
        }

        function showStatusContent(data) {
            const statusLabels = {
                'pending': 'Menunggu Diproses',
                'proses': 'Diproses', 
                'selesai': 'Selesai',
                'ditolak': 'Ditolak'
            };
            
            const urgencyLabels = {
                'rendah': 'Rendah',
                'sedang': 'Sedang',
                'tinggi': 'Tinggi',
                'sangat_tinggi': 'Sangat Tinggi'
            };
            
            const jenisPemohonLabels = {
                'publik': 'User Publik',
                'asn': 'ASN (Aparatur Sipil Negara)'
            };
            
            let content = `
                <div class="status-content">
                    <div class="status-ticket">${data.ticket_number}</div>
                    <div class="status-badge-large status-${data.status}">
                        ${statusLabels[data.status] || data.status}
                    </div>
                    
                    <div class="status-info">
                        <div class="status-row">
                            <span class="status-label">Pemohon:</span>
                            <span class="status-value">${data.nama}</span>
                        </div>
                        <div class="status-row">
                            <span class="status-label">Email:</span>
                            <span class="status-value">${data.email}</span>
                        </div>
                        <div class="status-row">
                            <span class="status-label">Jenis Pemohon:</span>
                            <span class="status-value">${jenisPemohonLabels[data.jenis_pemohon] || data.jenis_pemohon}</span>
                        </div>`;
            
            if (data.nip) {
                content += `
                        <div class="status-row">
                            <span class="status-label">NIP:</span>
                            <span class="status-value">${data.nip}</span>
                        </div>`;
            }
            
            content += `
                        <div class="status-row">
                            <span class="status-label">Dokumen Diminta:</span>
                            <span class="status-value">${data.judul_dokumen}</span>
                        </div>
                        <div class="status-row">
                            <span class="status-label">Kategori:</span>
                            <span class="status-value">${data.kategori_permintaan}</span>
                        </div>
                        <div class="status-row">
                            <span class="status-label">Urgensi:</span>
                            <span class="status-value">${urgencyLabels[data.urgency] || data.urgency}</span>
                        </div>
                        <div class="status-row">
                            <span class="status-label">Tanggal Pengajuan:</span>
                            <span class="status-value">${new Date(data.created_at).toLocaleDateString('id-ID', {
                                year: 'numeric', month: 'long', day: 'numeric',
                                hour: '2-digit', minute: '2-digit'
                            })}</span>
                        </div>
                    </div>
                    
                    <div class="status-description">
                        <strong>Deskripsi Kebutuhan:</strong><br>
                        ${data.deskripsi_kebutuhan.replace(/\n/g, '<br>')}
                    </div>
            `;
            
            if (data.admin_response) {
                content += `
                    <div class="status-description admin-response">
                        <strong>Respon Admin:</strong><br>
                        ${data.admin_response.replace(/\n/g, '<br>')}
                        <br><small>Direspon pada: ${new Date(data.tanggal_respon).toLocaleDateString('id-ID', {
                            year: 'numeric', month: 'long', day: 'numeric',
                            hour: '2-digit', minute: '2-digit'
                        })}</small>
                    </div>
                `;
            }
            
            content += '</div>';
            
            document.getElementById('statusModalBody').innerHTML = content;
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('statusModal');
            if (event.target === modal) {
                closeStatusModal();
            }
        });

        // Handle enter key in input
        document.addEventListener('DOMContentLoaded', function() {
            const ticketInput = document.getElementById('ticketInput');
            if (ticketInput) {
                ticketInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        cekStatusPengaduan();
                    }
                });
            }
        });
        
        function toggleNipField() {
            const jenisPemohon = document.getElementById('jenis_pemohon').value;
            const nipField = document.getElementById('nip-field');
            const nipInput = document.getElementById('nip');
            
            if (jenisPemohon === 'asn') {
                nipField.style.display = 'block';
                nipInput.required = true;
            } else {
                nipField.style.display = 'none';
                nipInput.required = false;
                nipInput.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleNipField();
            
            document.querySelector('form').addEventListener('submit', function(e) {
                const jenisPemohon = document.getElementById('jenis_pemohon').value;
                const nipInput = document.getElementById('nip');
                
                if (jenisPemohon === 'asn') {
                    if (!nipInput.value || nipInput.value.length < 18) {
                        e.preventDefault();
                        alert('NIP harus diisi dan minimal 18 digit untuk ASN');
                        nipInput.focus();
                        return false;
                    }
                    
                    if (!/^\d+$/.test(nipInput.value)) {
                        e.preventDefault();
                        alert('NIP harus berupa angka');
                        nipInput.focus();
                        return false;
                    }
                }
                
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.style.borderColor = '#e74c3c';
                        isValid = false;
                    } else {
                        field.style.borderColor = '#e9ecef';
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi (*)');
                }
            });
        });

        function fillJudul(text) {
            document.getElementById('judul_dokumen').value = text;
            document.getElementById('judul_dokumen').focus();
            
            const input = document.getElementById('judul_dokumen');
            input.style.borderColor = '#28a745';
            setTimeout(() => {
                input.style.borderColor = '';
            }, 1000);
        }

        let selectedKeywords = [];

        function addKeyword(keyword) {
            const button = event.target;
            
            if (selectedKeywords.includes(keyword)) {
                selectedKeywords = selectedKeywords.filter(k => k !== keyword);
                button.classList.remove('selected');
            } else {
                selectedKeywords.push(keyword);
                button.classList.add('selected');
            }
            
            updateSelectedKeywords();
        }

        function updateSelectedKeywords() {
            const container = document.getElementById('selected-keywords');
            const hiddenInput = document.getElementById('keywords');
            
            if (selectedKeywords.length === 0) {
                container.innerHTML = '';
                hiddenInput.value = '';
                return;
            }
            
            container.innerHTML = selectedKeywords.map(keyword => `
                <span class="selected-keyword">
                    ${keyword}
                    <span class="remove-keyword" onclick="removeKeyword('${keyword}')">&times;</span>
                </span>
            `).join('');
            
            hiddenInput.value = selectedKeywords.join(', ');
        }

        function removeKeyword(keyword) {
            selectedKeywords = selectedKeywords.filter(k => k !== keyword);
            
            const buttons = document.querySelectorAll('.keyword-tag');
            buttons.forEach(btn => {
                if (btn.textContent.trim() === keyword) {
                    btn.classList.remove('selected');
                }
            });
            
            updateSelectedKeywords();
        }

        document.getElementById('pengaduanForm').addEventListener('submit', function(e) {
            if (selectedKeywords.length > 0) {
                const deskripsi = document.getElementById('deskripsi_kebutuhan');
                const currentDesc = deskripsi.value.trim();
                const keywordText = '\n\nKata Kunci: ' + selectedKeywords.join(', ');
                
                if (!currentDesc.includes('Kata Kunci:')) {
                    deskripsi.value = currentDesc + keywordText;
                }
            }
        });
    </script>
</body>
</html>