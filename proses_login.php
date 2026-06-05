<?php
session_start();
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: login.php");
  exit;
}

$email = isset($_POST['email']) ? mysqli_real_escape_string($conn, trim($_POST['email'])) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($email == '' || $password == '') {
  header("Location: login.php?error=empty");
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header("Location: login.php?error=email");
  exit;
}

$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if (mysqli_num_rows($query) > 0) {
  $user = mysqli_fetch_assoc($query);

  if (password_verify($password, $user['password'])) {

    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] == 'admin') {
      header("Location: admin-dashboard.php");
      exit;
    } elseif ($user['role'] == 'customer') {
      header("Location: home-login.php");
      exit;
    } else {
      session_destroy();
      header("Location: login.php?error=role");
      exit;
    }

  } else {
    header("Location: login.php?error=invalid");
    exit;
  }

} else {
  header("Location: login.php?error=invalid");
  exit;
}
?>
