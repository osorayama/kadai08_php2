<?php
require_once('funcs.php');

$pdo = db_conn();

$highlightId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';

$orderBy = 'ORDER BY registeredDatetime DESC, id DESC';
switch ($sort) {
    case 'date_asc':
        $orderBy = 'ORDER BY registeredDatetime ASC, id ASC';
        break;
    case 'name_asc':
        $orderBy = 'ORDER BY bookName ASC, id ASC';
        break;
    case 'name_desc':
        $orderBy = 'ORDER BY bookName DESC, id DESC';
        break;
}

$where = '';
if ($q !== '') {
    $where = 'WHERE bookName LIKE :q';
}

$sql = 'SELECT id, bookName, bookUrl, comment, registeredDatetime FROM gs_bm_table ' . $where . ' ' . $orderBy;
$stmt = $pdo->prepare($sql);
if ($q !== '') {
    $stmt->bindValue(':q', '%' . $q . '%', PDO::PARAM_STR);
}
$status = $stmt->execute();

$view = '';
if ($status === false) {
    sql_error($stmt);
} else {
    while ($row = $stmt->fetch()) {
        $isHighlight = ($highlightId && (int)$row['id'] === $highlightId) ? ' highlight' : '';
        $id = (int)$row['id'];
        $name = h($row['bookName']);
        $url = h($row['bookUrl']);
        $date = h($row['registeredDatetime']);
        $comment = nl2br(h($row['comment']));

        $view .= '<div class="data-item' . $isHighlight . '">';
        $view .= '  <div class="item-head">';
        $view .= '    <div class="item-title">📘 <a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $name . '</a></div>';
        $view .= '    <div class="item-actions">';
        $view .= '      <a class="btn btn-outline btn-sm" href="edit.php?id=' . $id . '"><i class="fas fa-pen"></i> 編集</a>';
        $view .= '      <a class="btn btn-danger btn-sm" href="delete_confirm.php?id=' . $id . '"><i class="fas fa-trash"></i> 削除</a>';
        $view .= '    </div>';
        $view .= '  </div>';
        $view .= '  <div class="item-meta">🗓 ' . $date . '</div>';
        $view .= '  <div class="item-body">' . $comment . '</div>';
        $view .= '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 ブックマーク一覧・確認</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <header class="header">
        <div class="nav-container">
            <a href="#" class="logo">
                <i class="fas fa-book"></i>
                ブックマーク一覧
            </a>
            <a href="index.php" class="nav-link">
                <i class="fas fa-plus"></i>
                新規登録
            </a>
        </div>
    </header>

    <main class="main-container">
        <div class="content-card">
            <h1 class="page-title">ブックマーク一覧</h1>
            <p class="page-subtitle">登録内容の確認</p>
            <div class="toolbar">
                <form method="get" class="toolbar-form">
                    <input type="text" name="q" value="<?= h($q) ?>" placeholder="書籍名で検索" class="input">
                    <select name="sort" class="select">
                        <option value="date_desc" <?= $sort==='date_desc'?'selected':''; ?>>登録日時(新しい順)</option>
                        <option value="date_asc" <?= $sort==='date_asc'?'selected':''; ?>>登録日時(古い順)</option>
                        <option value="name_asc" <?= $sort==='name_asc'?'selected':''; ?>>書籍名(昇順)</option>
                        <option value="name_desc" <?= $sort==='name_desc'?'selected':''; ?>>書籍名(降順)</option>
                    </select>
                    <button type="submit" class="btn">検索</button>
                    <?php if($q!==''): ?>
                      <a class="btn btn-outline" href="select.php">クリア</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="data-container">
                <?php if(empty($view)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <p>まだブックマークがありません</p>
                        <p style="margin-top: 0.5rem; font-size: 0.9rem; color: #999;">
                            最初のブックマークを登録してみましょう！
                        </p>
                    </div>
                <?php else: ?>
                    <?= $view ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>