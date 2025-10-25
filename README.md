# 📚 SIDODIK - Sistem Informasi Dokumen Diskominfotik

## 📖 Deskripsi

**SIDODIK (Sistem Informasi Dokumen Diskominfotik)** adalah sistem manajemen dokumen berbasis web yang dikembangkan untuk Dinas Komunikasi, Informatika, dan Statistik Kabupaten Bandung Barat. Sistem ini memudahkan pengelolaan, penyimpanan, dan distribusi dokumen digital secara terstruktur dan efisien.

### ✨ Fitur Utama

#### 🌐 **Landing Page (Public Access)**
- Browse dokumen publik tanpa login
- Fitur pencarian dokumen dengan tag
- Filter dokumen berdasarkan menu dan kategori
- Sistem pengaduan/permintaan dokumen dengan tracking ticket
- Cek status pengaduan secara real-time
- View dan download dokumen publik

#### 👤 **User Dashboard**
- Akses dokumen publik dan privat
- Pencarian dokumen dengan filter advanced
- View dan download dokumen dengan tracking
- Dashboard dengan statistik personal
- Profile management

#### 👨‍💼 **Admin Dashboard**
- **Manajemen Dokumen**
  - Upload dokumen multi-format (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX)
  - Edit dan hapus dokumen
  - Auto-generate tags dari judul dan kategori
  - Kontrol akses dokumen (publik/privat)
  - Track views dan downloads
  
- **Manajemen Pengaduan**
  - View dan manage semua pengaduan
  - Update status pengaduan (pending, proses, selesai, ditolak)
  - Link dokumen ke pengaduan
  - Analytics pengaduan dengan chart
  - Export rekap pengaduan (Excel/PDF)
  
- **Manajemen Menu & Kategori**
  - CRUD menu dokumen
  - CRUD kategori dengan relasi menu
  - Icon picker untuk menu
  
- **Manajemen User**
  - Tambah, edit, hapus user
  - Role management (admin/user)
  - Support login dengan NIP untuk ASN
  
- **Analytics Dashboard**
  - Overview statistics (total dokumen, views, downloads, users)
  - Document analytics by type, menu, category
  - User activity tracking
  - Pengaduan analytics dengan success rate
  - Monthly trends chart
  - Menu performance metrics
  - Top documents ranking
  - Activity details dengan filter dan export

#### 🔐 **Authentication & Security**
- Multi-level authentication (Admin dengan username, User dengan NIP)
- Password hashing dengan bcrypt
- Session management
- IP address dan user agent logging
- Forgot password untuk user (dengan NIP)
- Activity logging untuk audit trail

#### 📊 **Reporting & Export**
- Export dashboard analytics (Excel/PDF)
- Export rekap pengaduan dengan periode custom
- Export activity report dengan filter
- Print-friendly reports

#### 🔍 **Search & Filter**
- Pencarian dokumen dengan tag, judul, deskripsi, nama file
- Filter by menu, kategori, status, akses level
- Search pengaduan by ticket number, nama, email, NIP
- Filter pengaduan by status, urgency, kategori, jenis pemohon

## 🛠️ Tech Stack

- **Framework:** CodeIgniter 4
- **PHP Version:** 8.2.12
- **Database:** MySQL (MariaDB 10.4.32)
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
- **Charts:** Chart.js
- **Icons:** Font Awesome 5

## 📁 Struktur Database

Database terdiri dari 6 tabel utama:

1. **users** - Data pengguna (admin & user)
2. **menu** - Menu dokumen
3. **kategori** - Kategori dokumen (relasi dengan menu)
4. **dokumen** - Data dokumen dengan tracking
5. **pengaduan_dokumen** - Sistem pengaduan dengan ticket number
6. **log_activity** - Logging semua aktivitas user

## 🚀 Instalasi

### Requirements
- PHP >= 8.1
- MySQL/MariaDB
- Composer
- Web Server (Apache/Nginx)

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone https://github.com/username/sidodik.git
cd sidodik
```

2. **Install Dependencies**
```bash
composer install
```

3. **Setup Environment**
```bash
cp env .env
```

Edit file `.env`:
```env
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = sidodik
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

4. **Import Database**
- Buat database baru dengan nama `sidodik`
- Import file `sidodik43_DONE.sql` ke database

5. **Setup Upload Directory**
```bash
mkdir -p writable/uploads/documents
chmod -R 755 writable/
```

6. **Run Application**
```bash
php spark serve
```

Akses aplikasi di `http://localhost:8080`

## 👥 Default Login

### Admin
- Username: `admin`
- Password: `password`

### User (contoh)
- NIP: `198501012010032001`
- Password: `password`

**⚠️ PENTING:** Ubah password default setelah instalasi pertama!

## 📱 Fitur Anti-Spam

Sistem dilengkapi dengan anti-spam protection untuk mencegah spam views dan downloads:
- Views: Limit 1x per user per dokumen per 1 jam
- Downloads: Limit 1x per user per dokumen per 5 menit
- Tracking IP address dan user agent

## 🔧 Konfigurasi

### Upload Settings
Konfigurasi upload file ada di `app/Controllers/Admin.php`:
```php
// Allowed file types
'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'

// Max file size: 10MB
'max_size' => 10240
```

### Session Settings
Konfigurasi session di `.env`:
```env
app.sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler'
app.sessionCookieName = 'ci_session'
app.sessionExpiration = 7200
app.sessionSavePath = NULL
app.sessionMatchIP = false
app.sessionTimeToUpdate = 300
app.sessionRegenerateDestroy = false
```

## 🐛 Known Issues & Limitations

1. **File Storage:** Files disimpan di `writable/uploads/` - pastikan backup rutin
2. **Browser Compatibility:** Optimized untuk Chrome, Firefox, Safari (latest versions)
3. **PDF Preview:** Beberapa PDF dengan encoding khusus mungkin tidak bisa preview langsung
