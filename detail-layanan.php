<?php
session_start();
include "koneksi.php";

$sudah_login = isset($_SESSION['id_user']);

if (!isset($_GET['id'])) {
  header("Location: layanan-login.php");
  exit;
}

$id_layanan = mysqli_real_escape_string($conn, $_GET['id']);

$query = mysqli_query($conn, "SELECT * FROM layanan WHERE id_layanan='$id_layanan'");
$layanan = mysqli_fetch_assoc($query);

if (!$layanan) {
  header("Location: layanan-login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Layanan - ClickSpace</title>
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
  <section class="detail-wrapper">

    <div class="detail-left">

      <div class="detail-img">
        <img
          src="<?php echo htmlspecialchars($layanan['gambar']); ?>"
          alt="<?php echo htmlspecialchars($layanan['nama_layanan']); ?>"
        >
      </div>

      <div class="why-box">
        <h3>Kenapa pilih paket ini?</h3>

        <div class="why-grid">
          <div class="why-card">
            <h4>Praktis</h4>
            <p>Layanan mudah dipilih sesuai kebutuhan customer.</p>
          </div>

          <div class="why-card">
            <h4>Nyaman</h4>
            <p>Sesi foto dilakukan dengan suasana studio yang nyaman.</p>
          </div>

          <div class="why-card">
            <h4>Berkualitas</h4>
            <p>Hasil foto disesuaikan dengan paket layanan yang dipilih.</p>
          </div>
        </div>
      </div>

      <a href="layanan-login.php" class="back-detail">← Kembali ke Layanan</a>
    </div>

    <div class="detail-card">

      <p class="category">
        <?php echo htmlspecialchars($layanan['kategori']); ?>
      </p>

      <h1>
        <?php echo htmlspecialchars($layanan['nama_layanan']); ?>
      </h1>

      <p class="new-price">
        Rp<?php echo number_format($layanan['harga'], 0, ',', '.'); ?>
      </p>

      <div class="tags">
        <span><?php echo htmlspecialchars($layanan['durasi']); ?></span>
        <span><?php echo htmlspecialchars($layanan['kapasitas']); ?></span>
        <span><?php echo htmlspecialchars($layanan['fasilitas']); ?></span>
      </div>

      <div class="detail-text">
        <p><?php echo htmlspecialchars($layanan['deskripsi']); ?></p>
        <p>- Durasi sesi foto <?php echo htmlspecialchars(strtolower($layanan['durasi'])); ?>.</p>
        <p>- Cocok untuk kebutuhan foto di ClickSpace Studio.</p>
        <p>- Konsep foto dapat disesuaikan dengan kebutuhan customer.</p>
      </div>

      <div class="bonus">
        <p><b>Bonus:</b></p>
        <p>- Arahan pose dari tim studio.</p>
        <p>- Background studio pilihan.</p>
        <p>- Hasil foto pilihan.</p>
      </div>

      <div class="studio-info">
        <p>Lokasi Studio: ClickSpace Studio</p>
        <p>Jam Operasional: 10.00 - 20.00 WIB</p>
        <p>Ditangani oleh tim studio ClickSpace</p>
      </div>

      <a href="booking-form.php" class="book-btn">Booking Sekarang</a>
    </div>

  </section>
</main>

<footer>
  <p>© 2026 ClickSpace</p>
</footer>

</body>
</html>
