<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Layanan - Admin ClickSpace</title>
  <link rel="stylesheet" href="style.css?v=90">
</head>

<body class="admin-page">

<header>
  <div class="logo">ClickSpace Admin</div>

  <nav>
    <a href="admin-dashboard.php">Dashboard</a>
    <a href="admin-booking.php">Data Booking</a>
    <a href="admin-users.php">Customer</a>
    <a href="admin-layanan.php">Layanan</a>
    <a href="admin-database.php">Database</a>
    <a href="backup_list.php">Backup</a>
    <a href="logout.php" class="btn-login">Logout</a>
  </nav>
</header>

<main class="admin-main">

  <section class="admin-page-title">
    <div>
      <p>ADMIN PANEL</p>
      <h1>Tambah Layanan</h1>
      <span>Tambahkan paket layanan baru yang akan tampil pada halaman customer.</span>
    </div>

    <a href="admin-layanan.php" class="admin-hero-button">Kembali</a>
  </section>

  <section class="admin-panel">

    <?php if (isset($_GET['error'])) { ?>
      <div class="admin-alert danger">
        <?php
          if ($_GET['error'] == 'empty') {
            echo "Semua data layanan wajib diisi.";
          } elseif ($_GET['error'] == 'harga') {
            echo "Harga layanan harus berupa angka dan lebih dari 0.";
          } elseif ($_GET['error'] == 'gambar') {
            echo "Gambar layanan wajib diupload.";
          } elseif ($_GET['error'] == 'file') {
            echo "Format gambar tidak valid. Gunakan JPG, JPEG, atau PNG.";
          } elseif ($_GET['error'] == 'size') {
            echo "Ukuran gambar terlalu besar. Maksimal 2 MB.";
          } elseif ($_GET['error'] == 'upload') {
            echo "Gambar gagal diupload. Silakan coba lagi.";
          } else {
            echo "Layanan gagal ditambahkan. Periksa kembali data yang diisi.";
          }
        ?>
      </div>
    <?php } ?>

    <form action="admin-simpan-layanan.php" method="POST" enctype="multipart/form-data" class="admin-edit-form">

      <div class="admin-edit-grid">

        <div class="admin-edit-left">

          <label>Nama Layanan</label>
          <input
            type="text"
            name="nama_layanan"
            placeholder="Contoh: Self Studio"
            required
          >

          <label>Kategori</label>
          <input
            type="text"
            name="kategori"
            placeholder="Contoh: Self Studio / Family / Graduation"
            required
          >

          <label>Durasi</label>
          <input
            type="text"
            name="durasi"
            placeholder="Contoh: 30 Menit"
            required
          >

          <label>Kapasitas</label>
          <input
            type="text"
            name="kapasitas"
            placeholder="Contoh: 2 Orang"
            required
          >

          <label>Fasilitas</label>
          <input
            type="text"
            name="fasilitas"
            placeholder="Contoh: Soft File"
            required
          >

          <label>Harga</label>
          <input
            type="number"
            name="harga"
            placeholder="Contoh: 50000"
            min="1"
            required
          >

        </div>

        <div class="admin-edit-right">

          <label>Gambar Layanan</label>
          <input
            type="file"
            name="gambar"
            accept=".jpg,.jpeg,.png"
            required
          >

          <div class="admin-image-note">
            <p>Gunakan gambar JPG, JPEG, atau PNG.</p>
            <p>Ukuran maksimal gambar 2 MB.</p>
            <p>Gambar ini akan tampil di halaman customer.</p>
          </div>

        </div>

      </div>

      <label>Deskripsi</label>
      <textarea
        name="deskripsi"
        placeholder="Masukkan deskripsi layanan"
        required
      ></textarea>

      <div class="admin-edit-actions">
        <button type="submit">Simpan Layanan</button>
        <a href="admin-layanan.php">Batal</a>
      </div>

    </form>

  </section>

</main>

<footer>
  <p>© 2026 ClickSpace</p>
</footer>

</body>
</html>
