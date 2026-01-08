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
$bookName = trim($_POST['bookName'] ?? '');
$bookUrl = trim($_POST['bookUrl'] ?? '');
$comment = trim($_POST['comment'] ?? '');

if ($id <= 0) { exit('Invalid ID'); }

$errors = [];
if ($bookName === '' || mb_strlen($bookName) > 64) {
  $errors[] = '書籍名は1〜64文字で入力してください。';
}

if ($comment === '') {
  $errors[] = 'コメントを入力してください。';
}

if ($errors) {
  exit(h(implode("\n", $errors)));
}

$pdo = db_conn();
$sql = 'UPDATE gs_bm_table SET bookName = :bookName, bookUrl = :bookUrl, comment = :comment WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':bookName', $bookName, PDO::PARAM_STR);
$stmt->bindValue(':bookUrl', $bookUrl, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  exit('UpdateError:' . h($e->getMessage()));
}

if ($status === false) { sql_error($stmt); }

redirect('select.php?id=' . urlencode($id));
