-- Backup Database ClickSpace
-- Database: `cs8`
-- Created at: 2026-06-05 21:28:31 WIB

CREATE DATABASE IF NOT EXISTS `cs8`;
USE `cs8`;
SET FOREIGN_KEY_CHECKS=0;

-- -----------------------------------------------
-- Table structure for `backup_logs`
-- -----------------------------------------------
DROP TABLE IF EXISTS `backup_logs`;
CREATE TABLE `backup_logs` (
  `id_backup` int NOT NULL AUTO_INCREMENT,
  `nama_file` varchar(255) NOT NULL,
  `lokasi_file` varchar(255) NOT NULL,
  `sumber_backup` enum('manual','scheduler') DEFAULT 'manual',
  `status_backup` enum('success','failed') DEFAULT 'success',
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_backup`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `backup_logs` (`id_backup`, `nama_file`, `lokasi_file`, `sumber_backup`, `status_backup`, `keterangan`, `created_at`) VALUES ('1', 'clickspace_backup_2026-06-05_21-16-03.sql', 'storage/backups/clickspace_backup_2026-06-05_21-16-03.sql', 'manual', 'success', 'Backup database berhasil dibuat.', '2026-06-05 21:16:03');

-- -----------------------------------------------
-- Table structure for `booking`
-- -----------------------------------------------
DROP TABLE IF EXISTS `booking`;
CREATE TABLE `booking` (
  `id_booking` int NOT NULL AUTO_INCREMENT,
  `kode_booking` varchar(20) NOT NULL,
  `id_user` int NOT NULL,
  `id_layanan` int NOT NULL,
  `nama_customer` varchar(100) NOT NULL,
  `no_whatsapp` varchar(20) NOT NULL,
  `tanggal_booking` date NOT NULL,
  `jam_booking` varchar(50) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `bukti_pembayaran` varchar(255) NOT NULL,
  `total_harga` int NOT NULL,
  `status_booking` enum('Menunggu Konfirmasi','Dikonfirmasi','Selesai','Dibatalkan') DEFAULT 'Menunggu Konfirmasi',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_booking`),
  UNIQUE KEY `kode_booking` (`kode_booking`),
  KEY `id_user` (`id_user`),
  KEY `id_layanan` (`id_layanan`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `booking` (`id_booking`, `kode_booking`, `id_user`, `id_layanan`, `nama_customer`, `no_whatsapp`, `tanggal_booking`, `jam_booking`, `metode_pembayaran`, `bukti_pembayaran`, `total_harga`, `status_booking`, `created_at`) VALUES ('4', 'CS20260528095649', '2', '3', 'salwa', '083140664639', '2026-06-02', '10.00 - 11.00', 'Transfer Bank', 'uploads/bukti_1779962209_490.png', '100000', 'Menunggu Konfirmasi', '2026-05-28 16:56:49');
INSERT INTO `booking` (`id_booking`, `kode_booking`, `id_user`, `id_layanan`, `nama_customer`, `no_whatsapp`, `tanggal_booking`, `jam_booking`, `metode_pembayaran`, `bukti_pembayaran`, `total_harga`, `status_booking`, `created_at`) VALUES ('5', 'CS20260528110011', '3', '4', 'Zahira Adiah Safa', '0865379738010', '2026-07-02', '10.00 - 11.00', 'DANA', 'uploads/bukti_1779966011_667.png', '75000', 'Menunggu Konfirmasi', '2026-05-28 18:00:11');

-- -----------------------------------------------
-- Table structure for `booking_audit`
-- -----------------------------------------------
DROP TABLE IF EXISTS `booking_audit`;
CREATE TABLE `booking_audit` (
  `id_audit` int NOT NULL AUTO_INCREMENT,
  `id_booking` int DEFAULT NULL,
  `kode_booking` varchar(20) DEFAULT NULL,
  `aksi` varchar(30) NOT NULL,
  `status_lama` varchar(50) DEFAULT NULL,
  `status_baru` varchar(50) DEFAULT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_audit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------
-- Table structure for `booking_fragment_aktif`
-- -----------------------------------------------
DROP TABLE IF EXISTS `booking_fragment_aktif`;
CREATE TABLE `booking_fragment_aktif` (
  `id_booking` int NOT NULL AUTO_INCREMENT,
  `kode_booking` varchar(20) NOT NULL,
  `id_user` int NOT NULL,
  `id_layanan` int NOT NULL,
  `nama_customer` varchar(100) NOT NULL,
  `no_whatsapp` varchar(20) NOT NULL,
  `tanggal_booking` date NOT NULL,
  `jam_booking` varchar(50) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `bukti_pembayaran` varchar(255) NOT NULL,
  `total_harga` int NOT NULL,
  `status_booking` enum('Menunggu Konfirmasi','Dikonfirmasi','Selesai','Dibatalkan') DEFAULT 'Menunggu Konfirmasi',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_booking`),
  UNIQUE KEY `kode_booking` (`kode_booking`),
  KEY `id_user` (`id_user`),
  KEY `id_layanan` (`id_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `booking_fragment_aktif` (`id_booking`, `kode_booking`, `id_user`, `id_layanan`, `nama_customer`, `no_whatsapp`, `tanggal_booking`, `jam_booking`, `metode_pembayaran`, `bukti_pembayaran`, `total_harga`, `status_booking`, `created_at`) VALUES ('4', 'CS20260528095649', '2', '3', 'salwa', '083140664639', '2026-06-02', '10.00 - 11.00', 'Transfer Bank', 'uploads/bukti_1779962209_490.png', '100000', 'Menunggu Konfirmasi', '2026-05-28 16:56:49');
INSERT INTO `booking_fragment_aktif` (`id_booking`, `kode_booking`, `id_user`, `id_layanan`, `nama_customer`, `no_whatsapp`, `tanggal_booking`, `jam_booking`, `metode_pembayaran`, `bukti_pembayaran`, `total_harga`, `status_booking`, `created_at`) VALUES ('5', 'CS20260528110011', '3', '4', 'Zahira Adiah Safa', '0865379738010', '2026-07-02', '10.00 - 11.00', 'DANA', 'uploads/bukti_1779966011_667.png', '75000', 'Menunggu Konfirmasi', '2026-05-28 18:00:11');

-- -----------------------------------------------
-- Table structure for `booking_fragment_selesai`
-- -----------------------------------------------
DROP TABLE IF EXISTS `booking_fragment_selesai`;
CREATE TABLE `booking_fragment_selesai` (
  `id_booking` int NOT NULL AUTO_INCREMENT,
  `kode_booking` varchar(20) NOT NULL,
  `id_user` int NOT NULL,
  `id_layanan` int NOT NULL,
  `nama_customer` varchar(100) NOT NULL,
  `no_whatsapp` varchar(20) NOT NULL,
  `tanggal_booking` date NOT NULL,
  `jam_booking` varchar(50) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `bukti_pembayaran` varchar(255) NOT NULL,
  `total_harga` int NOT NULL,
  `status_booking` enum('Menunggu Konfirmasi','Dikonfirmasi','Selesai','Dibatalkan') DEFAULT 'Menunggu Konfirmasi',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_booking`),
  UNIQUE KEY `kode_booking` (`kode_booking`),
  KEY `id_user` (`id_user`),
  KEY `id_layanan` (`id_layanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------
-- Table structure for `deadlock_logs`
-- -----------------------------------------------
DROP TABLE IF EXISTS `deadlock_logs`;
CREATE TABLE `deadlock_logs` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `nama_proses` varchar(30) NOT NULL,
  `resource_name` varchar(80) DEFAULT NULL,
  `status_step` enum('LOCKED','WAITING','ROLLBACK','RETRY','COMMIT','INFO') NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------
-- Table structure for `deadlock_resource`
-- -----------------------------------------------
DROP TABLE IF EXISTS `deadlock_resource`;
CREATE TABLE `deadlock_resource` (
  `id_resource` int NOT NULL AUTO_INCREMENT,
  `resource_name` varchar(80) NOT NULL,
  `locked_by` varchar(30) DEFAULT NULL,
  `status_resource` enum('FREE','LOCKED','WAITING') DEFAULT 'FREE',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_resource`),
  UNIQUE KEY `resource_name` (`resource_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `deadlock_resource` (`id_resource`, `resource_name`, `locked_by`, `status_resource`, `updated_at`) VALUES ('1', 'booking:sample', NULL, 'FREE', '2026-06-04 16:53:06');
INSERT INTO `deadlock_resource` (`id_resource`, `resource_name`, `locked_by`, `status_resource`, `updated_at`) VALUES ('2', 'layanan:sample', NULL, 'FREE', '2026-06-04 16:53:06');

-- -----------------------------------------------
-- Table structure for `layanan`
-- -----------------------------------------------
DROP TABLE IF EXISTS `layanan`;
CREATE TABLE `layanan` (
  `id_layanan` int NOT NULL AUTO_INCREMENT,
  `nama_layanan` varchar(100) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `durasi` varchar(50) NOT NULL,
  `kapasitas` varchar(50) NOT NULL,
  `fasilitas` varchar(100) NOT NULL,
  `harga` int NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  PRIMARY KEY (`id_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `kategori`, `durasi`, `kapasitas`, `fasilitas`, `harga`, `deskripsi`, `gambar`) VALUES ('2', 'Foto Family', 'Foto Keluarga', '45 Menit', '4-6 Orang', 'Soft File', '150000', 'Paket foto keluarga dengan suasana nyaman dan hasil foto yang cocok untuk kenangan keluarga.', 'images/foto keluarga.jpg');
INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `kategori`, `durasi`, `kapasitas`, `fasilitas`, `harga`, `deskripsi`, `gambar`) VALUES ('3', 'Graduation', 'Graduation', '40 Menit', '1-2 Orang', 'Soft File', '100000', 'Paket foto wisuda untuk mengabadikan momen kelulusan secara rapi dan formal.', 'images/graduation.jpg');
INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `kategori`, `durasi`, `kapasitas`, `fasilitas`, `harga`, `deskripsi`, `gambar`) VALUES ('4', 'Photobooth', 'Photobooth', '30 Menit', 'Acara', 'Cetak Foto', '75000', 'Layanan foto cepat untuk acara kecil, gathering, ulang tahun, atau kebutuhan pribadi.', 'images/fotobooth.jpg');
INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `kategori`, `durasi`, `kapasitas`, `fasilitas`, `harga`, `deskripsi`, `gambar`) VALUES ('5', 'Prewedding', 'Prewedding', '90 Menit', '2 Orang', 'Editing', '300000', 'Paket foto pasangan dengan konsep elegan dan romantis untuk kebutuhan prewedding.', 'images/prewed.jpg');
INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `kategori`, `durasi`, `kapasitas`, `fasilitas`, `harga`, `deskripsi`, `gambar`) VALUES ('6', 'Sewa Studio', 'Sewa Studio', 'Per Jam', 'Indoor', 'Properti', '75000', 'Sewa tempat studio untuk kebutuhan foto, konten, dan dokumentasi pribadi.', 'images/studio.jpg');
INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `kategori`, `durasi`, `kapasitas`, `fasilitas`, `harga`, `deskripsi`, `gambar`) VALUES ('7', 'Self Foto', 'Self Studio', '15 Menit', '2 Orang', 'Soft File', '50000', 'Foto mandiri dengan konsep simple dan modern. Cocok untuk foto pribadi, couple, atau konten media sosial', 'images/layanan_1779963126_525.jpg');

-- -----------------------------------------------
-- Table structure for `transaksi_log`
-- -----------------------------------------------
DROP TABLE IF EXISTS `transaksi_log`;
CREATE TABLE `transaksi_log` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `nama_transaksi` varchar(100) NOT NULL,
  `status_transaksi` enum('BERHASIL','GAGAL') NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------
-- Table structure for `users`
-- -----------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_whatsapp` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `no_whatsapp`, `password`, `role`, `created_at`) VALUES ('1', 'Admin ClickSpace', 'admin@clickspace.com', '081234567890', '$2y$10$S7nzPlmDikHVz9LKgvRmE.oqefx9pbRW4M25tOXrEJtI8WxX2bV.W', 'admin', '2026-05-28 16:55:00');
INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `no_whatsapp`, `password`, `role`, `created_at`) VALUES ('2', 'Ardhia Salwa Indriani', 'salwaindriani255@gmail.com', '083140664639', '$2y$10$dBaVaoYGN1vkdCxSxqRc4u6wac5V73Y4yPT9wObouqazC09KRTSP2', 'customer', '2026-05-28 16:56:09');
INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `no_whatsapp`, `password`, `role`, `created_at`) VALUES ('3', 'Zahira Adiah Safa', 'raradiah@gmail.com', '08658543579', '$2y$10$stQQRxraSNYlfDgVsjG0POt3/yOzwDMRVO2TiZFs2jwcPnZE9l3G6', 'customer', '2026-05-28 17:58:05');

DROP VIEW IF EXISTS `v_booking_detail`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_booking_detail` AS select `b`.`id_booking` AS `id_booking`,`b`.`kode_booking` AS `kode_booking`,`b`.`id_user` AS `id_user`,`u`.`nama_lengkap` AS `nama_lengkap`,`u`.`email` AS `email`,`b`.`id_layanan` AS `id_layanan`,`l`.`nama_layanan` AS `nama_layanan`,`l`.`kategori` AS `kategori`,`b`.`nama_customer` AS `nama_customer`,`b`.`no_whatsapp` AS `no_whatsapp`,`b`.`tanggal_booking` AS `tanggal_booking`,date_format(`b`.`tanggal_booking`,'%d-%m-%Y') AS `tanggal_format`,`b`.`jam_booking` AS `jam_booking`,`b`.`metode_pembayaran` AS `metode_pembayaran`,`b`.`bukti_pembayaran` AS `bukti_pembayaran`,`b`.`total_harga` AS `total_harga`,`fn_format_rupiah`(`b`.`total_harga`) AS `total_harga_format`,`b`.`status_booking` AS `status_booking`,`b`.`created_at` AS `created_at` from ((`booking` `b` join `users` `u` on((`b`.`id_user` = `u`.`id_user`))) join `layanan` `l` on((`b`.`id_layanan` = `l`.`id_layanan`)));

DROP VIEW IF EXISTS `v_layanan_ringkas`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_layanan_ringkas` AS select `layanan`.`id_layanan` AS `id_layanan`,upper(`layanan`.`nama_layanan`) AS `nama_layanan_upper`,`layanan`.`kategori` AS `kategori`,`layanan`.`harga` AS `harga`,`fn_format_rupiah`(`layanan`.`harga`) AS `harga_format` from `layanan`;

SET FOREIGN_KEY_CHECKS=1;
