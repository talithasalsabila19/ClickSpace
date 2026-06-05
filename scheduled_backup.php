<?php
include "koneksi.php";
require_once "backup_helper.php";

$result = clickspace_run_backup($conn, 'scheduler');

echo ($result['ok'] ? 'SUCCESS' : 'FAILED') . PHP_EOL;
echo $result['message'] . PHP_EOL;
echo 'File: ' . $result['file'] . PHP_EOL;
?>
