<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';
$where = "WHERE role='customer'";

if ($keyword != '') {
  $where .= " AND (
    nama_lengkap LIKE '%$keyword%'
    OR email LIKE '%$keyword%'
    OR no_whatsapp LIKE '%$keyword%'
  )";
}

$query_customer = mysqli_query($conn, "
  SELECT users.*, COUNT(booking.id_booking) AS total_booking
  FROM users
  LEFT JOIN booking ON users.id_user = booking.id_user
  $where
  GROUP BY users.id_user
  ORDER BY users.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Customer - Admin ClickSpace</title>
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

  <section class="admin-page-title">
    <div>
      <p>ADMIN PANEL</p>
      <h1>Data Customer</h1>
      <span>Halaman ini menampilkan daftar akun customer yang sudah terdaftar.</span>
    </div>
  </section>

  <section class="admin-filter-card">
    <form method="GET" action="admin-users.php">
      <input type="text" name="keyword" placeholder="Cari nama, email, atau WhatsApp" value="<?php echo htmlspecialchars($keyword); ?>">
      <button type="submit">Cari</button>
      <a href="admin-users.php">Reset</a>
    </form>
  </section>

  <section class="admin-panel">
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Nama Customer</th>
            <th>Email</th>
            <th>No WhatsApp</th>
            <th>Total Booking</th>
            <th>Tanggal Daftar</th>
          </tr>
        </thead>

        <tbody>
          <?php if (mysqli_num_rows($query_customer) > 0) { ?>
            <?php while ($customer = mysqli_fetch_assoc($query_customer)) { ?>
              <tr>
                <td><b><?php echo $customer['nama_lengkap']; ?></b></td>
                <td><?php echo $customer['email']; ?></td>
                <td><?php echo $customer['no_whatsapp']; ?></td>
                <td><span class="admin-badge"><?php echo $customer['total_booking']; ?> booking</span></td>
                <td><?php echo date('d M Y', strtotime($customer['created_at'])); ?></td>
              </tr>
            <?php } ?>
          <?php } else { ?>
            <tr>
              <td colspan="5" class="admin-empty">Data customer tidak ditemukan.</td>
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
