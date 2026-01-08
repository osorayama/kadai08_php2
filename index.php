<?php require_once('funcs.php'); ensure_session(); ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 ブックマーク登録</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <meta name="robots" content="noindex,nofollow">
</head>

<body>

    <header class="header">
        <div class="nav-container">
            <a href="#" class="logo">
                <i class="fas fa-bookmark"></i>
                ブックマーク登録
            </a>
            <a href="select.php" class="nav-link">
                <i class="fas fa-list"></i>
                一覧/確認
            </a>
        </div>
    </header>

    <main class="main-container form-page">
        <div class="form-card">
            <h1 class="form-title">ブックマーク登録</h1>
            <p class="form-subtitle">書籍名・URL・コメントを保存します</p>

            <form method="post" action="insert.php" novalidate>
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

                <div class="form-group">
                    <label for="bookName" class="form-label">
                        <i class="fas fa-book"></i> 書籍名
                    </label>
                    <input type="text" id="bookName" name="bookName" class="form-input" placeholder="例：リーダブルコード" maxlength="64" required>
                </div>

                <div class="form-group">
                    <label for="bookUrl" class="form-label">
                        <i class="fas fa-link"></i> 書籍URL
                    </label>
                    <input type="url" id="bookUrl" name="bookUrl" class="form-input" placeholder="例：https://example.com/book" required>
                </div>

                <div class="form-group">
                    <label for="comment" class="form-label">
                        <i class="fas fa-comment"></i> コメント
                    </label>
                    <textarea id="comment" name="comment" class="form-textarea" placeholder="メモや感想などをどうぞ..." required></textarea>
                </div>

                <button type="submit" class="submit-btn">
                    登録する
                </button>
            </form>
        </div>
    </main>
</body>

</html>