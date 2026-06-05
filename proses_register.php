<?php
session_start();
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: register.php");
  exit;
}

$nama_lengkap = isset($_POST['nama_lengkap']) ? mysqli_real_escape_string($conn, trim($_POST['nama_lengkap'])) : '';
$email = isset($_POST['email']) ? mysqli_real_escape_string($conn, trim($_POST['email'])) : '';
$no_whatsapp = isset($_POST['no_whatsapp']) ? mysqli_real_escape_string($conn, trim($_POST['no_whatsapp'])) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$konfirmasi_password = isset($_POST['konfirmasi_password']) ? $_POST['konfirmasi_password'] : '';

if (
  $nama_lengkap == '' ||
  $email == '' ||
  $no_whatsapp == '' ||
  $password == '' ||
  $konfirmasi_password == ''
) {
  header("Location: register.php?error=empty");
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header("Location: register.php?error=email");
  exit;
}

if (!preg_match('/^[0-9]{10,15}$/', $no_whatsapp)) {
  header("Location: register.php?error=whatsapp");
  exit;
}

if (strlen($password) < 6) {
  header("Location: register.php?error=short");
  exit;
}

if ($password !== $konfirmasi_password) {
  header("Location: register.php?error=password");
  exit;
}

$cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if (mysqli_num_rows($cek) > 0) {
  header("Location: register.php?error=exists");
  exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$query = mysqli_query($conn, "INSERT INTO users
(nama_lengkap, email, no_whatsapp, password, role)
VALUES
('$nama_lengkap', '$email', '$no_whatsapp', '$password_hash', 'customer')");

if ($query) {
  header("Location: login.php?success=registered");
  exit;
} else {
  header("Location: register.php?error=failed");
  exit;
}
?>
