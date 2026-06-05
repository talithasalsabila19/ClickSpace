<?php
session_start();
include "koneksi.php";

$sudah_login = isset($_SESSION['id_user']);

function rupiah($angka) {
  return "Rp" . number_format($angka, 0, ',', '.');
}

$query_layanan = mysqli_query($conn, "SELECT * FROM layanan ORDER BY id_layanan ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Layanan - ClickSpace</title>
  <link rel="stylesheet" href="style.css?v=60">
</head>
<body>

<header>
  <div class="logo">ClickSpace</div>

  <nav>
    <?php if ($sudah_login) { ?>
      <a href="home-login.php">Home</a>
    <?php } else { ?>
      <a href="index.html">Home</a>
    <?php } ?>

    <a href="layanan-login.php">Layanan</a>
    <a href="booking-form.php">Booking</a>
    <a href="status.php">Riwayat</a>

    <?php if ($sudah_login) { ?>
      <a href="logout.php" class="btn-login">Logout</a>
    <?php } else { ?>
      <a href="login.php" class="btn-login">Login</a>
    <?php } ?>
  </nav>
</header>

<main>

  <section class="service-hero">
    <div class="service-hero-content">
      <p class="service-label">CLICKSPACE STUDIO</p>
      <h1>Temukan Layanan Foto Terbaik</h1>
      <p>Pilih paket foto sesuai kebutuhanmu di ClickSpace Studio.</p>
    </div>
  </section>

  <section class="section service-list-section">
    <div class="service-heading">
      <div>
        <p class="service-label">DAFTAR LAYANAN</p>
        <h2>Paket Foto ClickSpace</h2>
      </div>
    </div>

    <div class="service-grid">

      <?php if (mysqli_num_rows($query_layanan) > 0) { ?>

        <?php while ($layanan = mysqli_fetch_assoc($query_layanan)) { ?>

          <div class="service-card">
            <div class="service-image">
              <img
                src="<?php echo htmlspecialchars($layanan['gambar']); ?>"
                alt="<?php echo htmlspecialchars($layanan['nama_layanan']); ?>"
              >

              <span><?php echo htmlspecialchars($layanan['kategori']); ?></span>
            </div>

            <div class="service-content">
              <p class="service-location">ClickSpace Studio</p>

              <h3><?php echo htmlspecialchars($layanan['nama_layanan']); ?></h3>

              <p class="service-desc">
                <?php echo htmlspecialchars($layanan['deskripsi']); ?>
              </p>

              <div class="service-info">
                <span><?php echo htmlspecialchars($layanan['durasi']); ?></span>
                <span><?php echo htmlspecialchars($layanan['kapasitas']); ?></span>
                <span><?php echo htmlspecialchars($layanan['fasilitas']); ?></span>
              </div>

              <div class="service-bottom">
                <strong><?php echo rupiah($layanan['harga']); ?></strong>

                <a href="detail-layanan.php?id=<?php echo htmlspecialchars($layanan['id_layanan']); ?>">
                  Lihat Detail →
                </a>
              </div>
            </div>
          </div>

        <?php } ?>

      <?php } else { ?>

        <div class="empty-box">
          <h3>Belum Ada Layanan</h3>
          <p>Data layanan belum tersedia.</p>
        </div>

      <?php } ?>

    </div>

    <div class="service-action-center">
      <a href="booking-form.php" class="button service-booking-button">Booking Sekarang</a>
    </div>
  </section>

</main>

<footer>
  <p>© 2026 ClickSpace</p>
</footer>

</body>
</html>
