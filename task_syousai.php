<?php
// タスク取得処理
$task_data = null;
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    if (file_exists("tasks.csv")) {
        $file = fopen("tasks.csv", "r");
        while (($data = fgetcsv($file)) !== false) {
            if ($data[0] === $task_id) {
                $task_data = $data;
                break;
            }
        }
        fclose($file);
    }
}

// 進捗度を数値に変換
function getProgressPercent($status) {
    switch ($status) {
        case "完了": return 100;
        case "進行中": return 50;
        default: return 0;
    }
}
$progress = $task_data ? getProgressPercent($task_data[4]) : 0;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>タスク詳細</title>
    <link rel="stylesheet" href="css/task_syousai.css">
   
    
</head>
<body>
    
    <header>
        <h1>アプリ名</h1>
        
    </header>

    <main class="detail-container">
        <!-- 戻るボタン -->
        <a href="task.php" class="back-button">← 戻る</a>

        <?php if ($task_data): ?>
            <div class="task-detail-box">
                
                <!-- タスク名または番号 -->
                <div class="task-content-box">
                    <h2><?= htmlspecialchars($task_data[1]) ?></h2>
                </div>

                <!-- 担当者 -->
                <div class="task-info">
                    <p><strong>担当者:</strong> <?= htmlspecialchars($task_data[3]) ?></p>

                    <!-- 進捗度 -->
                    <p><strong>進捗度:</strong> <?= htmlspecialchars($task_data[4]) ?></p>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill"></div>
                        </div>
                    </div>

                    <!-- 期限 -->
                    <p><strong>期限:</strong> <?= htmlspecialchars($task_data[2]) ?></p>
                </div>

                <!-- 編集ボタン -->
                <div class="task-edit-btn">
                    <form action="hennsyuu.php" method="get">
                        <input type="hidden" name="task_id" value="<?= htmlspecialchars($task_data[0]) ?>">
                        <button type="submit">編集</button>
                    </form>
                </div>

            </div>
        <?php else: ?>
            <p>タスクが見つかりませんでした。</p>
        <?php endif; ?>
    </main>
</body>
</html>
