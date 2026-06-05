<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

if (!isset($_GET['id'])) {
  header("Location: admin-layanan.php");
  exit;
}

$id_layanan = mysqli_real_escape_string($conn, $_GET['id']);

$cek_booking = mysqli_query($conn, "SELECT * FROM booking WHERE id_layanan='$id_layanan'");

if (mysqli_num_rows($cek_booking) > 0) {
  header("Location: admin-layanan.php?deleted=failed");
  exit;
}

$query = mysqli_query($conn, "DELETE FROM layanan WHERE id_layanan='$id_layanan'");

if ($query) {
  header("Location: admin-layanan.php?deleted=success");
  exit;
} else {
  header("Location: admin-layanan.php");
  exit;
}
?>
