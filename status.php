<?php
session_start();
include "koneksi.php";

$sudah_login = isset($_SESSION['id_user']);
$ada_booking = false;

if ($sudah_login) {

  $id_user = $_SESSION['id_user'];

  $query_booking = mysqli_query($conn, "
    SELECT booking.*, layanan.nama_layanan
    FROM booking
    JOIN layanan ON booking.id_layanan = layanan.id_layanan
    WHERE booking.id_user = '$id_user'
    ORDER BY booking.created_at DESC
  ");

  if (mysqli_num_rows($query_booking) > 0) {
    $ada_booking = true;
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Booking - ClickSpace</title>
  <link rel="stylesheet" href="style.css">
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

<?php if (!$sudah_login) { ?>

  <section class="riwayat-gate-hero">

    <div class="riwayat-gate-content">

      <h1>Riwayat Booking</h1>

      <p>
        Kamu perlu login terlebih dahulu untuk melihat riwayat booking milikmu.
        Setelah masuk, kamu bisa melihat daftar booking, status konfirmasi,
        dan detail pesanan studio yang sudah kamu lakukan.
      </p>

      <div class="riwayat-gate-actions">

        <a href="login.php" class="riwayat-gate-primary">
          Login
        </a>

        <a href="register.php" class="riwayat-gate-secondary">
          Sign Up
        </a>

      </div>

    </div>

  </section>

<?php } elseif (!$ada_booking) { ?>

  <section class="section">

    <div class="empty-box">

      <h3>Belum Ada Booking</h3>

      <p>
        Riwayat booking kamu masih kosong.
        Silakan pilih layanan lalu lakukan booking studio.
      </p>

      <a href="booking-form.php" class="button">
        Booking Sekarang
      </a>

      <a href="layanan-login.php" class="button second">
        Lihat Layanan
      </a>

    </div>

  </section>

<?php } else { ?>

  <section class="history-page-header">

    <div>
      <p>CLICKSPACE BOOKING</p>

      <h1>Riwayat Booking</h1>

      <span>
        Lihat daftar booking studio yang sudah kamu lakukan.
      </span>
    </div>

  </section>

  <section class="history-list-section">

    <?php while ($booking = mysqli_fetch_assoc($query_booking)) { ?>

      <?php

      $gambar = "images/studio.jpg";

      if ($booking['nama_layanan'] == "Self Studio") {
        $gambar = "images/self studio.jpg";
      }

      elseif ($booking['nama_layanan'] == "Foto Keluarga") {
        $gambar = "images/foto keluarga.jpg";
      }

      elseif ($booking['nama_layanan'] == "Graduation") {
        $gambar = "images/graduation.jpg";
      }

      elseif ($booking['nama_layanan'] == "Photobooth") {
        $gambar = "images/fotobooth.jpg";
      }

      elseif ($booking['nama_layanan'] == "Prewedding") {
        $gambar = "images/prewed.jpg";
      }

      elseif ($booking['nama_layanan'] == "Sewa Studio") {
        $gambar = "images/studio.jpg";
      }

      ?>

      <div class="history-booking-card">

        <div class="history-booking-image">

          <img
            src="<?php echo $gambar; ?>"
            alt="<?php echo $booking['nama_layanan']; ?>"
          >

        </div>

        <div class="history-booking-content">

          <div class="history-booking-top">

            <span>
              Kode:
              <?php echo $booking['kode_booking']; ?>
            </span>

            <span>
              <?php echo date('d M Y', strtotime($booking['created_at'])); ?>
            </span>

          </div>

          <h2>
            <?php echo $booking['nama_layanan']; ?>
          </h2>

          <p class="history-booking-desc">

            Booking atas nama
            <b><?php echo $booking['nama_customer']; ?></b>

            untuk tanggal

            <b>
              <?php echo date('d F Y', strtotime($booking['tanggal_booking'])); ?>
            </b>

            pukul

            <b><?php echo $booking['jam_booking']; ?></b>

          </p>

          <div class="history-booking-tags">

            <span>
              <?php echo $booking['metode_pembayaran']; ?>
            </span>

            <span>
              Rp<?php echo number_format($booking['total_harga'], 0, ',', '.'); ?>
            </span>

            <span>
              <?php echo $booking['no_whatsapp']; ?>
            </span>

          </div>

          <div class="history-booking-bottom">

            <div class="history-status">
              <?php echo $booking['status_booking']; ?>
            </div>

            <a href="success.php?kode=<?php echo $booking['kode_booking']; ?>">
              Lihat Detail
            </a>

          </div>

        </div>

      </div>

    <?php } ?>

  </section>

<?php } ?>

</main>

<footer>
  <p>© 2026 ClickSpace</p>
</footer>

</body>
</html>
