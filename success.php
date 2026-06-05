<?php
session_start();
include "koneksi.php";

if (!isset($_GET['kode'])) {
  header("Location: home-login.php");
  exit;
}

$kode_booking = $_GET['kode'];

$query = mysqli_query($conn, "
  SELECT booking.*, layanan.nama_layanan
  FROM booking
  JOIN layanan ON booking.id_layanan = layanan.id_layanan
  WHERE booking.kode_booking='$kode_booking'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
  header("Location: home-login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking Berhasil - ClickSpace</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="success-page">

<header>
  <div class="logo">ClickSpace</div>

  <nav>
    <a href="home-login.php">Home</a>
    <a href="layanan-login.php">Layanan</a>
    <a href="booking-form.php">Booking</a>
    <a href="status.php">Riwayat</a>
    <a href="logout.php" class="btn-login">Logout</a>
  </nav>
</header>

<main class="success-main">

  <section class="success-card">

    <button class="success-close">
      ×
    </button>

    <div class="success-icon">
      ✓
    </div>

    <h1>Booking Berhasil!</h1>

    <p class="success-subtitle">
      Booking studio kamu berhasil dikirim dan sedang menunggu konfirmasi admin.
    </p>

    <div class="success-detail-box">

      <div class="success-item">
        <span>Kode Booking</span>
        <strong><?php echo $data['kode_booking']; ?></strong>
      </div>

      <div class="success-item">
        <span>Nama Customer</span>
        <strong><?php echo $data['nama_customer']; ?></strong>
      </div>

      <div class="success-item">
        <span>Layanan</span>
        <strong><?php echo $data['nama_layanan']; ?></strong>
      </div>

      <div class="success-item">
        <span>Tanggal Booking</span>
        <strong>
          <?php echo date('d F Y', strtotime($data['tanggal_booking'])); ?>
        </strong>
      </div>

      <div class="success-item">
        <span>Jam Booking</span>
        <strong><?php echo $data['jam_booking']; ?></strong>
      </div>

      <div class="success-item">
        <span>Pembayaran</span>
        <strong><?php echo $data['metode_pembayaran']; ?></strong>
      </div>

      <div class="success-item">
        <span>Status</span>
        <strong class="status-waiting">
          Menunggu Konfirmasi
        </strong>
      </div>

    </div>

    <p class="success-note">
      Kamu dapat melihat update booking melalui halaman riwayat booking.
    </p>

    <a href="status.php" class="success-button">
      Lihat Riwayat Booking
    </a>

  </section>

</main>

</body>
</html>
