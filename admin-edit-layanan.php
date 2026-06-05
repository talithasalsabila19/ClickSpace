<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

if (!isset($_GET['id'])) {
  header("Location: admin-layanan.php");
  exit;
}

$id_layanan = mysqli_real_escape_string($conn, $_GET['id']);

$query = mysqli_query($conn, "SELECT * FROM layanan WHERE id_layanan='$id_layanan'");
$layanan = mysqli_fetch_assoc($query);

if (!$layanan) {
  header("Location: admin-layanan.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Layanan - Admin ClickSpace</title>
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
      <h1>Edit Layanan</h1>
      <span>Ubah informasi layanan yang akan tampil pada halaman customer ClickSpace.</span>
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
            echo "Gambar layanan gagal diproses.";
          } elseif ($_GET['error'] == 'file') {
            echo "Format gambar tidak valid. Gunakan JPG, JPEG, atau PNG.";
          } elseif ($_GET['error'] == 'size') {
            echo "Ukuran gambar terlalu besar. Maksimal 2 MB.";
          } elseif ($_GET['error'] == 'upload') {
            echo "Gambar gagal diupload. Silakan coba lagi.";
          } else {
            echo "Layanan gagal diperbarui. Periksa kembali data yang diisi.";
          }
        ?>
      </div>
    <?php } ?>

    <form action="admin-update-layanan.php" method="POST" enctype="multipart/form-data" class="admin-edit-form">

      <input type="hidden" name="id_layanan" value="<?php echo htmlspecialchars($layanan['id_layanan']); ?>">
      <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($layanan['gambar']); ?>">

      <div class="admin-edit-grid">

        <div class="admin-edit-left">

          <label>Nama Layanan</label>
          <input
            type="text"
            name="nama_layanan"
            value="<?php echo htmlspecialchars($layanan['nama_layanan']); ?>"
            required
          >

          <label>Kategori</label>
          <input
            type="text"
            name="kategori"
            value="<?php echo htmlspecialchars($layanan['kategori']); ?>"
            required
          >

          <label>Durasi</label>
          <input
            type="text"
            name="durasi"
            value="<?php echo htmlspecialchars($layanan['durasi']); ?>"
            required
          >

          <label>Kapasitas</label>
          <input
            type="text"
            name="kapasitas"
            value="<?php echo htmlspecialchars($layanan['kapasitas']); ?>"
            required
          >

          <label>Fasilitas</label>
          <input
            type="text"
            name="fasilitas"
            value="<?php echo htmlspecialchars($layanan['fasilitas']); ?>"
            required
          >

          <label>Harga</label>
          <input
            type="number"
            name="harga"
            value="<?php echo htmlspecialchars($layanan['harga']); ?>"
            min="1"
            required
          >

        </div>

        <div class="admin-edit-right">

          <label>Gambar Saat Ini</label>
          <div class="admin-current-image">
            <img
              src="<?php echo htmlspecialchars($layanan['gambar']); ?>"
              alt="<?php echo htmlspecialchars($layanan['nama_layanan']); ?>"
            >
          </div>

          <label>Ganti Gambar</label>
          <input
            type="file"
            name="gambar"
            accept=".jpg,.jpeg,.png"
          >

          <div class="admin-image-note">
            <p>Gunakan gambar JPG, JPEG, atau PNG.</p>
            <p>Ukuran maksimal gambar 2 MB.</p>
            <p>Jika tidak ingin mengganti gambar, kosongkan bagian ini.</p>
          </div>

        </div>

      </div>

      <label>Deskripsi</label>
      <textarea
        name="deskripsi"
        required
      ><?php echo htmlspecialchars($layanan['deskripsi']); ?></textarea>

      <div class="admin-edit-actions">
        <button type="submit">Simpan Perubahan</button>
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
