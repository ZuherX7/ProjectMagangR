<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - SIDODIK</title>
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

        /* Kategori table specific column widths */
        .kategori-table th:nth-child(1) { width: 5%; }    /* No */
        .kategori-table th:nth-child(2) { width: 18%; }   /* Nama Kategori */
        .kategori-table th:nth-child(3) { width: 15%; }   /* Menu */
        .kategori-table th:nth-child(4) { width: 30%; }   /* Deskripsi */
        .kategori-table th:nth-child(5) { width: 12%; }   /* Jumlah Dokumen */
        .kategori-table th:nth-child(6) { width: 10%; }   /* Waktu Upload */
        .kategori-table th:nth-child(7) { width: 7%; }    /* Status */
        .kategori-table th:nth-child(8) { width: 13%; }   /* Aksi */
        
        .kategori-table td:nth-child(1) { width: 5%; }
        .kategori-table td:nth-child(2) { width: 18%; }
        .kategori-table td:nth-child(3) { width: 15%; }
        .kategori-table td:nth-child(4) { width: 30%; }
        .kategori-table td:nth-child(5) { width: 12%; }
        .kategori-table td:nth-child(6) { width: 10%; }
        .kategori-table td:nth-child(7) { width: 7%; }
        .kategori-table td:nth-child(8) { width: 13%; }

        .kategori-name {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            word-wrap: break-word;
        }

        .menu-name {
            font-size: 13px;
            color: #0066cc;
            background: #e8f4fd;
            padding: 2px 8px;
            border-radius: 12px;
            display: inline-block;
            font-weight: 500;
        }

        .kategori-desc {
            font-size: 13px;
            color: #6c757d;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .date-created, .time-created {
            font-size: 13px;
            color: #333;
            white-space: nowrap;
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

                    <li class="nav-item active">
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
                        <h1>Kelola Kategori</h1>
                        <p>Manajemen kategori dokumen berdasarkan menu</p>
                    </div>
                </div>
                <div class="header-right">
                    <span class="breadcrumb">Dashboard > Kategori</span>
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
                        <h3 class="table-title">Daftar Kategori</h3>
                        <button type="button" class="btn btn-primary" onclick="openAddModal()">
                            <i class="fas fa-plus"></i>
                            Tambah Kategori
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table kategori-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Menu</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Dokumen</th>
                                    <th>Waktu Upload</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kategori)): ?>
                                    <?php foreach ($kategori as $key => $kat): ?>
                                        <tr>
                                            <td><?= $key + 1 ?></td>
                                            <td>
                                                <div class="kategori-info">
                                                    <div class="kategori-name"><?= esc($kat['nama_kategori']) ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="menu-name"><?= esc($kat['nama_menu'] ?? 'Menu Dihapus') ?></span>
                                            </td>
                                            <td>
                                                <div class="kategori-desc">
                                                    <?= esc($kat['deskripsi'] ?? '-') ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="doc-count">
                                                    <i class="fas fa-file"></i>
                                                    <?= $kat['jumlah_dokumen'] ?? 0 ?> dokumen
                                                </div>
                                            </td>
                                            <td>
                                                <div class="upload-time"><?= date('d M Y', strtotime($kat['created_at'])) ?></div>
                                                <div class="upload-meta"><?= date('H:i', strtotime($kat['created_at'])) ?> WIB</div>
                                            </td>
                                            <td>
                                                <?php if ($kat['status'] === 'aktif'): ?>
                                                    <span class="badge badge-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-warning btn-sm" onclick="editKategori(<?= $kat['id'] ?>, '<?= esc($kat['nama_kategori']) ?>', <?= $kat['menu_id'] ?>, '<?= esc($kat['deskripsi']) ?>', '<?= $kat['status'] ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteKategori(<?= $kat['id'] ?>, '<?= esc($kat['nama_kategori']) ?>', <?= $kat['jumlah_dokumen'] ?? 0 ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 40px;">
                                            <i class="fas fa-tags" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                                            <div>Belum ada kategori</div>
                                            <div style="font-size: 12px; color: #6c757d;">Klik tombol "Tambah Kategori" untuk membuat kategori pertama</div>
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
                <h3 class="modal-title">Tambah Kategori Baru</h3>
                <button class="close-btn" onclick="closeModal('addModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/kategori/add') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="menu_id" class="form-label">Menu *</label>
                        <select class="form-control" id="menu_id" name="menu_id" required>
                            <option value="">Pilih Menu</option>
                            <?php if (!empty($menu)): ?>
                                <?php foreach ($menu as $m): ?>
                                    <option value="<?= $m['id'] ?>"><?= esc($m['nama_menu']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nama_kategori" class="form-label">Nama Kategori *</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required maxlength="100" placeholder="Masukkan nama kategori">
                    </div>

                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" maxlength="500" placeholder="Deskripsi kategori (opsional)"></textarea>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px;">
                        <button type="button" class="btn" style="background: #6c757d; color: white;" onclick="closeModal('addModal')">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan Kategori
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
                <h3 class="modal-title">Edit Kategori</h3>
                <button class="close-btn" onclick="closeModal('editModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="edit_menu_id" class="form-label">Menu *</label>
                        <select class="form-control" id="edit_menu_id" name="menu_id" required>
                            <option value="">Pilih Menu</option>
                            <?php if (!empty($menu)): ?>
                                <?php foreach ($menu as $m): ?>
                                    <option value="<?= $m['id'] ?>"><?= esc($m['nama_menu']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_nama_kategori" class="form-label">Nama Kategori *</label>
                        <input type="text" class="form-control" id="edit_nama_kategori" name="nama_kategori" required maxlength="100" placeholder="Masukkan nama kategori">
                    </div>

                    <div class="form-group">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" maxlength="500" placeholder="Deskripsi kategori (opsional)"></textarea>
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

        // Function untuk edit kategori dengan parameter menu_id
        function editKategori(id, nama, menuId, deskripsi, status) {
            console.log('Edit kategori called with:', {id, nama, menuId, deskripsi, status});
            
            // Buka modal
            document.getElementById('editModal').classList.add('active');
            
            // Set action form
            const form = document.getElementById('editForm');
            form.action = `<?= base_url('admin/kategori/edit/') ?>${id}`;
            
            // Isi form dengan data
            document.getElementById('edit_menu_id').value = menuId || '';
            document.getElementById('edit_nama_kategori').value = nama || '';
            document.getElementById('edit_deskripsi').value = deskripsi || '';
            document.getElementById('edit_status').value = status || 'aktif';
            
            // Debug: cek nilai form
            console.log('Form values set:', {
                menu_id: document.getElementById('edit_menu_id').value,
                nama: document.getElementById('edit_nama_kategori').value,
                deskripsi: document.getElementById('edit_deskripsi').value,
                status: document.getElementById('edit_status').value,
                action: form.action
            });
        }

        // Event listener untuk form validation
        document.addEventListener('DOMContentLoaded', function() {
            // Validation untuk form add
            const addForm = document.querySelector('#addModal form');
            if (addForm) {
                addForm.addEventListener('submit', function(e) {
                    const menuId = document.getElementById('menu_id').value;
                    const namaKategori = document.getElementById('nama_kategori').value.trim();
                    
                    if (!menuId) {
                        e.preventDefault();
                        alert('Menu harus dipilih');
                        return false;
                    }
                    
                    if (namaKategori.length < 3) {
                        e.preventDefault();
                        alert('Nama kategori minimal 3 karakter');
                        return false;
                    }
                });
            }

            // Validation untuk form edit
            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    const menuId = document.getElementById('edit_menu_id').value;
                    const namaKategori = document.getElementById('edit_nama_kategori').value.trim();
                    const status = document.getElementById('edit_status').value;
                    
                    if (!menuId) {
                        e.preventDefault();
                        alert('Menu harus dipilih');
                        return false;
                    }
                    
                    if (namaKategori.length < 3) {
                        e.preventDefault();
                        alert('Nama kategori minimal 3 karakter');
                        return false;
                    }
                    
                    if (!status) {
                        e.preventDefault();
                        alert('Status harus dipilih');
                        return false;
                    }
                    
                    console.log('Form submitted with:', {
                        menu_id: menuId,
                        nama_kategori: namaKategori,
                        deskripsi: document.getElementById('edit_deskripsi').value,
                        status: status,
                        action: this.action
                    });
                });
            }
        });

        function deleteKategori(id, nama, jumlahDokumen) {
            if (jumlahDokumen > 0) {
                alert(`Kategori "${nama}" tidak dapat dihapus karena masih memiliki ${jumlahDokumen} dokumen.\n\nPindahkan atau hapus dokumen terlebih dahulu.`);
                return;
            }
            
            if (confirm(`Apakah Anda yakin ingin menghapus kategori "${nama}"?`)) {
                window.location.href = `<?= base_url('admin/kategori/delete/') ?>${id}`;
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
    <script src="<?= base_url('assets/js/admin.js') ?>"></script>
</body>
</html>