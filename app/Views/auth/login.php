<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIDODIK</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <style>
        /* Tambahkan ke auth.css atau di dalam <style> tag di login.php */
        body {
            background: url('<?= base_url("assets/img/gedung2.png") ?>') center center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8); /* Overlay lebih gelap untuk menggelapkan gambar */
            z-index: -1;
        }

        /* Update login-container tanpa overlay tambahan */
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            position: relative;
        }

        .login-wrapper {
            background: rgba(255, 255, 255, 0.95); /* Semi-transparent white */
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: none; /* Hilangkan bayangan */
            max-width: 350px;
            width: 100%;
            max-height: 95vh;
        }

        .logo-img {
            width: 60px;
            height: 60px;
        }

        .app-title {
            font-size: 22px;
            margin-bottom: 0;
        }

        .logo-section {
            margin-bottom: 20px;
        }

        .tab-nav {
            margin-bottom: 20px;
        }

        .tab-btn {
            padding: 8px 12px;
            font-size: 13px;
        }

        .form-title {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            padding: 10px 12px;
            font-size: 13px;
        }

        .btn-submit {
            padding: 12px;
            font-size: 14px;
            margin-top: 5px;
        }

        .forgot-password-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            font-size: 13px;
            cursor: pointer;
        }

        .forgot-password-link:hover {
            text-decoration: underline;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            backdrop-filter: blur(3px);
        }

        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            max-width: 400px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .modal-title {
            color: #2c3e50;
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: #6c757d;
            cursor: pointer;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: rgba(108, 117, 125, 0.1);
            color: #495057;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .modal-description {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .modal-footer {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        /* Animation */
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .modal.show {
            animation: modalFadeIn 0.3s ease-out;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-wrapper">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo">
                    <img src="<?= base_url('assets/img/kbb.png') ?>" alt="Logo Bandung Barat" class="logo-img">
                </div>
                <h2 class="app-title">SIDODIK</h2>
            </div>

            <!-- Tab Navigation -->
            <div class="tab-nav" id="tabNav">
                <button type="button" class="tab-btn active" data-tab="user">User Login</button>
                <button type="button" class="tab-btn" data-tab="admin">Admin Login</button>
            </div>

            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <!-- User Login Form -->
            <div class="tab-content active" id="user-tab">
                <form action="<?= base_url('auth/processLogin') ?>" method="POST" class="login-form">
                    <?= csrf_field() ?>
                    <input type="hidden" name="login_type" value="user">
                    
                    <h3 class="form-title">User Login</h3>
                    
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" 
                               class="form-control <?= (isset($errors['nip'])) ? 'is-invalid' : '' ?>" 
                               id="nip" 
                               name="nip" 
                               placeholder="Enter your NIP"
                               value="<?= old('nip') ?>" 
                               required>
                        <?php if (isset($errors['nip'])): ?>
                            <div class="invalid-feedback"><?= $errors['nip'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="user-password">Password</label>
                        <input type="password" 
                               class="form-control <?= (isset($errors['password'])) ? 'is-invalid' : '' ?>" 
                               id="user-password" 
                               name="password" 
                               placeholder="Enter your password"
                               required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?= $errors['password'] ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-submit">Submit</button>
                    
                    <!-- Forgot Password Link for Users -->
                    <a href="#" class="forgot-password-link" onclick="showForgotPasswordModal()">Lupa Password?</a>
                </form>
            </div>

            <!-- Admin Login Form -->
            <div class="tab-content" id="admin-tab">
                <form action="<?= base_url('auth/processLogin') ?>" method="POST" class="login-form">
                    <?= csrf_field() ?>
                    <input type="hidden" name="login_type" value="admin">
                    
                    <h3 class="form-title">Administrator Login</h3>
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" 
                               class="form-control <?= (isset($errors['username'])) ? 'is-invalid' : '' ?>" 
                               id="username" 
                               name="username" 
                               placeholder="Enter your username"
                               value="<?= old('username') ?>" 
                               required>
                        <?php if (isset($errors['username'])): ?>
                            <div class="invalid-feedback"><?= $errors['username'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="admin-password">Password</label>
                        <input type="password" 
                               class="form-control <?= (isset($errors['password'])) ? 'is-invalid' : '' ?>" 
                               id="admin-password" 
                               name="password" 
                               placeholder="Enter your password"
                               required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?= $errors['password'] ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal-overlay" id="forgotPasswordModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Reset Password</h3>
                <button type="button" class="modal-close" onclick="closeForgotPasswordModal()">&times;</button>
            </div>
            
            <form action="<?= base_url('auth/forgotPassword') ?>" method="POST">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    
                    <p class="modal-description">Masukkan NIP dan password baru Anda untuk reset password</p>
                    
                    <div class="form-group">
                        <label for="forgot-nip">NIP</label>
                        <input type="text" 
                               class="form-control" 
                               id="forgot-nip" 
                               name="nip" 
                               placeholder="Enter your NIP"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="new-password">Password Baru</label>
                        <input type="password" 
                               class="form-control" 
                               id="new-password" 
                               name="new_password" 
                               placeholder="Masukkan password baru"
                               minlength="6"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Konfirmasi Password</label>
                        <input type="password" 
                               class="form-control" 
                               id="confirm-password" 
                               name="confirm_password" 
                               placeholder="Konfirmasi password baru"
                               minlength="6"
                               required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeForgotPasswordModal()">Batal</button>
                    <button type="submit" class="btn-submit" style="width: auto; margin-top: 0;">Reset Password</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= base_url('assets/js/auth.js') ?>"></script>
    <script>
        function showForgotPasswordModal() {
            const modal = document.getElementById('forgotPasswordModal');
            const modalContent = modal.querySelector('.modal');
            
            modal.style.display = 'block';
            
            // Add show class for animation
            setTimeout(() => {
                modalContent.classList.add('show');
            }, 10);
            
            // Focus on first input
            setTimeout(() => {
                document.getElementById('forgot-nip').focus();
            }, 100);
        }

        function closeForgotPasswordModal() {
            const modal = document.getElementById('forgotPasswordModal');
            const modalContent = modal.querySelector('.modal');
            
            modalContent.classList.remove('show');
            
            setTimeout(() => {
                modal.style.display = 'none';
                
                // Reset form
                const form = modal.querySelector('form');
                form.reset();
                
                // Remove any error states
                const inputs = form.querySelectorAll('.form-control');
                inputs.forEach(input => {
                    input.classList.remove('is-invalid');
                });
            }, 300);
        }

        // Close modal when clicking outside
        document.getElementById('forgotPasswordModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeForgotPasswordModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('forgotPasswordModal');
                if (modal.style.display === 'block') {
                    closeForgotPasswordModal();
                }
            }
        });

        // Validation for password confirmation in modal
        document.getElementById('confirm-password').addEventListener('input', function() {
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = this.value;
            
            if (newPassword !== confirmPassword) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>