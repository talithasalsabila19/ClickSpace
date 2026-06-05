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

function rupiah_admin_db($angka) {
  return "Rp" . number_format((int)$angka, 0, ',', '.');
}

function clearStoredResults($conn) {
  while (mysqli_more_results($conn)) {
    mysqli_next_result($conn);
    if ($result = mysqli_store_result($conn)) {
      mysqli_free_result($result);
    }
  }
}

function getRows($conn, $sql) {
  $result = mysqli_query($conn, $sql);
  if (!$result) {
    return ['error' => mysqli_error($conn), 'rows' => []];
  }
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  mysqli_free_result($result);
  return ['error' => null, 'rows' => $rows];
}

function getProcedureRows($conn, $sql) {
  $result = mysqli_query($conn, $sql);
  if (!$result) {
    return ['error' => mysqli_error($conn), 'rows' => []];
  }
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  mysqli_free_result($result);
  clearStoredResults($conn);
  return ['error' => null, 'rows' => $rows];
}

function renderTable($data, $emptyText = 'Data belum tersedia.') {
  if (!empty($data['error'])) {
    echo '<div class="admin-alert danger">Query gagal: ' . h($data['error']) . '</div>';
    return;
  }
  $rows = $data['rows'];
  if (empty($rows)) {
    echo '<p class="db-empty-text">' . h($emptyText) . '</p>';
    return;
  }

  echo '<div class="admin-table-wrap"><table class="booking-table"><thead><tr>';
  foreach (array_keys($rows[0]) as $column) {
    echo '<th>' . h($column) . '</th>';
  }
  echo '</tr></thead><tbody>';
  foreach ($rows as $row) {
    echo '<tr>';
    foreach ($row as $value) {
      echo '<td>' . h($value) . '</td>';
    }
    echo '</tr>';
  }
  echo '</tbody></table></div>';
}

$message = null;
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $action = $_POST['action'];

  if ($action === 'transaction_success') {
    mysqli_begin_transaction($conn);
    $ok = mysqli_query($conn, "INSERT INTO transaksi_log (nama_transaksi, status_transaksi, keterangan) VALUES ('Demo Transaction COMMIT', 'BERHASIL', 'BEGIN dijalankan, query valid, lalu COMMIT.')");
    if ($ok) {
      mysqli_commit($conn);
      $message = 'Transaksi berhasil: BEGIN diproses, data transaksi_log masuk, lalu COMMIT.';
      $messageType = 'success';
    } else {
      mysqli_rollback($conn);
      $message = 'Transaksi gagal dan sudah ROLLBACK: ' . mysqli_error($conn);
      $messageType = 'danger';
    }
  }

  if ($action === 'transaction_fail') {
    mysqli_begin_transaction($conn);
    $ok1 = mysqli_query($conn, "INSERT INTO transaksi_log (nama_transaksi, status_transaksi, keterangan) VALUES ('Demo Transaction ROLLBACK', 'GAGAL', 'BEGIN dijalankan, query kedua dibuat gagal, lalu ROLLBACK.')");
    $ok2 = @mysqli_query($conn, "INSERT INTO tabel_tidak_ada (id) VALUES (1)");
    if ($ok1 && $ok2) {
      mysqli_commit($conn);
      $message = 'Transaksi justru berhasil, tidak ada error.';
      $messageType = 'success';
    } else {
      mysqli_rollback($conn);
      mysqli_query($conn, "INSERT INTO transaksi_log (nama_transaksi, status_transaksi, keterangan) VALUES ('Demo Transaction ROLLBACK', 'GAGAL', 'Query kedua gagal sehingga ROLLBACK dijalankan. Data dalam transaksi tidak disimpan.')");
      $message = 'Transaksi gagal: query kedua error, maka ROLLBACK dijalankan. Ini contoh transaksi gagal.';
      $messageType = 'danger';
    }
  }

  if ($action === 'procedure_insert') {
    $customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_user, nama_lengkap, no_whatsapp FROM users WHERE role='customer' ORDER BY id_user ASC LIMIT 1"));
    $layanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_layanan, harga FROM layanan ORDER BY id_layanan ASC LIMIT 1"));
    if ($customer && $layanan) {
      $kode = 'DEMO' . date('YmdHis');
      $tanggal = date('Y-m-d', strtotime('+1 day'));
      $status = 'Menunggu Konfirmasi';
      $customerId = (int)$customer['id_user'];
      $layananId = (int)$layanan['id_layanan'];
      $namaCustomer = $customer['nama_lengkap'];
      $noWhatsapp = $customer['no_whatsapp'];
      $totalHarga = (int)$layanan['harga'];
      mysqli_query($conn, "SET @new_booking_id = 0");
      $stmt = mysqli_prepare($conn, "CALL sp_insert_booking(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @new_booking_id)");
      $jam = '09.00 - 10.00';
      $metode = 'QRIS';
      $bukti = 'uploads/demo-procedure.txt';
      mysqli_stmt_bind_param($stmt, 'siissssssis', $kode, $customerId, $layananId, $namaCustomer, $noWhatsapp, $tanggal, $jam, $metode, $bukti, $totalHarga, $status);
      $ok = mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      clearStoredResults($conn);
      $new = mysqli_fetch_assoc(mysqli_query($conn, "SELECT @new_booking_id AS id_booking"));
      if ($ok) {
        $message = 'Procedure INSERT berhasil membuat booking demo dengan kode ' . $kode . ' (ID ' . $new['id_booking'] . ').';
        $messageType = 'success';
      } else {
        $message = 'Procedure INSERT gagal: ' . mysqli_error($conn);
        $messageType = 'danger';
      }
    } else {
      $message = 'Procedure INSERT gagal karena data customer atau layanan belum tersedia.';
      $messageType = 'danger';
    }
  }

  if ($action === 'procedure_update') {
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_booking, kode_booking FROM booking ORDER BY id_booking DESC LIMIT 1"));
    if ($row) {
      $id = (int)$row['id_booking'];
      $ok = mysqli_query($conn, "CALL sp_update_booking_status($id, 'Dikonfirmasi')");
      clearStoredResults($conn);
      $message = $ok ? 'Procedure UPDATE berhasil mengubah status booking ' . $row['kode_booking'] . ' menjadi Dikonfirmasi.' : 'Procedure UPDATE gagal: ' . mysqli_error($conn);
      $messageType = $ok ? 'success' : 'danger';
    } else {
      $message = 'Procedure UPDATE gagal karena belum ada data booking.';
      $messageType = 'danger';
    }
  }

  if ($action === 'procedure_delete') {
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_booking, kode_booking FROM booking WHERE kode_booking LIKE 'DEMO%' ORDER BY id_booking DESC LIMIT 1"));
    if ($row) {
      $id = (int)$row['id_booking'];
      $ok = mysqli_query($conn, "CALL sp_delete_booking($id)");
      clearStoredResults($conn);
      $message = $ok ? 'Procedure DELETE berhasil menghapus booking demo ' . $row['kode_booking'] . '.' : 'Procedure DELETE gagal: ' . mysqli_error($conn);
      $messageType = $ok ? 'success' : 'danger';
    } else {
      $message = 'Tidak ada booking demo berkode DEMO% untuk dihapus. Jalankan INSERT procedure dulu.';
      $messageType = 'danger';
    }
  }

  if ($action === 'deadlock_simulation') {
    mysqli_query($conn, "TRUNCATE TABLE deadlock_logs");
    mysqli_query($conn, "UPDATE deadlock_resource SET locked_by=NULL, status_resource='FREE'");
    $steps = [
      ['Proses A', 'booking:sample', 'LOCKED', 'Proses A mengunci data booking terlebih dahulu.'],
      ['Proses B', 'layanan:sample', 'LOCKED', 'Proses B mengunci data layanan terlebih dahulu.'],
      ['Proses A', 'layanan:sample', 'WAITING', 'Proses A ingin mengakses layanan, tetapi resource sedang dikunci Proses B. Kondisi waiting.'],
      ['Proses B', 'booking:sample', 'WAITING', 'Proses B ingin mengakses booking, tetapi resource sedang dikunci Proses A. Kondisi waiting.'],
      ['SYSTEM', '-', 'INFO', 'Konflik terdeteksi karena kedua proses saling menunggu resource yang sedang dikunci.'],
      ['SYSTEM', 'Proses B', 'ROLLBACK', 'Sistem memilih Proses B untuk rollback agar deadlock selesai.'],
      ['Proses A', 'booking:sample + layanan:sample', 'COMMIT', 'Proses A menyelesaikan pekerjaan dan melepas lock.'],
      ['Proses B', 'booking:sample + layanan:sample', 'RETRY', 'Proses B dijalankan ulang setelah resource bebas.'],
      ['Proses B', 'booking:sample + layanan:sample', 'COMMIT', 'Retry berhasil dan transaksi selesai.']
    ];
    foreach ($steps as $step) {
      $p = mysqli_real_escape_string($conn, $step[0]);
      $r = mysqli_real_escape_string($conn, $step[1]);
      $s = mysqli_real_escape_string($conn, $step[2]);
      $k = mysqli_real_escape_string($conn, $step[3]);
      mysqli_query($conn, "INSERT INTO deadlock_logs (nama_proses, resource_name, status_step, keterangan) VALUES ('$p', '$r', '$s', '$k')");
    }
    mysqli_query($conn, "UPDATE deadlock_resource SET locked_by=NULL, status_resource='FREE'");
    $message = 'Simulasi deadlock selesai: sistem menampilkan waiting, rollback, retry, dan commit.';
    $messageType = 'success';
  }

  if ($action === 'refresh_fragmentasi') {
    $ok = mysqli_query($conn, "CALL sp_refresh_booking_fragmentasi()");
    clearStoredResults($conn);
    $message = $ok ? 'Fragmentasi berhasil diperbarui: booking aktif dan booking selesai/dibatalkan dipisahkan.' : 'Fragmentasi gagal: ' . mysqli_error($conn);
    $messageType = $ok ? 'success' : 'danger';
  }
}

@mysqli_query($conn, "CALL sp_refresh_booking_fragmentasi()");
clearStoredResults($conn);

$viewData = getRows($conn, "SELECT kode_booking, nama_customer, nama_layanan, email, tanggal_format, jam_booking, total_harga_format, status_booking FROM v_booking_detail ORDER BY created_at DESC LIMIT 8");
$joinData = getRows($conn, "SELECT u.nama_lengkap, u.email, COUNT(b.id_booking) AS total_booking, fn_format_rupiah(COALESCE(SUM(b.total_harga), 0)) AS total_nilai FROM users u LEFT JOIN booking b ON u.id_user = b.id_user WHERE u.role = 'customer' GROUP BY u.id_user, u.nama_lengkap, u.email ORDER BY total_booking DESC");
$unionData = getRows($conn, "(SELECT 'LAYANAN' AS jenis_data, nama_layanan AS nama_data, kategori AS keterangan FROM layanan) UNION ALL (SELECT 'CUSTOMER' AS jenis_data, nama_lengkap AS nama_data, email AS keterangan FROM users WHERE role = 'customer') ORDER BY jenis_data, nama_data LIMIT 12");
$functionData = getRows($conn, "SELECT nama_layanan, UPPER(kategori) AS built_in_upper_kategori, CHAR_LENGTH(deskripsi) AS built_in_panjang_deskripsi, fn_format_rupiah(harga) AS custom_fn_harga FROM layanan ORDER BY id_layanan ASC");
$procedureData = getProcedureRows($conn, "CALL sp_select_booking_detail()");
if (count($procedureData['rows']) > 8) {
  $procedureData['rows'] = array_slice($procedureData['rows'], 0, 8);
}
$transactionData = getRows($conn, "SELECT nama_transaksi, status_transaksi, keterangan, created_at FROM transaksi_log ORDER BY created_at DESC LIMIT 8");
$triggerData = getRows($conn, "SELECT aksi, kode_booking, status_lama, status_baru, keterangan, created_at FROM booking_audit ORDER BY created_at DESC LIMIT 8");
$deadlockData = getRows($conn, "SELECT nama_proses, resource_name, status_step, keterangan, created_at FROM deadlock_logs ORDER BY id_log ASC");
$fragmentAktif = getRows($conn, "SELECT kode_booking, nama_customer, status_booking, tanggal_booking, jam_booking FROM booking_fragment_aktif ORDER BY created_at DESC LIMIT 8");
$fragmentSelesai = getRows($conn, "SELECT kode_booking, nama_customer, status_booking, tanggal_booking, jam_booking FROM booking_fragment_selesai ORDER BY created_at DESC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Database Lab - Admin ClickSpace</title>
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
      <p>UAP DATABASE IMPLEMENTATION</p>
      <h1>Database Lab ClickSpace</h1>
      <span>Halaman ini menampilkan pemanggilan view, join, union, transaction, function, procedure, trigger, fragmentasi, backup, dan simulasi deadlock.</span>
    </div>
    <a href="backup_list.php" class="admin-hero-button">Buka Backup</a>
  </section>

  <?php if ($message) { ?>
    <div class="admin-alert <?php echo h($messageType); ?>">
      <?php echo h($message); ?>
    </div>
  <?php } ?>

  <section class="db-command-grid">
    <form method="POST"><input type="hidden" name="action" value="transaction_success"><button>Test Transaction COMMIT</button></form>
    <form method="POST"><input type="hidden" name="action" value="transaction_fail"><button>Test Transaction ROLLBACK</button></form>
    <form method="POST"><input type="hidden" name="action" value="procedure_insert"><button>CALL INSERT Procedure</button></form>
    <form method="POST"><input type="hidden" name="action" value="procedure_update"><button>CALL UPDATE Procedure</button></form>
    <form method="POST"><input type="hidden" name="action" value="procedure_delete"><button>CALL DELETE Procedure</button></form>
    <form method="POST"><input type="hidden" name="action" value="deadlock_simulation"><button>Simulasi Deadlock</button></form>
    <form method="POST"><input type="hidden" name="action" value="refresh_fragmentasi"><button>Refresh Fragmentasi</button></form>
  </section>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title"><div><p>1. DATABASE VIEW</p><h2>Query dari View v_booking_detail</h2></div></div>
    <pre class="db-code">SELECT kode_booking, nama_customer, nama_layanan, email, total_harga_format FROM v_booking_detail;</pre>
    <?php renderTable($viewData); ?>
  </section>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title"><div><p>2. SQL JOIN</p><h2>LEFT JOIN Users + Booking</h2></div></div>
    <pre class="db-code">SELECT users LEFT JOIN booking untuk menghitung total booking tiap customer.</pre>
    <?php renderTable($joinData); ?>
  </section>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title"><div><p>3. SET OPERATIONS</p><h2>UNION ALL Layanan dan Customer</h2></div></div>
    <pre class="db-code">SELECT nama_layanan FROM layanan UNION ALL SELECT nama_lengkap FROM users;</pre>
    <?php renderTable($unionData); ?>
  </section>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title"><div><p>4. FUNCTION</p><h2>Built-in Function + Custom Function</h2></div></div>
    <pre class="db-code">Built-in: UPPER(), CHAR_LENGTH() | Custom: fn_format_rupiah()</pre>
    <?php renderTable($functionData); ?>
  </section>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title"><div><p>5. STORED PROCEDURE</p><h2>CALL sp_select_booking_detail()</h2></div></div>
    <pre class="db-code">INSERT, SELECT, UPDATE, DELETE procedure dapat dites lewat tombol di atas.</pre>
    <?php renderTable($procedureData); ?>
  </section>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title"><div><p>6. TRANSACTION</p><h2>Log BEGIN, COMMIT, dan ROLLBACK</h2></div></div>
    <pre class="db-code">BEGIN dijalankan lewat mysqli_begin_transaction(), lalu sistem memilih COMMIT atau ROLLBACK.</pre>
    <?php renderTable($transactionData, 'Belum ada transaksi yang dites.'); ?>
  </section>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title"><div><p>7. TRIGGER</p><h2>Audit Otomatis Booking</h2></div></div>
    <pre class="db-code">trg_booking_after_insert, trg_booking_after_update, trg_booking_before_delete</pre>
    <?php renderTable($triggerData, 'Belum ada log trigger. Jalankan insert/update/delete booking.'); ?>
  </section>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title"><div><p>8. DEADLOCK SIMULATION</p><h2>Konflik Waiting, Rollback, Retry</h2></div></div>
    <pre class="db-code">Simulasi logika: Proses A dan B saling menunggu resource, lalu sistem rollback salah satu proses dan retry.</pre>
    <?php renderTable($deadlockData, 'Klik tombol Simulasi Deadlock untuk menampilkan konflik.'); ?>
  </section>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title"><div><p>9. FRAGMENTASI</p><h2>Fragmentasi Horizontal Tabel Booking</h2></div></div>
    <div class="db-fragment-grid">
      <div>
        <h3>booking_fragment_aktif</h3>
        <?php renderTable($fragmentAktif, 'Tidak ada booking aktif.'); ?>
      </div>
      <div>
        <h3>booking_fragment_selesai</h3>
        <?php renderTable($fragmentSelesai, 'Tidak ada booking selesai/dibatalkan.'); ?>
      </div>
    </div>
  </section>

  <section class="admin-panel db-section-card">
    <div class="admin-panel-title"><div><p>10. BACKUP + TASK SCHEDULER</p><h2>Backup Database</h2></div></div>
    <p class="db-empty-text">Backup manual ada di <strong>backup.php</strong>. Backup otomatis dapat dijalankan Windows Task Scheduler melalui <strong>tasks/backup_clickspace.bat</strong> yang memanggil <strong>scheduled_backup.php</strong>.</p>
    <a href="backup.php" class="admin-hero-button">Buat Backup Sekarang</a>
  </section>
</main>

<footer><p>© 2026 ClickSpace</p></footer>
</body>
</html>
