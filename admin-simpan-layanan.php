<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: admin-layanan.php");
  exit;
}

$nama_layanan = isset($_POST['nama_layanan']) ? mysqli_real_escape_string($conn, trim($_POST['nama_layanan'])) : '';
$kategori = isset($_POST['kategori']) ? mysqli_real_escape_string($conn, trim($_POST['kategori'])) : '';
$durasi = isset($_POST['durasi']) ? mysqli_real_escape_string($conn, trim($_POST['durasi'])) : '';
$kapasitas = isset($_POST['kapasitas']) ? mysqli_real_escape_string($conn, trim($_POST['kapasitas'])) : '';
$fasilitas = isset($_POST['fasilitas']) ? mysqli_real_escape_string($conn, trim($_POST['fasilitas'])) : '';
$harga = isset($_POST['harga']) ? mysqli_real_escape_string($conn, trim($_POST['harga'])) : '';
$deskripsi = isset($_POST['deskripsi']) ? mysqli_real_escape_string($conn, trim($_POST['deskripsi'])) : '';

if (
  $nama_layanan == '' ||
  $kategori == '' ||
  $durasi == '' ||
  $kapasitas == '' ||
  $fasilitas == '' ||
  $harga == '' ||
  $deskripsi == ''
) {
  header("Location: admin-tambah-layanan.php?error=empty");
  exit;
}

if (!is_numeric($harga)) {
  header("Location: admin-tambah-layanan.php?error=harga");
  exit;
}

if ($harga <= 0) {
  header("Location: admin-tambah-layanan.php?error=harga");
  exit;
}

if (!isset($_FILES['gambar']) || $_FILES['gambar']['name'] == '') {
  header("Location: admin-tambah-layanan.php?error=gambar");
  exit;
}

$folder_upload = "images/";

if (!is_dir($folder_upload)) {
  mkdir($folder_upload, 0777, true);
}

$nama_file = $_FILES['gambar']['name'];
$tmp_file = $_FILES['gambar']['tmp_name'];
$ukuran_file = $_FILES['gambar']['size'];
$error_file = $_FILES['gambar']['error'];
$ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

$ekstensi_diizinkan = array("jpg", "jpeg", "png");

if ($error_file !== 0) {
  header("Location: admin-tambah-layanan.php?error=gambar");
  exit;
}

if (!in_array($ekstensi, $ekstensi_diizinkan)) {
  header("Location: admin-tambah-layanan.php?error=file");
  exit;
}

if ($ukuran_file > 2 * 1024 * 1024) {
  header("Location: admin-tambah-layanan.php?error=size");
  exit;
}

$nama_file_baru = "layanan_" . time() . "_" . rand(100, 999) . "." . $ekstensi;
$lokasi_file = $folder_upload . $nama_file_baru;

$upload = move_uploaded_file($tmp_file, $lokasi_file);

if (!$upload) {
  header("Location: admin-tambah-layanan.php?error=upload");
  exit;
}

$query = mysqli_query($conn, "
  INSERT INTO layanan
  (nama_layanan, kategori, durasi, kapasitas, fasilitas, harga, deskripsi, gambar)
  VALUES
  ('$nama_layanan', '$kategori', '$durasi', '$kapasitas', '$fasilitas', '$harga', '$deskripsi', '$lokasi_file')
");

if ($query) {
  header("Location: admin-layanan.php?created=success");
  exit;
} else {
  header("Location: admin-tambah-layanan.php?error=failed");
  exit;
}
?>
