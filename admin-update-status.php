<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: admin-booking.php");
  exit;
}

function clearStoredResultsUpdate($conn) {
  while (mysqli_more_results($conn)) {
    mysqli_next_result($conn);
    if ($result = mysqli_store_result($conn)) {
      mysqli_free_result($result);
    }
  }
}

$id_booking = mysqli_real_escape_string($conn, $_POST['id_booking']);
$status_booking = mysqli_real_escape_string($conn, $_POST['status_booking']);

$status_diizinkan = array('Menunggu Konfirmasi', 'Dikonfirmasi', 'Selesai', 'Dibatalkan');

if (!in_array($status_booking, $status_diizinkan)) {
  header("Location: admin-booking.php");
  exit;
}

$stmt = mysqli_prepare($conn, "CALL sp_update_booking_status(?, ?)");
mysqli_stmt_bind_param($stmt, 'is', $id_booking, $status_booking);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
clearStoredResultsUpdate($conn);

if ($ok) {
  header("Location: admin-booking.php?updated=success");
} else {
  header("Location: admin-booking.php?updated=failed");
}
exit;
?>
