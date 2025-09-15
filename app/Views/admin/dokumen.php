<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Dokumen - SIDODIK</title>
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
                    
                    <li class="nav-item active">
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
                        <h1>Kelola Dokumen</h1>
                        <p>Manajemen dokumen dan file</p>
                    </div>
                </div>
                <div class="header-right">
                    <span class="breadcrumb">Dashboard > Dokumen</span>
                </div>
            </header>

            <!-- Content -->
            <!-- Content -->
            <div class="dashboard-content">
                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error') && !strpos(session()->getFlashdata('error'), 'Unknown column')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Table Container -->
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Daftar Dokumen</h3>
                        <button type="button" class="btn btn-primary" onclick="openAddModal()">
                            <i class="fas fa-plus"></i>
                            Tambah Dokumen
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Dokumen</th>
                                    <th>Kategori</th>
                                    <th>Menu</th>
                                    <th>File</th>
                                    <th>Waktu Upload</th>
                                    <th>Statistik</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($dokumen)): ?>
                                    <?php foreach ($dokumen as $key => $doc): ?>
                                        <tr>
                                            <td><?= $key + 1 ?></td>
                                            <td>
                                                <div class="document-title"><?= esc($doc['judul']) ?></div>
                                                <?php if (!empty($doc['deskripsi'])): ?>
                                                    <div class="document-meta"><?= esc(substr($doc['deskripsi'], 0, 100)) ?><?= strlen($doc['deskripsi']) > 100 ? '...' : '' ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($doc['nama_kategori']) ?></td>
                                            <td><?= esc($doc['nama_menu']) ?></td>
                                            <td>
                                                <div class="file-info">
                                                    <div class="file-icon <?= strtolower($doc['file_type']) ?>">
                                                        <?= strtoupper($doc['file_type']) ?>
                                                    </div>
                                                    <div>
                                                        <div style="font-size: 11px; font-weight: 500;"><?= esc($doc['file_name']) ?></div>
                                                        <div style="font-size: 10px; color: #6c757d;"><?= formatBytes($doc['file_size']) ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="upload-time"><?= date('d M Y', strtotime($doc['created_at'])) ?></div>
                                                <div class="upload-meta">
                                                    <?= date('H:i', strtotime($doc['created_at'])) ?> WIB
                                                    <br>
                                                    oleh: <?= esc($doc['uploader'] ?? 'Unknown') ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="stats-row">
                                                    <span><i class="fas fa-eye"></i> <?= $doc['views'] ?? 0 ?></span>
                                                    <span><i class="fas fa-download"></i> <?= $doc['downloads'] ?? 0 ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($doc['status'] === 'aktif'): ?>
                                                    <span class="badge badge-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-warning btn-sm" onclick="editDocument(<?= $doc['id'] ?>)" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteDocument(<?= $doc['id'] ?>, '<?= esc($doc['judul']) ?>')" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <a href="<?= base_url('files/' . $doc['file_path']) ?>" target="_blank" class="btn btn-success btn-sm" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" style="text-align: center; padding: 40px;">
                                            <i class="fas fa-folder-open" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                                            <div>Belum ada dokumen</div>
                                            <div style="font-size: 12px; color: #6c757d;">Klik tombol "Tambah Dokumen" untuk mengunggah dokumen pertama</div>
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

    <!-- Add Document Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Dokumen Baru</h3>
                <button class="close-btn" onclick="closeModal('addModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/dokumen/add') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="judul" class="form-label">Judul Dokumen *</label>
                        <input type="text" class="form-control" id="judul" name="judul" required maxlength="200" placeholder="Masukkan judul dokumen">
                    </div>

                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" maxlength="1000" placeholder="Deskripsi singkat dokumen (opsional)"></textarea>
                    </div>

                    <!-- Add Document Modal - Form Section yang diupdate -->
                    <div style="display: flex; gap: 16px;">
                        <div class="form-group" style="flex: 1;">
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

                        <div class="form-group" style="flex: 1;">
                            <label for="kategori_id" class="form-label">Kategori *</label>
                            <select class="form-control" id="kategori_id" name="kategori_id" required disabled>
                                <option value="">Pilih Menu terlebih dahulu</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="file" class="form-label">File Dokumen *</label>
                        <input type="file" class="form-control file-input" id="file" name="file" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        <div class="file-requirements">
                            Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maksimal 10MB.
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px;">
                        <button type="button" class="btn" style="background: #6c757d; color: white;" onclick="closeModal('addModal')">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i>
                            Upload Dokumen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Document Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Dokumen</h3>
                <button class="close-btn" onclick="closeModal('editModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_id" name="id">
                    
                    <div class="form-group">
                        <label for="edit_judul" class="form-label">Judul Dokumen *</label>
                        <input type="text" class="form-control" id="edit_judul" name="judul" required maxlength="200">
                    </div>

                    <div class="form-group">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" maxlength="1000"></textarea>
                    </div>

                    <div style="display: flex; gap: 16px;">
                        <div class="form-group" style="flex: 1;">
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

                        <div class="form-group" style="flex: 1;">
                            <label for="edit_kategori_id" class="form-label">Kategori *</label>
                            <select class="form-control" id="edit_kategori_id" name="kategori_id" required disabled>
                                <option value="">Pilih Menu terlebih dahulu</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_status" class="form-label">Status *</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_file" class="form-label">Ganti File (Opsional)</label>
                        <input type="file" class="form-control file-input" id="edit_file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        <div class="file-requirements">
                            Kosongkan jika tidak ingin mengganti file. Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maksimal 10MB.
                        </div>
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
        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Perbaikan untuk function editDocument di dokumen.php

        // Tambahkan data dokumen ke dalam JavaScript (di bagian bawah dokumen.php)
        const dokumenData = <?= json_encode($dokumen) ?>;

        function editDocument(id) {
            // Cari data dokumen berdasarkan ID
            const doc = dokumenData.find(d => d.id == id);
            
            if (!doc) {
                alert('Data dokumen tidak ditemukan!');
                return;
            }
            
            // Isi form dengan data dokumen
            document.getElementById('edit_id').value = doc.id;
            document.getElementById('edit_judul').value = doc.judul;
            document.getElementById('edit_deskripsi').value = doc.deskripsi || '';
            document.getElementById('edit_kategori_id').value = doc.kategori_id;
            document.getElementById('edit_menu_id').value = doc.menu_id;
            document.getElementById('edit_status').value = doc.status;
            
            // Set form action
            document.getElementById('editForm').action = `<?= base_url('admin/dokumen/edit/') ?>${id}`;
            
            // Buka modal
            document.getElementById('editModal').classList.add('active');
        }

        // Alternative: Menggunakan AJAX untuk mengambil data dokumen
        function editDocumentAjax(id) {
            // Buka modal terlebih dahulu
            document.getElementById('editModal').classList.add('active');
            
            // Set form action
            document.getElementById('editForm').action = `<?= base_url('admin/dokumen/edit/') ?>${id}`;
            
            // Load data via AJAX (jika Anda ingin membuat endpoint API)
            fetch(`<?= base_url('admin/dokumen/get/') ?>${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const doc = data.dokumen;
                        document.getElementById('edit_id').value = doc.id;
                        document.getElementById('edit_judul').value = doc.judul;
                        document.getElementById('edit_deskripsi').value = doc.deskripsi || '';
                        document.getElementById('edit_kategori_id').value = doc.kategori_id;
                        document.getElementById('edit_menu_id').value = doc.menu_id;
                        document.getElementById('edit_status').value = doc.status;
                    } else {
                        alert('Gagal memuat data dokumen');
                        closeModal('editModal');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data');
                    closeModal('editModal');
                });
        }

        function deleteDocument(id, title) {
            if (confirm(`Apakah Anda yakin ingin menghapus dokumen "${title}"?\n\nFile akan dihapus permanent dan tidak dapat dikembalikan.`)) {
                window.location.href = `<?= base_url('admin/dokumen/delete/') ?>${id}`;
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

        // File size validation
        document.getElementById('file').addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.size > 10 * 1024 * 1024) { // 10MB
                alert('File terlalu besar. Maksimal 10MB.');
                this.value = '';
            }
        });

        document.getElementById('edit_file').addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.size > 10 * 1024 * 1024) { // 10MB
                alert('File terlalu besar. Maksimal 10MB.');
                this.value = '';
            }
        });

        <?php
        function formatBytes($size, $precision = 2) {
            $units = array('B', 'KB', 'MB', 'GB');
            for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
                $size /= 1024;
            }
            return round($size, $precision) . ' ' . $units[$i];
        }
        ?>

        // Tambahkan script ini ke bagian bawah dokumen.php (setelah script yang sudah ada)

        // Update fungsi loadKategoriByMenu untuk handle disabled state
        function loadKategoriByMenu(menuId, targetSelectId, selectedKategoriId = null) {
            const kategoriSelect = document.getElementById(targetSelectId);
            
            // Reset dropdown kategori
            kategoriSelect.innerHTML = '<option value="">Loading...</option>';
            kategoriSelect.disabled = true;
            
            if (!menuId) {
                kategoriSelect.innerHTML = '<option value="">Pilih Menu terlebih dahulu</option>';
                kategoriSelect.disabled = true;
                return;
            }
            
            // AJAX request ke endpoint yang sudah ada
            fetch(`<?= base_url('admin/kategori/by-menu/') ?>${menuId}`)
                .then(response => response.json())
                .then(data => {
                    kategoriSelect.innerHTML = '<option value="">Pilih Kategori</option>';
                    
                    if (data.success && data.data && data.data.length > 0) {
                        data.data.forEach(kategori => {
                            const option = document.createElement('option');
                            option.value = kategori.id;
                            option.textContent = kategori.nama_kategori;
                            
                            // Set selected jika ada selectedKategoriId
                            if (selectedKategoriId && kategori.id == selectedKategoriId) {
                                option.selected = true;
                            }
                            
                            kategoriSelect.appendChild(option);
                        });
                        kategoriSelect.disabled = false; // Enable setelah data dimuat
                    } else {
                        kategoriSelect.innerHTML = '<option value="">Tidak ada kategori untuk menu ini</option>';
                        kategoriSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error loading kategori:', error);
                    kategoriSelect.innerHTML = '<option value="">Error loading kategori</option>';
                    kategoriSelect.disabled = true;
                });
        }

        // Event listener untuk dropdown menu di Add Modal
        document.getElementById('menu_id').addEventListener('change', function() {
            const menuId = this.value;
            loadKategoriByMenu(menuId, 'kategori_id');
        });

        // Event listener untuk dropdown menu di Edit Modal
        document.getElementById('edit_menu_id').addEventListener('change', function() {
            const menuId = this.value;
            loadKategoriByMenu(menuId, 'edit_kategori_id');
        });

        // Update fungsi editDocument untuk handle cascading dropdown
        function editDocument(id) {
            // Cari data dokumen berdasarkan ID
            const doc = dokumenData.find(d => d.id == id);
            
            if (!doc) {
                alert('Data dokumen tidak ditemukan!');
                return;
            }
            
            // Isi form dengan data dokumen
            document.getElementById('edit_id').value = doc.id;
            document.getElementById('edit_judul').value = doc.judul;
            document.getElementById('edit_deskripsi').value = doc.deskripsi || '';
            document.getElementById('edit_menu_id').value = doc.menu_id;
            document.getElementById('edit_status').value = doc.status;
            
            // Load kategori berdasarkan menu yang dipilih, lalu set kategori yang terpilih
            loadKategoriByMenu(doc.menu_id, 'edit_kategori_id', doc.kategori_id);
            
            // Set form action
            document.getElementById('editForm').action = `<?= base_url('admin/dokumen/edit/') ?>${id}`;
            
            // Buka modal
            document.getElementById('editModal').classList.add('active');
        }

        // Update fungsi openAddModal untuk reset form state
        function openAddModal() {
            // Reset form
            document.getElementById('addModal').querySelector('form').reset();
            
            // Reset kategori dropdown ke disabled state
            const kategoriSelect = document.getElementById('kategori_id');
            kategoriSelect.innerHTML = '<option value="">Pilih Menu terlebih dahulu</option>';
            kategoriSelect.disabled = true;
            
            // Buka modal
            document.getElementById('addModal').classList.add('active');
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan kategori dropdown disabled saat halaman pertama dimuat
            const kategoriSelect = document.getElementById('kategori_id');
            const editKategoriSelect = document.getElementById('edit_kategori_id');
            
            if (kategoriSelect) {
                kategoriSelect.disabled = true;
                kategoriSelect.innerHTML = '<option value="">Pilih Menu terlebih dahulu</option>';
            }
            
            if (editKategoriSelect) {
                editKategoriSelect.disabled = true;
                editKategoriSelect.innerHTML = '<option value="">Pilih Menu terlebih dahulu</option>';
            }
            
            // Jika ada error dan form kembali dengan input, restore cascading dropdown
            const selectedMenuId = document.getElementById('menu_id').value;
            const selectedKategoriId = document.getElementById('kategori_id').value;
            
            if (selectedMenuId) {
                loadKategoriByMenu(selectedMenuId, 'kategori_id', selectedKategoriId);
            }
        });
    </script>
</body>
</html>