-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Okt 2025 pada 08.23
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sidodik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen`
--

CREATE TABLE `dokumen` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `kategori_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_type` varchar(10) NOT NULL,
  `file_size` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `tanggal_upload` date NOT NULL,
  `views` int(11) DEFAULT 0,
  `downloads` int(11) DEFAULT 0,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `akses` enum('publik','privat') NOT NULL DEFAULT 'privat',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokumen`
--

INSERT INTO `dokumen` (`id`, `judul`, `deskripsi`, `tags`, `kategori_id`, `menu_id`, `file_name`, `file_path`, `file_type`, `file_size`, `uploaded_by`, `tanggal_upload`, `views`, `downloads`, `status`, `akses`, `created_at`, `updated_at`) VALUES
(17, 'Proposal', 'Proposal Magang Diskominfo', NULL, 33, 18, 'Proposalmagang3.docx', 'uploads/documents/2025/09/68bfcade4a79a_1757399774.docx', 'docx', 235763, 1, '2025-09-09', 20, 18, 'aktif', 'publik', '2025-09-09 06:36:14', '2025-09-27 15:46:06'),
(22, 'Keuangan Instansi', '', 'keuangan,instansi,laporan', 29, 18, 'testing.xlsx', 'uploads/documents/2025/09/68bfd142a5338_1757401410.xlsx', 'xlsx', 9661, 1, '2025-09-09', 17, 16, 'aktif', 'publik', '2025-09-09 07:03:30', '2025-10-03 13:19:47'),
(25, 'tes tes', 'tes ppt', NULL, 14, 18, 'Title Lorem Ipsum.pptx', 'uploads/documents/2025/09/68c7d411345e6_1757926417.pptx', 'pptx', 1661945, 1, '2025-09-15', 16, 13, 'aktif', 'publik', '2025-09-15 08:53:37', '2025-09-27 18:02:45'),
(26, 'tes upload lagi', 'tes', 'tes,upload,lagi,formulir,perjalanan,dinas', 21, 4, 'SISTEMATIKA PROPOSAL KP 2024.pdf', 'uploads/documents/2025/09/68cc04320c41a_1758200882.pdf', 'pdf', 586618, 1, '2025-09-18', 17, 22, 'aktif', 'publik', '2025-09-18 13:08:02', '2025-10-03 05:34:58'),
(27, 'Testing Fitur Tag', 'Laporan Proposal Alamant Desa', 'testing,fitur,tag,formulir,pengajuan', 20, 4, 'ContohGA_Optimasi_Rute_Pengiriman_Barang.docx', 'uploads/documents/2025/10/68df60e35afee_1759469795.docx', 'docx', 38637, 31, '2025-10-03', 2, 1, 'aktif', 'privat', '2025-10-03 05:36:35', '2025-10-25 06:14:51'),
(28, 'tes tag', 'tessstaggggg', 'tes,tag,book,manajemen,pemerintahan', 18, 19, 'Contoh_Kasus_Algoritma_Genetika.docx', 'uploads/documents/2025/10/68dfa65d7ce34_1759487581.docx', 'docx', 37599, 1, '2025-10-03', 0, 1, 'aktif', 'privat', '2025-10-03 10:33:01', '2025-10-25 06:15:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `menu_id`, `deskripsi`, `status`, `created_at`, `updated_at`) VALUES
(14, 'Laporan Kegiatan', 18, 'Kegiatan Magang', 'aktif', '2025-09-08 23:07:45', '2025-09-08 23:07:45'),
(15, 'Panduan Teknis', 19, 'Teknisi', 'aktif', '2025-09-08 23:10:02', '2025-09-08 23:10:02'),
(16, 'Formulir Cuti', 4, 'Cuti bersama', 'aktif', '2025-09-08 23:10:34', '2025-09-08 23:10:34'),
(17, 'Teknologi Informasi', 19, 'Teknologi', 'aktif', '2025-09-08 23:13:07', '2025-09-08 23:13:07'),
(18, 'Manajemen Pemerintahan', 19, '', 'aktif', '2025-09-08 23:14:03', '2025-09-08 23:14:03'),
(19, 'Hasil Penelitian', 19, 'Penelitian-Penelitian', 'aktif', '2025-09-08 23:14:42', '2025-09-08 23:14:42'),
(20, 'Formulir Pengajuan', 4, '', 'aktif', '2025-09-08 23:16:01', '2025-09-08 23:16:01'),
(21, 'Formulir Perjalanan Dinas', 4, '', 'aktif', '2025-09-08 23:16:18', '2025-09-08 23:16:18'),
(22, 'Formulir Evaluasi', 4, '', 'aktif', '2025-09-08 23:16:31', '2025-09-08 23:16:31'),
(23, 'Formulir Pendaftaran', 4, 'Pendaftaran Magang', 'aktif', '2025-09-08 23:16:53', '2025-09-08 23:16:53'),
(24, 'Surat Keputusan', 6, 'Keputusan', 'aktif', '2025-09-08 23:19:21', '2025-09-08 23:19:21'),
(25, 'Surat Edaran', 6, '', 'aktif', '2025-09-08 23:19:35', '2025-09-08 23:19:35'),
(26, 'Surat Tugas', 6, '', 'aktif', '2025-09-08 23:19:55', '2025-09-08 23:19:55'),
(27, 'Surat Undangan', 6, '', 'aktif', '2025-09-08 23:20:05', '2025-09-08 23:20:05'),
(28, 'Surat Keterangan', 6, '', 'aktif', '2025-09-08 23:20:13', '2025-09-08 23:20:13'),
(29, 'Laporan Keuangan', 18, 'Keuangan Instansi', 'aktif', '2025-09-08 23:20:38', '2025-09-08 23:20:38'),
(30, 'Laporan Kinerja', 18, 'Kinerja', 'aktif', '2025-09-08 23:21:07', '2025-09-08 23:21:07'),
(31, 'Laporan Evaluasi', 18, '', 'aktif', '2025-09-08 23:21:21', '2025-09-08 23:21:21'),
(32, 'Laporan Tahunan', 18, '', 'aktif', '2025-09-08 23:21:39', '2025-09-08 23:21:39'),
(33, 'Administrasi', 18, '', 'aktif', '2025-09-08 23:32:35', '2025-09-08 23:32:35'),
(35, 'tes edittt', 19, '1234567890qwerteytrt', 'nonaktif', '2025-10-03 07:15:10', '2025-10-10 09:44:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_activity`
--

CREATE TABLE `log_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dokumen_id` int(11) DEFAULT NULL,
  `activity` varchar(50) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_activity`
--

INSERT INTO `log_activity` (`id`, `user_id`, `dokumen_id`, `activity`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 22:08:01', '2025-09-19 22:08:01'),
(2, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 22:08:07', '2025-09-19 22:08:07'),
(3, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 22:08:21', '2025-09-19 22:08:21'),
(4, 1, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 22:08:34', '2025-09-19 22:08:34'),
(5, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 00:04:40', '2025-09-20 00:04:40'),
(6, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 00:04:59', '2025-09-20 00:04:59'),
(7, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 00:12:00', '2025-09-20 00:12:00'),
(8, 1, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 00:30:23', '2025-09-20 00:30:23'),
(9, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 00:30:28', '2025-09-20 00:30:28'),
(10, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 00:30:38', '2025-09-20 00:30:38'),
(11, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 00:30:56', '2025-09-20 00:30:56'),
(12, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 03:23:09', '2025-09-20 03:23:09'),
(13, 1, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 04:30:04', '2025-09-20 04:30:04'),
(14, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 04:30:34', '2025-09-20 04:30:34'),
(15, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 04:30:48', '2025-09-20 04:30:48'),
(16, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 05:28:37', '2025-09-20 05:28:37'),
(17, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 05:28:52', '2025-09-20 05:28:52'),
(18, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 06:20:34', '2025-09-20 06:20:34'),
(19, 1, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 06:20:43', '2025-09-20 06:20:43'),
(20, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 06:20:56', '2025-09-20 06:20:56'),
(21, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 06:21:02', '2025-09-20 06:21:02'),
(22, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 08:41:02', '2025-09-20 08:41:02'),
(23, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 09:26:56', '2025-09-20 09:26:56'),
(24, 33, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 09:28:50', '2025-09-20 09:28:50'),
(25, 33, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 09:29:01', '2025-09-20 09:29:01'),
(26, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 09:29:08', '2025-09-20 09:29:08'),
(27, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 09:33:02', '2025-09-20 09:33:02'),
(28, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 09:33:09', '2025-09-20 09:33:09'),
(29, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 09:35:18', '2025-09-20 09:35:18'),
(30, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 09:35:33', '2025-09-20 09:35:33'),
(31, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 09:44:52', '2025-09-20 09:44:52'),
(32, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-20 09:45:03', '2025-09-20 09:45:03'),
(33, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 21:42:21', '2025-09-22 21:42:21'),
(34, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:07:35', '2025-09-22 23:07:35'),
(35, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:13:16', '2025-09-22 23:13:16'),
(36, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:13:39', '2025-09-22 23:13:39'),
(37, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:40:40', '2025-09-22 23:40:40'),
(38, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:41:21', '2025-09-22 23:41:21'),
(39, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:49:03', '2025-09-22 23:49:03'),
(40, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:52:26', '2025-09-22 23:52:26'),
(41, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-22 23:52:50', '2025-09-22 23:52:50'),
(42, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:10:26', '2025-09-23 01:10:26'),
(43, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:10:40', '2025-09-23 01:10:40'),
(44, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 01:33:28', '2025-09-23 01:33:28'),
(45, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 05:18:52', '2025-09-23 05:18:52'),
(46, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-23 07:00:40', '2025-09-23 07:00:40'),
(47, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 07:43:20', '2025-09-24 07:43:20'),
(48, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 13:41:55', '2025-09-24 13:41:55'),
(49, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 18:34:19', '2025-09-24 18:34:19'),
(50, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 18:35:30', '2025-09-24 18:35:30'),
(51, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 18:40:34', '2025-09-24 18:40:34'),
(52, 1, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 18:40:45', '2025-09-24 18:40:45'),
(53, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 19:11:02', '2025-09-24 19:11:02'),
(54, 1, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 19:11:38', '2025-09-24 19:11:38'),
(55, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 19:14:35', '2025-09-24 19:14:35'),
(56, 1, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 19:20:37', '2025-09-24 19:20:37'),
(57, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 19:20:49', '2025-09-24 19:20:49'),
(58, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-24 19:20:56', '2025-09-24 19:20:56'),
(59, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-25 05:48:51', '2025-09-25 05:48:51'),
(60, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-25 05:56:03', '2025-09-25 05:56:03'),
(61, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-25 05:56:21', '2025-09-25 05:56:21'),
(62, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-25 05:56:31', '2025-09-25 05:56:31'),
(63, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-25 19:00:50', '2025-09-25 19:00:50'),
(64, 1, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-25 19:02:29', '2025-09-25 19:02:29'),
(65, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 21:27:27', '2025-09-26 21:27:27'),
(66, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-26 21:47:22', '2025-09-26 21:47:22'),
(67, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 00:47:40', '2025-09-27 00:47:40'),
(68, 1, 26, 'download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 00:49:57', '2025-09-27 00:49:57'),
(69, 1, 26, 'download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 00:49:58', '2025-09-27 00:49:58'),
(70, 1, 26, 'download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 00:49:59', '2025-09-27 00:49:59'),
(71, 1, 26, 'view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 00:50:04', '2025-09-27 00:50:04'),
(72, 1, 26, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:41:31', '2025-09-27 01:41:31'),
(73, 1, 26, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:41:32', '2025-09-27 01:41:32'),
(74, 1, 26, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:41:44', '2025-09-27 01:41:44'),
(75, 1, 26, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:41:45', '2025-09-27 01:41:45'),
(76, 1, 26, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:41:54', '2025-09-27 01:41:54'),
(77, 1, 26, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:41:55', '2025-09-27 01:41:55'),
(80, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:42:23', '2025-09-27 01:42:23'),
(84, 1, 17, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:45:24', '2025-09-27 01:45:24'),
(85, 1, 17, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:45:25', '2025-09-27 01:45:25'),
(86, 1, 17, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:45:26', '2025-09-27 01:45:26'),
(87, 1, 26, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:45:34', '2025-09-27 01:45:34'),
(88, 1, 26, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:45:35', '2025-09-27 01:45:35'),
(89, 1, 25, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:45:42', '2025-09-27 01:45:42'),
(90, 1, 25, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:45:42', '2025-09-27 01:45:42'),
(91, 1, 25, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:45:44', '2025-09-27 01:45:44'),
(92, 1, 22, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:45:52', '2025-09-27 01:45:52'),
(93, 1, 22, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:45:53', '2025-09-27 01:45:53'),
(94, 1, 22, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:45:54', '2025-09-27 01:45:54'),
(95, 1, 17, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:46:07', '2025-09-27 01:46:07'),
(96, 1, 17, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:46:08', '2025-09-27 01:46:08'),
(97, 1, 17, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:46:09', '2025-09-27 01:46:09'),
(98, 1, 17, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:46:18', '2025-09-27 01:46:18'),
(99, 1, 17, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:46:18', '2025-09-27 01:46:18'),
(100, 1, 17, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:46:19', '2025-09-27 01:46:19'),
(101, 1, 26, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:51:08', '2025-09-27 01:51:08'),
(102, 1, 25, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:51:24', '2025-09-27 01:51:24'),
(103, 1, 22, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:52:41', '2025-09-27 01:52:41'),
(104, 1, 17, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 01:52:54', '2025-09-27 01:52:54'),
(105, 1, 22, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:15:52', '2025-09-27 04:15:52'),
(106, 1, 26, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:16:01', '2025-09-27 04:16:01'),
(107, 1, 25, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 04:16:08', '2025-09-27 04:16:08'),
(109, 1, 26, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:36:53', '2025-09-27 05:36:53'),
(110, 1, 26, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:36:58', '2025-09-27 05:36:58'),
(111, 1, 25, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:37:09', '2025-09-27 05:37:09'),
(112, 1, 25, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:37:17', '2025-09-27 05:37:17'),
(113, 1, 22, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:37:43', '2025-09-27 05:37:43'),
(114, 1, 17, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:37:59', '2025-09-27 05:37:59'),
(115, 1, 17, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:38:07', '2025-09-27 05:38:07'),
(118, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:45:06', '2025-09-27 05:45:06'),
(119, 1, 26, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:45:29', '2025-09-27 05:45:29'),
(120, 1, 25, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 05:45:40', '2025-09-27 05:45:40'),
(121, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:24:10', '2025-09-27 07:24:10'),
(122, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:24:47', '2025-09-27 07:24:47'),
(123, 1, 25, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:24:55', '2025-09-27 07:24:55'),
(124, 1, 25, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:25:03', '2025-09-27 07:25:03'),
(125, 32, 26, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:38:44', '2025-09-27 07:38:44'),
(126, 32, 26, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:38:53', '2025-09-27 07:38:53'),
(127, 32, 26, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:38:53', '2025-09-27 07:38:53'),
(128, 32, 26, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:38:55', '2025-09-27 07:38:55'),
(129, 32, 25, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:39:04', '2025-09-27 07:39:04'),
(130, 32, 25, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:39:04', '2025-09-27 07:39:04'),
(131, 32, 25, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:39:07', '2025-09-27 07:39:07'),
(132, 32, 25, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:39:12', '2025-09-27 07:39:12'),
(133, 32, 25, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:39:14', '2025-09-27 07:39:14'),
(134, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:39:36', '2025-09-27 07:39:36'),
(135, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:43:05', '2025-09-27 07:43:05'),
(136, 32, 26, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:43:11', '2025-09-27 07:43:11'),
(137, 32, 26, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:43:23', '2025-09-27 07:43:23'),
(138, 32, 26, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:43:23', '2025-09-27 07:43:23'),
(139, 32, 26, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:43:24', '2025-09-27 07:43:24'),
(140, 32, 25, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:44:22', '2025-09-27 07:44:22'),
(141, 32, 25, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:44:23', '2025-09-27 07:44:23'),
(146, 32, 26, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:04', '2025-09-27 07:46:04'),
(147, 32, 26, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:12', '2025-09-27 07:46:12'),
(148, 32, 26, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:12', '2025-09-27 07:46:12'),
(149, 32, 26, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:13', '2025-09-27 07:46:13'),
(150, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 07:46:30', '2025-09-27 07:46:30'),
(151, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:45:23', '2025-09-27 08:45:23'),
(152, 32, 26, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:45:29', '2025-09-27 08:45:29'),
(157, 32, 17, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:45:58', '2025-09-27 08:45:58'),
(158, 32, 17, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:45:59', '2025-09-27 08:45:59'),
(159, 32, 17, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:46:06', '2025-09-27 08:46:06'),
(160, 32, 17, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:46:06', '2025-09-27 08:46:06'),
(161, 32, 17, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:46:07', '2025-09-27 08:46:07'),
(162, 32, 22, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:46:15', '2025-09-27 08:46:15'),
(163, 32, 22, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:46:17', '2025-09-27 08:46:17'),
(164, 32, 22, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:46:24', '2025-09-27 08:46:24'),
(165, 32, 22, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:46:24', '2025-09-27 08:46:24'),
(166, 32, 22, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:46:25', '2025-09-27 08:46:25'),
(167, 32, 25, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:46:32', '2025-09-27 08:46:32'),
(168, 32, 25, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 08:46:34', '2025-09-27 08:46:34'),
(169, 32, 25, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 09:07:29', '2025-09-27 09:07:29'),
(170, 32, 25, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 09:07:30', '2025-09-27 09:07:30'),
(171, 32, 25, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 09:07:31', '2025-09-27 09:07:31'),
(172, 32, 25, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 09:07:36', '2025-09-27 09:07:36'),
(173, 32, 25, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 09:07:37', '2025-09-27 09:07:37'),
(174, 32, 22, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 09:07:46', '2025-09-27 09:07:46'),
(175, 32, 22, 'user_view_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 09:07:48', '2025-09-27 09:07:48'),
(176, 32, 22, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 09:07:54', '2025-09-27 09:07:54'),
(177, 32, 22, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 09:07:54', '2025-09-27 09:07:54'),
(178, 32, 22, 'user_download_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 09:07:55', '2025-09-27 09:07:55'),
(179, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 10:57:58', '2025-09-27 10:57:58'),
(180, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 19:41:02', '2025-09-27 19:41:02'),
(181, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-27 23:00:11', '2025-09-27 23:00:11'),
(182, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-28 03:36:52', '2025-09-28 03:36:52'),
(185, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-30 17:48:49', '2025-09-30 17:48:49'),
(186, 1, NULL, 'edit_menu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-30 18:29:17', '2025-09-30 18:29:17'),
(187, 1, NULL, 'update_pengaduan_status', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-30 18:30:23', '2025-09-30 18:30:23'),
(188, 1, NULL, 'update_pengaduan_status', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-30 18:35:13', '2025-09-30 18:35:13'),
(189, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-30 22:21:56', '2025-09-30 22:21:56'),
(190, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-01 02:54:49', '2025-10-01 02:54:49'),
(191, 1, NULL, 'update_pengaduan_status', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-01 04:42:01', '2025-10-01 04:42:01'),
(192, 1, NULL, 'update_pengaduan_status', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-01 04:48:30', '2025-10-01 04:48:30'),
(193, 1, NULL, 'delete_menu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-01 04:49:21', '2025-10-01 04:49:21'),
(194, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-01 07:45:27', '2025-10-01 07:45:27'),
(195, 31, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-01 17:02:13', '2025-10-01 17:02:13'),
(196, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-01 17:02:28', '2025-10-01 17:02:28'),
(197, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 10:01:58', '2025-10-02 10:01:58'),
(198, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 10:02:53', '2025-10-02 10:02:53'),
(199, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 10:03:07', '2025-10-02 10:03:07'),
(200, 31, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 10:04:07', '2025-10-02 10:04:07'),
(201, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 10:04:32', '2025-10-02 10:04:32'),
(202, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 22:34:38', '2025-10-02 22:34:38'),
(203, 31, 27, 'upload', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 22:36:35', '2025-10-02 22:36:35'),
(204, 31, 27, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 22:36:43', '2025-10-02 22:36:43'),
(205, 31, 27, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 22:36:53', '2025-10-02 22:36:53'),
(206, 31, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 22:37:29', '2025-10-02 22:37:29'),
(207, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-02 22:37:36', '2025-10-02 22:37:36'),
(208, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-03 00:46:53', '2025-10-03 00:46:53'),
(209, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-03 00:47:01', '2025-10-03 00:47:01'),
(210, 31, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-03 00:48:05', '2025-10-03 00:48:05'),
(211, 1, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-03 00:55:13', '2025-10-03 00:55:13'),
(212, 1, 28, 'upload', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-03 03:33:01', '2025-10-03 03:33:01'),
(213, 1, NULL, 'update_pengaduan_status', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-03 03:41:06', '2025-10-03 03:41:06'),
(214, 1, 27, 'link_pengaduan_dokumen', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-03 06:20:13', '2025-10-03 06:20:13'),
(215, 1, NULL, 'add_kategori', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-03 07:15:10', '2025-10-03 07:15:10'),
(216, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-08 11:39:01', '2025-10-08 11:39:01'),
(217, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-10 09:40:22', '2025-10-10 09:40:22'),
(218, 31, NULL, 'edit_kategori', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-10 09:44:25', '2025-10-10 09:44:25'),
(219, 31, NULL, 'edit_menu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-10 09:45:39', '2025-10-10 09:45:39'),
(220, 31, NULL, 'edit_user', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-10 16:49:26', '2025-10-10 16:49:26'),
(221, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-11 04:25:58', '2025-10-11 04:25:58'),
(222, 31, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-11 07:42:15', '2025-10-11 07:42:15'),
(223, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-11 08:16:19', '2025-10-11 08:16:19'),
(224, 31, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-11 08:17:44', '2025-10-11 08:17:44'),
(225, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-11 08:18:27', '2025-10-11 08:18:27'),
(226, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-11 10:40:31', '2025-10-11 10:40:31'),
(227, 31, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-11 10:50:10', '2025-10-11 10:50:10'),
(228, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-11 10:50:18', '2025-10-11 10:50:18'),
(229, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-14 03:19:52', '2025-10-14 03:19:52'),
(230, 31, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-14 03:20:01', '2025-10-14 03:20:01'),
(231, 32, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-14 03:20:37', '2025-10-14 03:20:37'),
(232, 32, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-14 03:20:51', '2025-10-14 03:20:51'),
(233, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-14 03:21:08', '2025-10-14 03:21:08'),
(234, 31, NULL, 'update_pengaduan_status', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-14 03:54:28', '2025-10-14 03:54:28'),
(235, 31, NULL, 'update_pengaduan_status', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-14 05:07:44', '2025-10-14 05:07:44'),
(236, 31, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-14 05:18:56', '2025-10-14 05:18:56'),
(237, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-14 05:19:04', '2025-10-14 05:19:04'),
(238, 31, NULL, 'logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-14 05:27:50', '2025-10-14 05:27:50'),
(239, 31, NULL, 'login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-25 06:10:08', '2025-10-25 06:10:08'),
(240, 31, NULL, 'delete_pengaduan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-25 06:10:38', '2025-10-25 06:10:38'),
(241, 31, NULL, 'delete_pengaduan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-25 06:10:42', '2025-10-25 06:10:42'),
(243, 31, 27, 'admin_view', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-25 06:14:51', '2025-10-25 06:14:51'),
(244, 31, 28, 'admin_download', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-25 06:15:04', '2025-10-25 06:15:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `deskripsi`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(4, 'Formulir', 'Formulir dan Dokumen', 'clipboard', 'aktif', '2025-08-24 02:25:27', '2025-08-24 02:25:27'),
(6, 'surat', 'suratsuratan', 'envelope', 'aktif', '2025-08-26 19:19:16', '2025-08-26 19:19:16'),
(18, 'Laporan', 'contoh', 'file-alt', 'aktif', '2025-09-08 23:05:45', '2025-09-08 23:05:45'),
(19, 'E-book', 'Buku buku an', 'book', 'aktif', '2025-09-08 23:08:32', '2025-10-10 09:45:39'),
(23, 'tes menu 999', '', 'envelope', 'aktif', '2025-09-13 09:00:33', '2025-09-13 09:00:33'),
(24, 'tes', '', '', 'aktif', '2025-09-15 00:42:14', '2025-09-15 00:42:14'),
(25, 'tes menuuu', '123', 'envelope', 'nonaktif', '2025-09-18 06:09:51', '2025-09-18 06:10:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_dokumen`
--

CREATE TABLE `pengaduan_dokumen` (
  `id` int(11) NOT NULL,
  `ticket_number` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `jenis_pemohon` enum('publik','asn') NOT NULL DEFAULT 'publik',
  `nip` varchar(20) DEFAULT NULL,
  `instansi` varchar(100) DEFAULT NULL,
  `judul_dokumen` varchar(200) NOT NULL,
  `deskripsi_kebutuhan` text NOT NULL,
  `kategori_permintaan` enum('surat','laporan','formulir','panduan','lainnya') NOT NULL,
  `urgency` enum('rendah','sedang','tinggi','sangat_tinggi') NOT NULL DEFAULT 'sedang',
  `status` enum('pending','proses','selesai','ditolak') NOT NULL DEFAULT 'pending',
  `admin_response` text DEFAULT NULL,
  `dokumen_terkait` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `tanggal_respon` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan_dokumen`
--

INSERT INTO `pengaduan_dokumen` (`id`, `ticket_number`, `nama`, `email`, `telepon`, `jenis_pemohon`, `nip`, `instansi`, `judul_dokumen`, `deskripsi_kebutuhan`, `kategori_permintaan`, `urgency`, `status`, `admin_response`, `dokumen_terkait`, `ip_address`, `user_agent`, `tanggal_respon`, `created_at`, `updated_at`) VALUES
(3, 'REQ-20250926-0003', 'tes', 'admin@koperasi.com', '078965678956', 'publik', NULL, 'dinas kesehatan', 'laporan apbd 2000', 'tessssssssssssssssssssssssssssssssss', 'panduan', 'sangat_tinggi', 'selesai', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-01 01:35:13', '2025-09-25 20:09:47', '2025-09-30 18:35:13'),
(4, 'REQ-20250927-0004', 'tes baruu', 'atmindatang@gmail.com', '-', 'publik', NULL, '-', 'laporan laporan', 'tesssss baruuuuuuuuuuuuuuu', 'panduan', 'tinggi', 'pending', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 10:54:28', '2025-09-26 21:48:22', '2025-10-14 03:54:28'),
(5, 'REQ-20250928-0005', 'aku siapa', 'asd@gmai.com', '00080', 'asn', '198501012010032020', 'dinas pendidikan', 'Laporan SK 2015', 'tes tes tes', 'lainnya', 'sedang', 'selesai', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-01 11:42:01', '2025-09-28 03:34:28', '2025-10-01 04:42:01'),
(6, 'REQ-20251003-0006', 'asisten negara', 'zzz@gmai.com', '-', 'asn', '198501012010032001', '', 'PNS PNS PNS', 'lagi butuh aja sihh', 'panduan', 'rendah', 'pending', NULL, 27, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-14 12:07:44', '2025-10-02 22:34:05', '2025-10-14 05:07:44'),
(7, 'REQ-20251003-0007', 'ujang', 'zzz@gmai.com', '-', 'asn', '198501012010032099', 'dinas alam semesta', 'Surat Edaran', 'saya membutuhkannnya karena ingin sekolah', 'surat', 'sedang', 'selesai', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-03 10:41:06', '2025-10-03 00:46:50', '2025-10-03 03:41:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `nip`, `password`, `nama_lengkap`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 'aktif', '2025-08-24 02:25:27', '2025-08-24 02:25:27'),
(2, 'siti.nurhalizaaaaaaaaaaas', '198501012010032001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Nurhalizaaaa', 'user', 'aktif', '2025-08-24 02:25:27', '2025-09-05 19:29:11'),
(31, 'Zuher', NULL, '$2y$10$xeuWd4zHgpeyTOhnEhTwC.scDaWPXg8mOpuAXvXw0SG/l0UEVdXZO', 'zaky zuhair HS', 'admin', 'aktif', '2025-09-08 22:59:33', '2025-09-08 22:59:33'),
(32, 'Zeky', '198501012010032020', '$2y$10$OiM80JL29ung2nlOvxogm.eG7A60AL7R3sfq5A1HqIPZBTtsGGkHq', 'coba user', 'user', 'aktif', '2025-09-08 23:04:02', '2025-09-15 01:22:52'),
(33, 'asisten', '198501012010032076', '$2y$10$.UAmGFlRi1calesessQu2eDk/SDR80FFob/P94OP/smjoXKfU2ACO', 'zaky zuhair hs', 'user', 'nonaktif', '2025-09-18 06:13:07', '2025-10-10 16:49:26');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `idx_dokumen_kategori` (`kategori_id`),
  ADD KEY `idx_dokumen_menu` (`menu_id`),
  ADD KEY `idx_dokumen_status` (`status`),
  ADD KEY `idx_dokumen_akses` (`akses`),
  ADD KEY `idx_dokumen_status_akses` (`status`,`akses`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kategori_menu` (`menu_id`);

--
-- Indeks untuk tabel `log_activity`
--
ALTER TABLE `log_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dokumen_id` (`dokumen_id`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengaduan_dokumen`
--
ALTER TABLE `pengaduan_dokumen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_number` (`ticket_number`),
  ADD KEY `idx_pengaduan_status` (`status`),
  ADD KEY `idx_pengaduan_urgency` (`urgency`),
  ADD KEY `idx_pengaduan_kategori` (`kategori_permintaan`),
  ADD KEY `idx_pengaduan_email` (`email`),
  ADD KEY `fk_dokumen_terkait` (`dokumen_terkait`),
  ADD KEY `idx_pengaduan_created_at` (`created_at`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_nip` (`nip`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `log_activity`
--
ALTER TABLE `log_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `pengaduan_dokumen`
--
ALTER TABLE `pengaduan_dokumen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  ADD CONSTRAINT `dokumen_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`),
  ADD CONSTRAINT `dokumen_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`),
  ADD CONSTRAINT `dokumen_ibfk_3` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD CONSTRAINT `fk_kategori_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Ketidakleluasaan untuk tabel `log_activity`
--
ALTER TABLE `log_activity`
  ADD CONSTRAINT `log_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `log_activity_ibfk_2` FOREIGN KEY (`dokumen_id`) REFERENCES `dokumen` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengaduan_dokumen`
--
ALTER TABLE `pengaduan_dokumen`
  ADD CONSTRAINT `fk_pengaduan_dokumen` FOREIGN KEY (`dokumen_terkait`) REFERENCES `dokumen` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
