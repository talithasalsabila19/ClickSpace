<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

function h($value) {
  return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$logs = mysqli_query($conn, "SELECT * FROM backup_logs ORDER BY created_at DESC LIMIT 20");
$backupFiles = glob(__DIR__ . '/storage/backups/*.sql');
rsort($backupFiles);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Backup Database - Admin ClickSpace</title>
  <link rel="stylesheet" href="style.css?v=120">
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
      <p>DATABASE BACKUP</p>
      <h1>Backup Database ClickSpace</h1>
      <span>Admin dapat membuat backup manual dan melihat hasil backup dari task scheduler.</span>
    </div>
    <a href="backup.php" class="admin-hero-button">Buat Backup Manual</a>
  </section>

  <?php if (isset($_GET['status'])) { ?>
    <div class="admin-alert <?php echo $_GET['status'] === 'success' ? 'success' : 'danger'; ?>">
      <?php echo h($_GET['message'] ?? 'Backup diproses.'); ?>
      <?php if (!empty($_GET['file'])) { echo '<br>File: <strong>' . h($_GET['file']) . '</strong>'; } ?>
    </div>
  <?php } ?>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title">
      <div>
        <p>BACKUP LOG</p>
        <h2>Riwayat Backup</h2>
      </div>
    </div>
    <div class="admin-table-wrap">
      <table class="booking-table">
        <thead>
          <tr>
            <th>Waktu</th>
            <th>File</th>
            <th>Sumber</th>
            <th>Status</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($logs && mysqli_num_rows($logs) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($logs)) { ?>
              <tr>
                <td><?php echo h($row['created_at']); ?></td>
                <td><?php echo h($row['nama_file']); ?></td>
                <td><?php echo h($row['sumber_backup']); ?></td>
                <td><?php echo h($row['status_backup']); ?></td>
                <td><?php echo h($row['keterangan']); ?></td>
              </tr>
            <?php } ?>
          <?php } else { ?>
            <tr><td colspan="5">Belum ada riwayat backup.</td></tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </section>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title">
      <div>
        <p>FILE BACKUP</p>
        <h2>File di Folder storage/backups</h2>
      </div>
    </div>
    <div class="backup-file-grid">
      <?php if (!empty($backupFiles)) { ?>
        <?php foreach ($backupFiles as $file) { $relative = 'storage/backups/' . basename($file); ?>
          <a class="backup-file-card" href="<?php echo h($relative); ?>" download>
            <strong><?php echo h(basename($file)); ?></strong>
            <span><?php echo h(date('d-m-Y H:i:s', filemtime($file))); ?></span>
          </a>
        <?php } ?>
      <?php } else { ?>
        <p>Belum ada file backup tersimpan.</p>
      <?php } ?>
    </div>
  </section>
</main>

<footer><p>© 2026 ClickSpace</p></footer>
</body>
</html>
