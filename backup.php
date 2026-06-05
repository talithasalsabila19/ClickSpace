<?php
session_start();
include "koneksi.php";
require_once "backup_helper.php";

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

$result = clickspace_run_backup($conn, 'manual');
$status = $result['ok'] ? 'success' : 'failed';
header("Location: backup_list.php?status=" . $status . "&message=" . urlencode($result['message']) . "&file=" . urlencode($result['file']));
exit;
?>
