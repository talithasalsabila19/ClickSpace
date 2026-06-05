<?php
function clickspace_sql_value($value) {
  if ($value === null) {
    return "NULL";
  }
  return "'" . str_replace(["\\", "'", "\n", "\r"], ["\\\\", "\\'", "\\n", "\\r"], (string)$value) . "'";
}

function clickspace_run_backup($conn, $source = 'manual') {
  date_default_timezone_set('Asia/Jakarta');

  $backupDir = __DIR__ . '/storage/backups';
  if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
  }

  $dbRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT DATABASE() AS db_name"));
  $dbName = $dbRow ? $dbRow['db_name'] : 'cs8';
  $fileName = 'clickspace_backup_' . date('Y-m-d_H-i-s') . '.sql';
  $fullPath = $backupDir . '/' . $fileName;
  $relativePath = 'storage/backups/' . $fileName;

  $sql = "-- Backup Database ClickSpace\n";
  $sql .= "-- Database: `" . $dbName . "`\n";
  $sql .= "-- Created at: " . date('Y-m-d H:i:s') . " WIB\n\n";
  $sql .= "CREATE DATABASE IF NOT EXISTS `" . $dbName . "`;\n";
  $sql .= "USE `" . $dbName . "`;\n";
  $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

  $tables = mysqli_query($conn, "SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
  if (!$tables) {
    return ['ok' => false, 'file' => $fileName, 'path' => $relativePath, 'message' => mysqli_error($conn)];
  }

  while ($table = mysqli_fetch_array($tables)) {
    $tableName = $table[0];
    $create = mysqli_query($conn, "SHOW CREATE TABLE `" . $tableName . "`");
    $createRow = mysqli_fetch_assoc($create);

    $sql .= "-- -----------------------------------------------\n";
    $sql .= "-- Table structure for `" . $tableName . "`\n";
    $sql .= "-- -----------------------------------------------\n";
    $sql .= "DROP TABLE IF EXISTS `" . $tableName . "`;\n";
    $sql .= $createRow['Create Table'] . ";\n\n";

    $rows = mysqli_query($conn, "SELECT * FROM `" . $tableName . "`");
    if ($rows && mysqli_num_rows($rows) > 0) {
      $columns = [];
      while ($field = mysqli_fetch_field($rows)) {
        $columns[] = '`' . $field->name . '`';
      }

      while ($row = mysqli_fetch_assoc($rows)) {
        $values = array_map('clickspace_sql_value', array_values($row));
        $sql .= "INSERT INTO `" . $tableName . "` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
      }
      $sql .= "\n";
    }
  }

  $views = mysqli_query($conn, "SHOW FULL TABLES WHERE Table_type = 'VIEW'");
  if ($views) {
    while ($view = mysqli_fetch_array($views)) {
      $viewName = $view[0];
      $create = mysqli_query($conn, "SHOW CREATE VIEW `" . $viewName . "`");
      $createRow = mysqli_fetch_assoc($create);
      if ($createRow && isset($createRow['Create View'])) {
        $sql .= "DROP VIEW IF EXISTS `" . $viewName . "`;\n";
        $sql .= $createRow['Create View'] . ";\n\n";
      }
    }
  }

  $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

  $saved = file_put_contents($fullPath, $sql);
  $ok = $saved !== false && filesize($fullPath) > 0;
  $message = $ok ? 'Backup database berhasil dibuat.' : 'Backup database gagal dibuat.';

  if (mysqli_query($conn, "SHOW TABLES LIKE 'backup_logs'")) {
    $safeFile = mysqli_real_escape_string($conn, $fileName);
    $safePath = mysqli_real_escape_string($conn, $relativePath);
    $safeSource = mysqli_real_escape_string($conn, $source === 'scheduler' ? 'scheduler' : 'manual');
    $safeStatus = $ok ? 'success' : 'failed';
    $safeMessage = mysqli_real_escape_string($conn, $message);
    @mysqli_query($conn, "INSERT INTO backup_logs (nama_file, lokasi_file, sumber_backup, status_backup, keterangan) VALUES ('$safeFile', '$safePath', '$safeSource', '$safeStatus', '$safeMessage')");
  }

  return ['ok' => $ok, 'file' => $fileName, 'path' => $relativePath, 'message' => $message];
}
?>
