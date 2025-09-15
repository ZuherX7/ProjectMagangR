<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - SIDODIK</title>
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

        /* Table Container dengan background semi-transparent */
        .table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .table-header h3 {
            color: #2c3e50;
            font-weight: 600;
            font-size: 20px;
            margin: 0;
        }

        /* Alert Messages dengan background */
        .alert {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Modal Background Enhancement */
        .modal {
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* PERBAIKAN: Tambahan CSS untuk alert di modal */
        .modal-alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .modal-alert.alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .modal-alert.alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .modal-alert.show {
            display: block;
        }

        .form-group.error input,
        .form-group.error select {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-group.error .form-label {
            color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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
            
            .table-container {
                padding: 20px;
                margin-bottom: 20px;
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
                    
                    <li class="nav-item active">
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
                        <h1>Kelola User</h1>
                        <p>Manajemen pengguna sistem</p>
                    </div>
                </div>
                <div class="header-right">
                    <span class="breadcrumb">Dashboard > User</span>
                </div>
            </header>

            <!-- Content -->
            <div class="dashboard-content">
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
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php 
                // Tampilkan validation errors jika ada
                $validation = \Config\Services::validation();
                if ($validation->getErrors()): 
                ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php foreach ($validation->getErrors() as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Table Container -->
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Daftar User</h3>
                        <button type="button" class="btn btn-primary" onclick="openAddModal()">
                            <i class="fas fa-plus"></i>
                            Tambah User
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>NIP</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Terdaftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $key => $usr): ?>
                                        <tr>
                                            <td><?= $key + 1 ?></td>
                                            <td>
                                                <div class="user-info">
                                                    <div class="user-avatar">
                                                        <?= strtoupper(substr($usr['nama_lengkap'], 0, 1)) ?>
                                                    </div>
                                                    <div class="user-details">
                                                        <div class="user-name"><?= esc($usr['nama_lengkap']) ?></div>
                                                        <div class="user-meta">@<?= esc($usr['username']) ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= esc($usr['nip'] ?? '-') ?></td>
                                            <td>
                                                <span class="badge <?= $usr['role'] == 'admin' ? 'badge-primary' : 'badge-warning' ?>">
                                                    <?= ucfirst($usr['role']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($usr['status'] === 'aktif'): ?>
                                                    <span class="badge badge-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d M Y', strtotime($usr['created_at'])) ?></td>
                                            <td>
                                                <div style="display: flex; gap: 4px;">
                                                    <button class="btn btn-warning btn-sm" onclick="editUser(<?= $usr['id'] ?>, '<?= esc($usr['username']) ?>', '<?= esc($usr['nip']) ?>', '<?= esc($usr['nama_lengkap']) ?>', '<?= $usr['role'] ?>', '<?= $usr['status'] ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php if ($usr['id'] != session()->get('user_id')): ?>
                                                        <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $usr['id'] ?>, '<?= esc($usr['username']) ?>')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 40px;">
                                            <i class="fas fa-users" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                                            <div>Belum ada user</div>
                                            <div style="font-size: 12px; color: #6c757d;">Klik tombol "Tambah User" untuk menambahkan user pertama</div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tambah User Baru</h3>
                <button class="close-btn" onclick="closeModal('addModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/user/add') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="username" class="form-label">Username *</label>
                        <input type="text" class="form-control" id="username" name="username" required maxlength="50" placeholder="Masukkan username">
                    </div>

                    <div class="form-group">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" maxlength="30" placeholder="Nomor Induk Pegawai (User wajib isi, Admin opsional)">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6" placeholder="Minimal 6 karakter">
                    </div>

                    <div class="form-group">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap *</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required maxlength="100" placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">Role *</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px;">
                        <button type="button" class="btn" style="background: #6c757d; color: white;" onclick="closeModal('addModal')">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit User</h3>
                <button class="close-btn" onclick="closeModal('editModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="edit_username" class="form-label">Username *</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required maxlength="50">
                    </div>

                    <div class="form-group">
                        <label for="edit_nip" class="form-label">NIP</label>
                        <input type="text" class="form-control" id="edit_nip" name="nip" maxlength="30">
                    </div>

                    <div class="form-group">
                        <label for="edit_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="edit_password" name="password" minlength="6" placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>

                    <div class="form-group">
                        <label for="edit_nama_lengkap" class="form-label">Nama Lengkap *</label>
                        <input type="text" class="form-control" id="edit_nama_lengkap" name="nama_lengkap" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="edit_role" class="form-label">Role *</label>
                        <select class="form-control" id="edit_role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_status" class="form-label">Status *</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px;">
                        <button type="button" class="btn" style="background: #6c757d; color: white;" onclick="closeModal('editModal')">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function editUser(id, username, nip, nama_lengkap, role, status) {
            document.getElementById('editModal').classList.add('active');
            document.getElementById('editForm').action = `<?= base_url('admin/user/edit/') ?>${id}`;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_nip').value = nip || '';
            document.getElementById('edit_nama_lengkap').value = nama_lengkap;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_status').value = status;
        }

        function deleteUser(id, username) {
            if (confirm(`Apakah Anda yakin ingin menghapus user "${username}"?\n\nUser akan dihapus permanent dan tidak dapat dikembalikan.`)) {
                window.location.href = `<?= base_url('admin/user/delete/') ?>${id}`;
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                }
            });
        }
    </script>
</body>
</html>