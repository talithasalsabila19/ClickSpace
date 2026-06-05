# 📸 ClickSpace (Proyek UAP)

ClickSpace adalah sistem booking studio foto sederhana yang dibangun menggunakan PHP dan MySQL. Sistem ini digunakan untuk mengelola layanan studio, pemesanan customer, bukti pembayaran, status booking, data user, serta fitur admin.

Project ini dikembangkan untuk memenuhi ketentuan UAP Basis Data Lanjut dengan menerapkan database view, SQL join, set operations, transaction, function, stored procedure, trigger, fragmentasi, backup database, task scheduler, dan simulasi deadlock.

## 📌 Detail Konsep

Stored procedure pada ClickSpace bertindak seperti alur kerja internal database. Operasi penting seperti insert booking, select detail booking, update status, delete booking, dan refresh fragmentasi tidak hanya diproses dari PHP, tetapi juga disimpan langsung di database agar proses lebih konsisten dan mudah dipanggil ulang.

Function digunakan untuk membantu query menghasilkan data yang lebih siap ditampilkan, misalnya mengubah nominal harga menjadi format rupiah. Trigger digunakan untuk mencatat aktivitas perubahan data booking secara otomatis ke tabel audit. Transaction digunakan agar proses booking bisa berhasil penuh atau dibatalkan jika ada kegagalan.

Halaman utama untuk menampilkan implementasi seluruh ketentuan berada pada:

```txt
admin-database.php
```

## 👤 Login Admin

```txt
Email    : admin@clickspace.com
Password : admin123
```

## 🗂️ File Utama Implementasi

```txt
admin-database.php
proses_booking.php
admin-booking.php
admin-update-status.php
admin-hapus-booking.php
backup.php
backup_list.php
scheduled_backup.php
backup_helper.php
tasks/backup_clickspace.bat
database/cs8.sql
```

## 🧩 Beberapa View, Procedure, Function, dan Trigger yang Digunakan

### 📄 admin-database.php

File ini menjadi pusat pembuktian fitur database. Di halaman ini admin dapat melihat hasil query dari view, join, union, function, procedure, trigger, transaction log, fragmentasi, backup, dan simulasi deadlock.

### 1. Database View

View digunakan untuk menyederhanakan query detail booking. Data booking yang berasal dari tabel `booking`, `users`, dan `layanan` digabungkan dalam view `v_booking_detail`.

```sql
CREATE OR REPLACE VIEW v_booking_detail AS
SELECT
  b.id_booking,
  b.kode_booking,
  u.nama_lengkap,
  u.email,
  l.nama_layanan,
  l.kategori,
  b.nama_customer,
  DATE_FORMAT(b.tanggal_booking, '%d-%m-%Y') AS tanggal_format,
  b.jam_booking,
  fn_format_rupiah(b.total_harga) AS total_harga_format,
  b.status_booking,
  b.created_at
FROM booking b
INNER JOIN users u ON b.id_user = u.id_user
INNER JOIN layanan l ON b.id_layanan = l.id_layanan;
```

Pemanggilan view pada sistem:

```php
$viewData = getRows($conn, "SELECT kode_booking, nama_customer, nama_layanan, email, tanggal_format, jam_booking, total_harga_format, status_booking FROM v_booking_detail ORDER BY created_at DESC LIMIT 8");
```

View juga dipakai pada `admin-booking.php` untuk menampilkan data booking admin dengan query yang lebih rapi.

### 2. SQL Join

Join digunakan untuk menggabungkan data customer dan booking. Pada bagian ini sistem memakai `LEFT JOIN` agar customer tetap tampil meskipun belum pernah melakukan booking.

```php
$joinData = getRows($conn, "SELECT u.nama_lengkap, u.email, COUNT(b.id_booking) AS total_booking, fn_format_rupiah(COALESCE(SUM(b.total_harga), 0)) AS total_nilai FROM users u LEFT JOIN booking b ON u.id_user = b.id_user WHERE u.role = 'customer' GROUP BY u.id_user, u.nama_lengkap, u.email ORDER BY total_booking DESC");
```

### 3. Set Operations

Set operation digunakan dengan `UNION ALL` untuk menggabungkan data layanan dan customer dalam satu hasil query.

```php
$unionData = getRows($conn, "(SELECT 'LAYANAN' AS jenis_data, nama_layanan AS nama_data, kategori AS keterangan FROM layanan) UNION ALL (SELECT 'CUSTOMER' AS jenis_data, nama_lengkap AS nama_data, email AS keterangan FROM users WHERE role = 'customer') ORDER BY jenis_data, nama_data LIMIT 12");
```

### 4. Transaction

Transaction diterapkan pada proses booking. Jika procedure insert berhasil, sistem menjalankan `COMMIT`. Jika gagal, sistem menjalankan `ROLLBACK` dan file bukti pembayaran yang sudah terunggah akan dihapus.

```php
mysqli_begin_transaction($conn);
mysqli_query($conn, "SET @new_booking_id = 0");
$stmt = mysqli_prepare($conn, "CALL sp_insert_booking(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @new_booking_id)");

if ($stmt) {
  mysqli_stmt_bind_param($stmt, 'siissssssis', $kode_booking, $id_user, $id_layanan, $nama_customer, $no_whatsapp, $tanggal_booking, $jam_booking, $metode_pembayaran, $lokasi_file, $total_harga, $status_booking);
  $simpan = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  clearStoredResultsBooking($conn);
} else {
  $simpan = false;
}

if ($simpan) {
  mysqli_commit($conn);
  header("Location: success.php?kode=" . $kode_booking);
  exit;
} else {
  mysqli_rollback($conn);
  if (file_exists($lokasi_file)) {
    unlink($lokasi_file);
  }
  header("Location: booking-form.php?error=failed");
  exit;
}
```

### 5. Function

Function yang digunakan terdiri dari built-in function dan custom function.

Built-in function:

```sql
UPPER()
CHAR_LENGTH()
COALESCE()
DATE_FORMAT()
```

Custom function:

```sql
CREATE FUNCTION fn_format_rupiah(p_nominal INT)
RETURNS VARCHAR(60)
DETERMINISTIC
BEGIN
  RETURN CONCAT('Rp', REPLACE(FORMAT(IFNULL(p_nominal, 0), 0), ',', '.'));
END
```

Pemanggilan function pada sistem:

```php
$functionData = getRows($conn, "SELECT nama_layanan, UPPER(kategori) AS built_in_upper_kategori, CHAR_LENGTH(deskripsi) AS built_in_panjang_deskripsi, fn_format_rupiah(harga) AS custom_fn_harga FROM layanan ORDER BY id_layanan ASC");
```

### 6. Stored Procedure

Procedure digunakan untuk operasi `INSERT`, `SELECT`, `UPDATE`, dan `DELETE`.

#### sp_insert_booking

Digunakan pada `proses_booking.php` dan `admin-database.php` untuk menambahkan booking baru.

```php
$stmt = mysqli_prepare($conn, "CALL sp_insert_booking(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @new_booking_id)");
mysqli_stmt_bind_param($stmt, 'siissssssis', $kode_booking, $id_user, $id_layanan, $nama_customer, $no_whatsapp, $tanggal_booking, $jam_booking, $metode_pembayaran, $lokasi_file, $total_harga, $status_booking);
mysqli_stmt_execute($stmt);
```

#### sp_select_booking_detail

Digunakan untuk menampilkan seluruh detail booking dari view.

```php
$procedureData = getProcedureRows($conn, "CALL sp_select_booking_detail()");
```

#### sp_update_booking_status

Digunakan pada `admin-update-status.php` untuk mengubah status booking.

```php
$stmt = mysqli_prepare($conn, "CALL sp_update_booking_status(?, ?)");
mysqli_stmt_bind_param($stmt, 'is', $id_booking, $status_booking);
$ok = mysqli_stmt_execute($stmt);
```

#### sp_delete_booking

Digunakan pada `admin-hapus-booking.php` untuk menghapus data booking.

```php
$stmt = mysqli_prepare($conn, "CALL sp_delete_booking(?)");
mysqli_stmt_bind_param($stmt, 'i', $id_booking);
$query = mysqli_stmt_execute($stmt);
```

### 7. Trigger

Trigger digunakan untuk mencatat aktivitas booking secara otomatis ke tabel `booking_audit`.

```sql
CREATE TRIGGER trg_booking_after_insert
AFTER INSERT ON booking
FOR EACH ROW
BEGIN
  INSERT INTO booking_audit
  (id_booking, kode_booking, aksi, status_lama, status_baru, keterangan)
  VALUES
  (NEW.id_booking, NEW.kode_booking, 'INSERT', NULL, NEW.status_booking, 'Data booking baru ditambahkan melalui sistem/procedure.');
END
```

Trigger lain yang digunakan:

```txt
trg_booking_after_update
trg_booking_before_delete
```

Hasil trigger ditampilkan pada `admin-database.php`.

```php
$triggerData = getRows($conn, "SELECT aksi, kode_booking, status_lama, status_baru, keterangan, created_at FROM booking_audit ORDER BY created_at DESC LIMIT 8");
```

### 8. Fragmentasi

Fragmentasi dilakukan dengan memisahkan data booking aktif dan booking selesai ke tabel berbeda.

```txt
booking_fragment_aktif
booking_fragment_selesai
```

Procedure yang digunakan:

```sql
CREATE PROCEDURE sp_refresh_booking_fragmentasi()
BEGIN
  TRUNCATE TABLE booking_fragment_aktif;
  TRUNCATE TABLE booking_fragment_selesai;

  INSERT INTO booking_fragment_aktif
  SELECT * FROM booking
  WHERE status_booking IN ('Menunggu Konfirmasi', 'Dikonfirmasi');

  INSERT INTO booking_fragment_selesai
  SELECT * FROM booking
  WHERE status_booking IN ('Selesai', 'Dibatalkan');
END
```

Pemanggilan pada sistem:

```php
$ok = mysqli_query($conn, "CALL sp_refresh_booking_fragmentasi()");
```

### 9. Deadlock Simulation

Simulasi deadlock dibuat dengan logika dua proses yang saling menunggu resource. Sistem menampilkan kondisi `WAITING`, lalu menangani konflik dengan `ROLLBACK` dan `RETRY`.

```php
$steps = [
  ['Proses A', 'booking:sample', 'LOCKED', 'Proses A mengunci data booking terlebih dahulu.'],
  ['Proses B', 'layanan:sample', 'LOCKED', 'Proses B mengunci data layanan terlebih dahulu.'],
  ['Proses A', 'layanan:sample', 'WAITING', 'Proses A ingin mengakses layanan, tetapi resource sedang dikunci Proses B. Kondisi waiting.'],
  ['Proses B', 'booking:sample', 'WAITING', 'Proses B ingin mengakses booking, tetapi resource sedang dikunci Proses A. Kondisi waiting.'],
  ['SYSTEM', 'Proses B', 'ROLLBACK', 'Sistem memilih Proses B untuk rollback agar deadlock selesai.'],
  ['Proses B', 'booking:sample + layanan:sample', 'RETRY', 'Proses B dijalankan ulang setelah resource bebas.'],
  ['Proses B', 'booking:sample + layanan:sample', 'COMMIT', 'Retry berhasil dan transaksi selesai.']
];
```

Log simulasi disimpan pada tabel:

```txt
deadlock_logs
```

## 💾 Backup Database dan Task Scheduler

Untuk menjaga keamanan data, ClickSpace memiliki fitur backup database manual dan otomatis. Backup dibuat dalam format `.sql`, lalu disimpan ke folder `storage/backups` dengan nama file yang menggunakan timestamp.

Backup manual dijalankan melalui:

```txt
backup.php
backup_list.php
```

Backup otomatis dijalankan melalui:

```txt
scheduled_backup.php
tasks/backup_clickspace.bat
```

### 📄 backup.php

```php
<?php
session_start();
include "koneksi.php";
require_once "backup_helper.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

$result = clickspace_run_backup($conn, 'manual');
$status = $result['ok'] ? 'success' : 'failed';
header("Location: backup_list.php?status=" . $status . "&message=" . urlencode($result['message']) . "&file=" . urlencode($result['file']));
exit;
?>
```

### 📄 scheduled_backup.php

```php
<?php
include "koneksi.php";
require_once "backup_helper.php";

$result = clickspace_run_backup($conn, 'scheduler');
echo $result['message'];
?>
```

### 📄 tasks/backup_clickspace.bat

```bat
@echo off

set PHP_EXE=C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe
set PROJECT_DIR=C:\laragon\www\ClickSpace

cd /d "%PROJECT_DIR%"
"%PHP_EXE%" "%PROJECT_DIR%\scheduled_backup.php"
```
