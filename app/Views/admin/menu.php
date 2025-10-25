<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - SIDODIK</title>
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

                    <li class="nav-item active">
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
                        <h1>Kelola Menu</h1>
                        <p>Manajemen menu navigasi</p>
                    </div>
                </div>
                <div class="header-right">
                    <span class="breadcrumb">Dashboard > Menu</span>
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

                <!-- Table Container -->
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Daftar Menu</h3>
                        <button type="button" class="btn btn-primary" onclick="openAddModal()">
                            <i class="fas fa-plus"></i>
                            Tambah Menu
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table menu-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%; text-align: center;">No</th>
                                    <th style="width: 20%;">Menu</th>
                                    <th style="width: 25%;">Deskripsi</th>
                                    <th style="width: 15%;">Jumlah Dokumen</th>
                                    <th style="width: 15%;">Tanggal Dibuat</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($menu)): ?>
                                    <!-- UBAH: Tambah $no variable untuk counter -->
                                    <?php $no = 1; foreach ($menu as $m): ?>
                                        <tr>
                                            <!-- BARU: Kolom No dengan counter -->
                                            <td style="text-align: center; font-weight: 600; color: #000">
                                                <?= $no++ ?>
                                            </td>
                                            <td>
                                                <div class="menu-info">
                                                    <div class="menu-icon">
                                                        <?php if (!empty($m['icon'])): ?>
                                                            <i class="fas fa-<?= esc($m['icon']) ?>"></i>
                                                        <?php else: ?>
                                                            <i class="fas fa-folder"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="menu-details">
                                                        <div class="menu-name"><?= esc($m['nama_menu']) ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="menu-desc">
                                                    <?= esc($m['deskripsi'] ?? '-') ?>
                                                </p>
                                            </td>
                                            <td>
                                                <div class="doc-count">
                                                    <i class="fas fa-file"></i>
                                                    <?= $m['jumlah_dokumen'] ?? 0 ?> dokumen
                                                </div>
                                            </td>
                                            <td>
                                                <div class="date-info">
                                                    <div style="font-size: 14px; font-weight: 500;">
                                                        <?= date('d M Y', strtotime($m['created_at'])) ?>
                                                    </div>
                                                    <div style="font-size: 12px; color: #6c757d;">
                                                        <?= date('H:i', strtotime($m['created_at'])) ?> WIB
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($m['status'] === 'aktif'): ?>
                                                    <span class="badge badge-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div style="display: flex; gap: 6px; justify-content: center;">
                                                    <button class="btn btn-warning btn-sm" onclick="editMenu(<?= $m['id'] ?>, '<?= esc($m['nama_menu']) ?>', '<?= esc($m['deskripsi']) ?>', '<?= esc($m['icon']) ?>', '<?= $m['status'] ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteMenu(<?= $m['id'] ?>, '<?= esc($m['nama_menu']) ?>', <?= $m['jumlah_dokumen'] ?? 0 ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 40px;">
                                            <i class="fas fa-bars" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                                            <div>Belum ada menu</div>
                                            <div style="font-size: 12px; color: #6c757d;">Klik tombol "Tambah Menu" untuk membuat menu pertama</div>
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
                <h3 class="modal-title">Tambah Menu Baru</h3>
                <button class="close-btn" onclick="closeModal('addModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/menu/add') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="nama_menu" class="form-label">Nama Menu *</label>
                        <input type="text" class="form-control" id="nama_menu" name="nama_menu" required maxlength="100" placeholder="Masukkan nama menu">
                    </div>

                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" maxlength="500" placeholder="Deskripsi menu (opsional)"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="icon" class="form-label">Icon (FontAwesome)</label>
                        <input type="text" class="form-control" id="icon" name="icon" maxlength="50" placeholder="Contoh: book, file-alt, envelope" onkeyup="previewIcon(this.value, 'icon-demo-add')">
                        <div class="icon-preview">
                            <div class="icon-demo" id="icon-demo-add">
                                <i class="fas fa-folder"></i>
                            </div>
                            <span>Preview icon (kosongkan untuk icon default)</span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px;">
                        <button type="button" class="btn" style="background: #6c757d; color: white;" onclick="closeModal('addModal')">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan Menu
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
                <h3 class="modal-title">Edit Menu</h3>
                <button class="close-btn" onclick="closeModal('editModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="edit_nama_menu" class="form-label">Nama Menu *</label>
                        <input type="text" class="form-control" id="edit_nama_menu" name="nama_menu" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" maxlength="500"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="edit_icon" class="form-label">Icon (FontAwesome)</label>
                        <input type="text" class="form-control" id="edit_icon" name="icon" maxlength="50" placeholder="Contoh: book, file-alt, envelope" onkeyup="previewIcon(this.value, 'icon-demo-edit')">
                        <div class="icon-preview">
                            <div class="icon-demo" id="icon-demo-edit">
                                <i class="fas fa-folder"></i>
                            </div>
                            <span>Preview icon (kosongkan untuk icon default)</span>
                        </div>
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

        function editMenu(id, nama, deskripsi, icon, status) {
            document.getElementById('editModal').classList.add('active');
            document.getElementById('editForm').action = `<?= base_url('admin/menu/edit/') ?>${id}`;
            document.getElementById('edit_nama_menu').value = nama;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('edit_icon').value = icon;
            document.getElementById('edit_status').value = status;
            
            previewIcon(icon || '', 'icon-demo-edit');
            
            console.log('Edit data:', {id, nama, deskripsi, icon, status});
        }

        function deleteMenu(id, nama, jumlahDokumen) {
            if (jumlahDokumen > 0) {
                alert(`Menu "${nama}" tidak dapat dihapus karena masih memiliki ${jumlahDokumen} dokumen.\n\nPindahkan atau hapus dokumen terlebih dahulu.`);
                return;
            }
            
            if (confirm(`Apakah Anda yakin ingin menghapus menu "${nama}"?`)) {
                window.location.href = `<?= base_url('admin/menu/delete/') ?>${id}`;
            }
        }

        function previewIcon(iconName, demoId) {
            const demo = document.getElementById(demoId);
            if (iconName && iconName.trim() !== '') {
                demo.innerHTML = `<i class="fas fa-${iconName}"></i>`;
            } else {
                demo.innerHTML = '<i class="fas fa-folder"></i>';
            }
        }

        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                }
            });
        }
    </script>
    <script src="<?= base_url('assets/js/admin.js') ?>"></script>
</body>
</html>