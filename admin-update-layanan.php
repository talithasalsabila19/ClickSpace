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

$id_layanan = isset($_POST['id_layanan']) ? mysqli_real_escape_string($conn, trim($_POST['id_layanan'])) : '';
$nama_layanan = isset($_POST['nama_layanan']) ? mysqli_real_escape_string($conn, trim($_POST['nama_layanan'])) : '';
$kategori = isset($_POST['kategori']) ? mysqli_real_escape_string($conn, trim($_POST['kategori'])) : '';
$durasi = isset($_POST['durasi']) ? mysqli_real_escape_string($conn, trim($_POST['durasi'])) : '';
$kapasitas = isset($_POST['kapasitas']) ? mysqli_real_escape_string($conn, trim($_POST['kapasitas'])) : '';
$fasilitas = isset($_POST['fasilitas']) ? mysqli_real_escape_string($conn, trim($_POST['fasilitas'])) : '';
$harga = isset($_POST['harga']) ? mysqli_real_escape_string($conn, trim($_POST['harga'])) : '';
$deskripsi = isset($_POST['deskripsi']) ? mysqli_real_escape_string($conn, trim($_POST['deskripsi'])) : '';
$gambar_lama = isset($_POST['gambar_lama']) ? mysqli_real_escape_string($conn, trim($_POST['gambar_lama'])) : '';

if ($id_layanan == '') {
  header("Location: admin-layanan.php");
  exit;
}

if (
  $nama_layanan == '' ||
  $kategori == '' ||
  $durasi == '' ||
  $kapasitas == '' ||
  $fasilitas == '' ||
  $harga == '' ||
  $deskripsi == ''
) {
  header("Location: admin-edit-layanan.php?id=" . $id_layanan . "&error=empty");
  exit;
}

if (!is_numeric($harga) || $harga <= 0) {
  header("Location: admin-edit-layanan.php?id=" . $id_layanan . "&error=harga");
  exit;
}

$cek_layanan = mysqli_query($conn, "SELECT * FROM layanan WHERE id_layanan='$id_layanan'");

if (mysqli_num_rows($cek_layanan) == 0) {
  header("Location: admin-layanan.php");
  exit;
}

$gambar_baru = $gambar_lama;

if (isset($_FILES['gambar']) && $_FILES['gambar']['name'] != '') {

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
    header("Location: admin-edit-layanan.php?id=" . $id_layanan . "&error=gambar");
    exit;
  }

  if (!in_array($ekstensi, $ekstensi_diizinkan)) {
    header("Location: admin-edit-layanan.php?id=" . $id_layanan . "&error=file");
    exit;
  }

  if ($ukuran_file > 2 * 1024 * 1024) {
    header("Location: admin-edit-layanan.php?id=" . $id_layanan . "&error=size");
    exit;
  }

  $nama_file_baru = "layanan_" . time() . "_" . rand(100, 999) . "." . $ekstensi;
  $lokasi_file = $folder_upload . $nama_file_baru;

  $upload = move_uploaded_file($tmp_file, $lokasi_file);

  if (!$upload) {
    header("Location: admin-edit-layanan.php?id=" . $id_layanan . "&error=upload");
    exit;
  }

  $gambar_baru = $lokasi_file;
}

$query = mysqli_query($conn, "
  UPDATE layanan SET
    nama_layanan='$nama_layanan',
    kategori='$kategori',
    durasi='$durasi',
    kapasitas='$kapasitas',
    fasilitas='$fasilitas',
    harga='$harga',
    deskripsi='$deskripsi',
    gambar='$gambar_baru'
  WHERE id_layanan='$id_layanan'
");

if ($query) {
  header("Location: admin-layanan.php?updated=success");
  exit;
} else {
  header("Location: admin-edit-layanan.php?id=" . $id_layanan . "&error=failed");
  exit;
}
?>
