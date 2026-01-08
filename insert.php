<?php
require_once('funcs.php');
ensure_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($token)) {
    exit('CSRF token validation failed');
}

$bookName = trim($_POST['bookName'] ?? '');
$bookUrl = trim($_POST['bookUrl'] ?? '');
$comment = trim($_POST['comment'] ?? '');

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

$sql = 'INSERT INTO gs_bm_table (bookName, bookUrl, comment, registeredDatetime) VALUES (:bookName, :bookUrl, :comment, :registeredDatetime)';
$stmt = $pdo->prepare($sql);

$now = (new DateTime('now', new DateTimeZone('Asia/Tokyo')))->format('Y-m-d H:i:s');
$stmt->bindValue(':bookName', $bookName, PDO::PARAM_STR);
$stmt->bindValue(':bookUrl', $bookUrl, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':registeredDatetime', $now, PDO::PARAM_STR);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    exit('InsertError:' . h($e->getMessage()));
}

if ($status === false) {
    sql_error($stmt);
}

$id = $pdo->lastInsertId();
redirect('select.php?id=' . urlencode($id));
