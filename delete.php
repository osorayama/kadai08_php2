<?php
require_once('funcs.php');
ensure_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('select.php');
}

$token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($token)) {
  exit('CSRF token validation failed');
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) { exit('Invalid ID'); }

$pdo = db_conn();
$stmt = $pdo->prepare('DELETE FROM gs_bm_table WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  exit('DeleteError:' . h($e->getMessage()));
}

if ($status === false) { sql_error($stmt); }

redirect('select.php');
