<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>編集画面</title>
  <link rel="stylesheet" href="css/hennsyuu.css">
</head>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>編集画面</title>
  <link rel="stylesheet" href="css/hennsyuu.css">
</head>


<body>

  <!-- アプリ名 -->
  <div class="header">アプリ名</div>

  <!-- 戻るボタン -->
  <button class="back-button" onclick="location.href='task_syousai.php?task_id=<?= htmlspecialchars($_GET['task_id'] ?? '') ?>'">戻る</button>

  <!-- 編集カード -->
  <div class="task-card">
    <!-- タスク -->
    <div class="task-item">
      <label>タスク</label>
      <div class="task-content"><?= htmlspecialchars($task_data[1] ?? '') ?></div>
    </div>

    <!-- 担当者（追加項目） -->
    <div class="task-item">
      <label>担当者</label>
      <div class="task-content"><?= htmlspecialchars($task_data[3] ?? '') ?></div>
    </div>

    <!-- 進捗度 -->
    <div class="task-item">
      <label>進捗度</label>
      <div class="task-content"><?= htmlspecialchars($task_data[4] ?? '') ?></div>
    </div>

    <!-- 期限 -->
    <div class="task-item">
      <label>期限</label>
      <div class="task-content"><?= htmlspecialchars($task_data[2] ?? '') ?></div>
    </div>

    <!-- 削除ボタン -->
    <button class="delete-button">削除</button>
  </div>

</body>
</html>
