<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

if (!isset($_GET['id'])) {
  header("Location: admin-booking.php");
  exit;
}

function clearStoredResultsDelete($conn) {
  while (mysqli_more_results($conn)) {
    mysqli_next_result($conn);
    if ($result = mysqli_store_result($conn)) {
      mysqli_free_result($result);
    }
  }
}

$id_booking = mysqli_real_escape_string($conn, $_GET['id']);

$ambil_booking = mysqli_query($conn, "SELECT * FROM booking WHERE id_booking='$id_booking'");
$booking = mysqli_fetch_assoc($ambil_booking);

if (!$booking) {
  header("Location: admin-booking.php");
  exit;
}


if (!empty($booking['bukti_pembayaran']) && file_exists($booking['bukti_pembayaran'])) {
  unlink($booking['bukti_pembayaran']);
}


$stmt = mysqli_prepare($conn, "CALL sp_delete_booking(?)");
mysqli_stmt_bind_param($stmt, 'i', $id_booking);
$query = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
clearStoredResultsDelete($conn);

if ($query) {
  header("Location: admin-booking.php?deleted=success");
  exit;
} else {
  header("Location: admin-booking.php?deleted=failed");
  exit;
}
?>
