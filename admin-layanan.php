<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

function rupiah($angka) {
  return "Rp" . number_format($angka, 0, ',', '.');
}

$query_layanan = mysqli_query($conn, "SELECT * FROM layanan ORDER BY id_layanan ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Layanan - Admin ClickSpace</title>
  <link rel="stylesheet" href="style.css?v=70">
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
      <h1>Data Layanan</h1>
      <span>Admin dapat menambah, melihat, mengedit, dan menghapus layanan ClickSpace.</span>
    </div>

    <a href="admin-tambah-layanan.php" class="admin-hero-button">
      + Tambah Layanan
    </a>
  </section>

  <?php if (isset($_GET['created']) && $_GET['created'] == 'success') { ?>
    <div class="admin-alert success">
      Data layanan berhasil ditambahkan.
    </div>
  <?php } ?>

  <?php if (isset($_GET['updated']) && $_GET['updated'] == 'success') { ?>
    <div class="admin-alert success">
      Data layanan berhasil diperbarui.
    </div>
  <?php } ?>

  <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'success') { ?>
    <div class="admin-alert success">
      Data layanan berhasil dihapus.
    </div>
  <?php } ?>

  <section class="admin-service-grid">

    <?php if (mysqli_num_rows($query_layanan) > 0) { ?>

      <?php while ($layanan = mysqli_fetch_assoc($query_layanan)) { ?>

        <div class="admin-service-card">

          <img
            src="<?php echo htmlspecialchars($layanan['gambar']); ?>"
            alt="<?php echo htmlspecialchars($layanan['nama_layanan']); ?>"
          >

          <div class="admin-service-content">

            <span><?php echo htmlspecialchars($layanan['kategori']); ?></span>

            <h2><?php echo htmlspecialchars($layanan['nama_layanan']); ?></h2>

            <p><?php echo htmlspecialchars($layanan['deskripsi']); ?></p>

            <div class="admin-service-meta">
              <small><?php echo htmlspecialchars($layanan['durasi']); ?></small>
              <small><?php echo htmlspecialchars($layanan['kapasitas']); ?></small>
              <small><?php echo htmlspecialchars($layanan['fasilitas']); ?></small>
            </div>

            <strong><?php echo rupiah($layanan['harga']); ?></strong>

            <div class="admin-service-action">
              <a
                href="admin-edit-layanan.php?id=<?php echo htmlspecialchars($layanan['id_layanan']); ?>"
                class="admin-edit-btn"
              >
                Edit Layanan
              </a>

              <a
                href="admin-hapus-layanan.php?id=<?php echo htmlspecialchars($layanan['id_layanan']); ?>"
                class="admin-delete-btn"
                onclick="return confirm('Yakin ingin menghapus layanan ini?');"
              >
                Hapus
              </a>
            </div>

          </div>

        </div>

      <?php } ?>

    <?php } else { ?>

      <div class="empty-box">
        <h3>Belum Ada Layanan</h3>
        <p>Data layanan belum tersedia. Silakan tambah layanan baru.</p>
        <a href="admin-tambah-layanan.php" class="button">Tambah Layanan</a>
      </div>

    <?php } ?>

  </section>

</main>

<footer>
  <p>© 2026 ClickSpace</p>
</footer>

</body>
</html>
