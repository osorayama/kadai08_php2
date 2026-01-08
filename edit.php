<?php
require_once('funcs.php');
ensure_session();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    exit('Invalid ID');
}

$pdo = db_conn();
$stmt = $pdo->prepare('SELECT id, bookName, bookUrl, comment FROM gs_bm_table WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
if (!$row) {
    exit('Record not found');
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ブックマーク編集</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>
<body>
<header class="header">
  <div class="nav-container">
    <a href="select.php" class="logo"><i class="fas fa-book"></i> ブックマーク一覧</a>
    <a href="index.php" class="nav-link"><i class="fas fa-plus"></i> 新規登録</a>
  </div>
</header>
<main class="main-container form-page">
  <div class="form-card">
    <h1 class="form-title">ブックマークを編集</h1>
    <p class="form-subtitle">内容を更新して保存します</p>

    <form method="post" action="update.php" novalidate>
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

      <div class="form-group">
        <label for="bookName" class="form-label"><i class="fas fa-book"></i> 書籍名</label>
        <input type="text" id="bookName" name="bookName" class="form-input" maxlength="64" required value="<?= h($row['bookName']) ?>">
      </div>

      <div class="form-group">
        <label for="bookUrl" class="form-label"><i class="fas fa-link"></i> 書籍URL</label>
        <input type="url" id="bookUrl" name="bookUrl" class="form-input" required value="<?= h($row['bookUrl']) ?>">
      </div>

      <div class="form-group">
        <label for="comment" class="form-label"><i class="fas fa-comment"></i> コメント</label>
        <textarea id="comment" name="comment" class="form-textarea" required><?= h($row['comment']) ?></textarea>
      </div>

      <button type="submit" class="submit-btn">更新する</button>
    </form>
  </div>
</main>
</body>
</html>
