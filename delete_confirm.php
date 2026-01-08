<?php
require_once('funcs.php');
ensure_session();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { exit('Invalid ID'); }

$pdo = db_conn();
$stmt = $pdo->prepare('SELECT id, bookName, bookUrl, comment, registeredDatetime FROM gs_bm_table WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
if (!$row) { exit('Record not found'); }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>削除の確認</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <style>
    .confirm-box{background:var(--card-bg);border:1px solid var(--border);border-radius:10px;padding:1rem;margin-top:1rem}
    .confirm-actions{display:flex;gap:.5rem;margin-top:1rem}
    .btn-danger{background:#9b2c2c;color:#fff;border:1px solid #7f1d1d}
    .btn-danger:hover{background:#7f1d1d}
  </style>
</head>
<body>
<header class="header">
  <div class="nav-container">
    <a href="select.php" class="logo"><i class="fas fa-book"></i> ブックマーク一覧</a>
    <a href="index.php" class="nav-link"><i class="fas fa-plus"></i> 新規登録</a>
  </div>
</header>
<main class="main-container">
  <div class="content-card">
    <h1 class="page-title">削除の確認</h1>
    <p class="page-subtitle">このブックマークを削除しますか？</p>

    <div class="confirm-box">
      <div class="data-date"><?= h($row['registeredDatetime']) ?></div>
      <div class="data-name">📘 <a href="<?= h($row['bookUrl']) ?>" target="_blank" rel="noopener noreferrer"><?= h($row['bookName']) ?></a></div>
      <div class="data-content"><?= nl2br(h($row['comment'])) ?></div>

      <form method="post" action="delete.php" class="confirm-actions">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
        <button type="submit" class="btn btn-danger">削除する</button>
        <a href="select.php" class="btn btn-outline">キャンセル</a>
      </form>
    </div>
  </div>
</main>
</body>
</html>
