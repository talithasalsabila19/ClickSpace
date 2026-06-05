CREATE DATABASE IF NOT EXISTS `cs8` CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `cs8`;


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";






CREATE TABLE `booking` (
  `id_booking` int NOT NULL,
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


INSERT INTO `booking` (`id_booking`, `kode_booking`, `id_user`, `id_layanan`, `nama_customer`, `no_whatsapp`, `tanggal_booking`, `jam_booking`, `metode_pembayaran`, `bukti_pembayaran`, `total_harga`, `status_booking`, `created_at`) VALUES
(4, 'CS20260528095649', 2, 3, 'salwa', '083140664639', '2026-06-02', '10.00 - 11.00', 'Transfer Bank', 'uploads/bukti_1779962209_490.png', 100000, 'Menunggu Konfirmasi', '2026-05-28 09:56:49'),
(5, 'CS20260528110011', 3, 4, 'Zahira Adiah Safa', '0865379738010', '2026-07-02', '10.00 - 11.00', 'DANA', 'uploads/bukti_1779966011_667.png', 75000, 'Menunggu Konfirmasi', '2026-05-28 11:00:11');



CREATE TABLE `layanan` (
  `id_layanan` int NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `durasi` varchar(50) NOT NULL,
  `kapasitas` varchar(50) NOT NULL,
  `fasilitas` varchar(100) NOT NULL,
  `harga` int NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `kategori`, `durasi`, `kapasitas`, `fasilitas`, `harga`, `deskripsi`, `gambar`) VALUES
(2, 'Foto Family', 'Foto Keluarga', '45 Menit', '4-6 Orang', 'Soft File', 150000, 'Paket foto keluarga dengan suasana nyaman dan hasil foto yang cocok untuk kenangan keluarga.', 'images/foto keluarga.jpg'),
(3, 'Graduation', 'Graduation', '40 Menit', '1-2 Orang', 'Soft File', 100000, 'Paket foto wisuda untuk mengabadikan momen kelulusan secara rapi dan formal.', 'images/graduation.jpg'),
(4, 'Photobooth', 'Photobooth', '30 Menit', 'Acara', 'Cetak Foto', 75000, 'Layanan foto cepat untuk acara kecil, gathering, ulang tahun, atau kebutuhan pribadi.', 'images/fotobooth.jpg'),
(5, 'Prewedding', 'Prewedding', '90 Menit', '2 Orang', 'Editing', 300000, 'Paket foto pasangan dengan konsep elegan dan romantis untuk kebutuhan prewedding.', 'images/prewed.jpg'),
(6, 'Sewa Studio', 'Sewa Studio', 'Per Jam', 'Indoor', 'Properti', 75000, 'Sewa tempat studio untuk kebutuhan foto, konten, dan dokumentasi pribadi.', 'images/studio.jpg'),
(7, 'Self Foto', 'Self Studio', '15 Menit', '2 Orang', 'Soft File', 50000, 'Foto mandiri dengan konsep simple dan modern. Cocok untuk foto pribadi, couple, atau konten media sosial', 'images/layanan_1779963126_525.jpg');



CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_whatsapp` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `no_whatsapp`, `password`, `role`, `created_at`) VALUES
(1, 'Admin ClickSpace', 'admin@clickspace.com', '081234567890', '$2y$10$S7nzPlmDikHVz9LKgvRmE.oqefx9pbRW4M25tOXrEJtI8WxX2bV.W', 'admin', '2026-05-28 09:55:00'),
(2, 'Ardhia Salwa Indriani', 'salwaindriani255@gmail.com', '083140664639', '$2y$10$dBaVaoYGN1vkdCxSxqRc4u6wac5V73Y4yPT9wObouqazC09KRTSP2', 'customer', '2026-05-28 09:56:09'),
(3, 'Zahira Adiah Safa', 'raradiah@gmail.com', '08658543579', '$2y$10$stQQRxraSNYlfDgVsjG0POt3/yOzwDMRVO2TiZFs2jwcPnZE9l3G6', 'customer', '2026-05-28 10:58:05');


ALTER TABLE `booking`
  ADD PRIMARY KEY (`id_booking`),
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_layanan` (`id_layanan`);

ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `booking`
  MODIFY `id_booking` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `layanan`
  MODIFY `id_layanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;


ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE CASCADE;
COMMIT;




USE `cs8`;

CREATE TABLE IF NOT EXISTS `booking_audit` (
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

CREATE TABLE IF NOT EXISTS `transaksi_log` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `nama_transaksi` varchar(100) NOT NULL,
  `status_transaksi` enum('BERHASIL','GAGAL') NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `deadlock_resource` (
  `id_resource` int NOT NULL AUTO_INCREMENT,
  `resource_name` varchar(80) NOT NULL,
  `locked_by` varchar(30) DEFAULT NULL,
  `status_resource` enum('FREE','LOCKED','WAITING') DEFAULT 'FREE',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_resource`),
  UNIQUE KEY `resource_name` (`resource_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `deadlock_resource` (`resource_name`, `locked_by`, `status_resource`) VALUES
('booking:sample', NULL, 'FREE'),
('layanan:sample', NULL, 'FREE')
ON DUPLICATE KEY UPDATE `locked_by` = VALUES(`locked_by`), `status_resource` = VALUES(`status_resource`);

CREATE TABLE IF NOT EXISTS `deadlock_logs` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `nama_proses` varchar(30) NOT NULL,
  `resource_name` varchar(80) DEFAULT NULL,
  `status_step` enum('LOCKED','WAITING','ROLLBACK','RETRY','COMMIT','INFO') NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `backup_logs` (
  `id_backup` int NOT NULL AUTO_INCREMENT,
  `nama_file` varchar(255) NOT NULL,
  `lokasi_file` varchar(255) NOT NULL,
  `sumber_backup` enum('manual','scheduler') DEFAULT 'manual',
  `status_backup` enum('success','failed') DEFAULT 'success',
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_backup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `booking_fragment_aktif` LIKE `booking`;
CREATE TABLE IF NOT EXISTS `booking_fragment_selesai` LIKE `booking`;

DROP FUNCTION IF EXISTS `fn_format_rupiah`;
DELIMITER $$
CREATE FUNCTION `fn_format_rupiah`(p_nominal INT)
RETURNS VARCHAR(60)
DETERMINISTIC
BEGIN
  RETURN CONCAT('Rp', REPLACE(FORMAT(IFNULL(p_nominal, 0), 0), ',', '.'));
END$$
DELIMITER ;

CREATE OR REPLACE VIEW `v_booking_detail` AS
SELECT
  b.id_booking,
  b.kode_booking,
  b.id_user,
  u.nama_lengkap,
  u.email,
  b.id_layanan,
  l.nama_layanan,
  l.kategori,
  b.nama_customer,
  b.no_whatsapp,
  b.tanggal_booking,
  DATE_FORMAT(b.tanggal_booking, '%d-%m-%Y') AS tanggal_format,
  b.jam_booking,
  b.metode_pembayaran,
  b.bukti_pembayaran,
  b.total_harga,
  fn_format_rupiah(b.total_harga) AS total_harga_format,
  b.status_booking,
  b.created_at
FROM booking b
INNER JOIN users u ON b.id_user = u.id_user
INNER JOIN layanan l ON b.id_layanan = l.id_layanan;

CREATE OR REPLACE VIEW `v_layanan_ringkas` AS
SELECT
  id_layanan,
  UPPER(nama_layanan) AS nama_layanan_upper,
  kategori,
  harga,
  fn_format_rupiah(harga) AS harga_format
FROM layanan;

DROP PROCEDURE IF EXISTS `sp_insert_booking`;
DROP PROCEDURE IF EXISTS `sp_select_booking_detail`;
DROP PROCEDURE IF EXISTS `sp_update_booking_status`;
DROP PROCEDURE IF EXISTS `sp_delete_booking`;
DROP PROCEDURE IF EXISTS `sp_refresh_booking_fragmentasi`;
DELIMITER $$
CREATE PROCEDURE `sp_insert_booking`(
  IN p_kode_booking VARCHAR(20),
  IN p_id_user INT,
  IN p_id_layanan INT,
  IN p_nama_customer VARCHAR(100),
  IN p_no_whatsapp VARCHAR(20),
  IN p_tanggal_booking DATE,
  IN p_jam_booking VARCHAR(50),
  IN p_metode_pembayaran VARCHAR(50),
  IN p_bukti_pembayaran VARCHAR(255),
  IN p_total_harga INT,
  IN p_status_booking VARCHAR(50),
  OUT p_id_booking INT
)
BEGIN
  INSERT INTO booking
  (kode_booking, id_user, id_layanan, nama_customer, no_whatsapp, tanggal_booking, jam_booking, metode_pembayaran, bukti_pembayaran, total_harga, status_booking)
  VALUES
  (p_kode_booking, p_id_user, p_id_layanan, p_nama_customer, p_no_whatsapp, p_tanggal_booking, p_jam_booking, p_metode_pembayaran, p_bukti_pembayaran, p_total_harga, p_status_booking);
  SET p_id_booking = LAST_INSERT_ID();
END$$

CREATE PROCEDURE `sp_select_booking_detail`()
BEGIN
  SELECT * FROM v_booking_detail ORDER BY created_at DESC;
END$$

CREATE PROCEDURE `sp_update_booking_status`(
  IN p_id_booking INT,
  IN p_status_booking VARCHAR(50)
)
BEGIN
  UPDATE booking
  SET status_booking = p_status_booking
  WHERE id_booking = p_id_booking;
END$$

CREATE PROCEDURE `sp_delete_booking`(
  IN p_id_booking INT
)
BEGIN
  DELETE FROM booking WHERE id_booking = p_id_booking;
END$$

CREATE PROCEDURE `sp_refresh_booking_fragmentasi`()
BEGIN
  TRUNCATE TABLE booking_fragment_aktif;
  TRUNCATE TABLE booking_fragment_selesai;

  INSERT INTO booking_fragment_aktif
  SELECT * FROM booking
  WHERE status_booking IN ('Menunggu Konfirmasi', 'Dikonfirmasi');

  INSERT INTO booking_fragment_selesai
  SELECT * FROM booking
  WHERE status_booking IN ('Selesai', 'Dibatalkan');
END$$
DELIMITER ;

CALL sp_refresh_booking_fragmentasi();

DROP TRIGGER IF EXISTS `trg_booking_after_insert`;
DROP TRIGGER IF EXISTS `trg_booking_after_update`;
DROP TRIGGER IF EXISTS `trg_booking_before_delete`;
DELIMITER $$
CREATE TRIGGER `trg_booking_after_insert`
AFTER INSERT ON `booking`
FOR EACH ROW
BEGIN
  INSERT INTO booking_audit
  (id_booking, kode_booking, aksi, status_lama, status_baru, keterangan)
  VALUES
  (NEW.id_booking, NEW.kode_booking, 'INSERT', NULL, NEW.status_booking, 'Data booking baru ditambahkan melalui sistem/procedure.');
END$$

CREATE TRIGGER `trg_booking_after_update`
AFTER UPDATE ON `booking`
FOR EACH ROW
BEGIN
  IF OLD.status_booking <> NEW.status_booking THEN
    INSERT INTO booking_audit
    (id_booking, kode_booking, aksi, status_lama, status_baru, keterangan)
    VALUES
    (NEW.id_booking, NEW.kode_booking, 'UPDATE', OLD.status_booking, NEW.status_booking, 'Status booking diperbarui dan dicatat otomatis oleh trigger.');
  END IF;
END$$

CREATE TRIGGER `trg_booking_before_delete`
BEFORE DELETE ON `booking`
FOR EACH ROW
BEGIN
  INSERT INTO booking_audit
  (id_booking, kode_booking, aksi, status_lama, status_baru, keterangan)
  VALUES
  (OLD.id_booking, OLD.kode_booking, 'DELETE', OLD.status_booking, NULL, 'Data booking akan dihapus dan dicatat otomatis oleh trigger.');
END$$
DELIMITER ;
