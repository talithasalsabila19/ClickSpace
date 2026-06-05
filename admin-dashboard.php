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

$total_booking = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM booking"));
$total_customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='customer'"));
$total_layanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM layanan"));
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_harga), 0) AS total FROM booking WHERE status_booking != 'Dibatalkan'"));

$menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM booking WHERE status_booking='Menunggu Konfirmasi'"));
$dikonfirmasi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM booking WHERE status_booking='Dikonfirmasi'"));
$selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM booking WHERE status_booking='Selesai'"));
$dibatalkan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM booking WHERE status_booking='Dibatalkan'"));

$booking_terbaru = mysqli_query($conn, "
  SELECT booking.*, layanan.nama_layanan, users.email
  FROM booking
  JOIN layanan ON booking.id_layanan = layanan.id_layanan
  JOIN users ON booking.id_user = users.id_user
  ORDER BY booking.created_at DESC
  LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - ClickSpace</title>
  <link rel="stylesheet" href="style.css?v=10">
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

  <section class="admin-hero">
    <div>
      <p>ADMIN PANEL</p>
      <h1>Dashboard ClickSpace</h1>
      <span>Halo, <?php echo $_SESSION['nama_lengkap']; ?>. Kelola data booking studio dari halaman ini.</span>
    </div>

    <a href="admin-booking.php" class="admin-hero-button">Kelola Booking</a>
  </section>

  <section class="admin-summary-grid">
    <div class="admin-summary-card">
      <span>Total Booking</span>
      <strong><?php echo $total_booking['total']; ?></strong>
      <p>Semua booking yang masuk</p>
    </div>

    <div class="admin-summary-card">
      <span>Total Customer</span>
      <strong><?php echo $total_customer['total']; ?></strong>
      <p>Akun customer terdaftar</p>
    </div>

    <div class="admin-summary-card">
      <span>Total Layanan</span>
      <strong><?php echo $total_layanan['total']; ?></strong>
      <p>Paket layanan tersedia</p>
    </div>

    <div class="admin-summary-card">
      <span>Pendapatan</span>
      <strong><?php echo rupiah($total_pendapatan['total']); ?></strong>
      <p>Selain booking dibatalkan</p>
    </div>
  </section>

  <section class="admin-status-grid">
    <div class="admin-status-card waiting">
      <span>Menunggu</span>
      <strong><?php echo $menunggu['total']; ?></strong>
    </div>

    <div class="admin-status-card confirmed">
      <span>Dikonfirmasi</span>
      <strong><?php echo $dikonfirmasi['total']; ?></strong>
    </div>

    <div class="admin-status-card done">
      <span>Selesai</span>
      <strong><?php echo $selesai['total']; ?></strong>
    </div>

    <div class="admin-status-card cancelled">
      <span>Dibatalkan</span>
      <strong><?php echo $dibatalkan['total']; ?></strong>
    </div>
  </section>

  <section class="admin-panel">
    <div class="admin-panel-title">
      <div>
        <p>DATA TERBARU</p>
        <h2>Booking Terbaru</h2>
      </div>
      <a href="admin-booking.php">Lihat Semua</a>
    </div>

    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Customer</th>
            <th>Layanan</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>

        <tbody>
          <?php if (mysqli_num_rows($booking_terbaru) > 0) { ?>
            <?php while ($booking = mysqli_fetch_assoc($booking_terbaru)) { ?>
              <tr>
                <td><?php echo $booking['kode_booking']; ?></td>
                <td>
                  <b><?php echo $booking['nama_customer']; ?></b><br>
                  <small><?php echo $booking['email']; ?></small>
                </td>
                <td><?php echo $booking['nama_layanan']; ?></td>
                <td><?php echo date('d M Y', strtotime($booking['tanggal_booking'])); ?></td>
                <td><?php echo rupiah($booking['total_harga']); ?></td>
                <td><span class="admin-badge"><?php echo $booking['status_booking']; ?></span></td>
              </tr>
            <?php } ?>
          <?php } else { ?>
            <tr>
              <td colspan="6" class="admin-empty">Belum ada data booking.</td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </section>

</main>

<footer>
  <p>© 2026 ClickSpace</p>
</footer>

</body>
</html>
