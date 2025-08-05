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
    <style>
        /* JSで進捗バーを動的に操作する場合はここで指定もOK */
        .progress-fill {
            width: <?= $progress ?>%;
        }
    </style>
</head>
<body>
    <header>
        <h1>アプリ名</h1>
    </header>

    <main class="detail-container">
        <a href="task.php" class="back-button">← 戻る</a>

        <?php if ($task_data): ?>
            <div class="task-detail-box">
                <div class="task-content-box">
                    <h2><?= htmlspecialchars($task_data[1]) ?></h2>
                </div>

                <div class="task-info">
                    <p><strong>進捗度:</strong> <?= htmlspecialchars($task_data[4]) ?></p>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill"></div>
                        </div>
                    </div>
                    <p><strong>期限:</strong> <?= htmlspecialchars($task_data[2]) ?></p>
                </div>

                <div class="task-edit-btn">
                    <button>編集</button>
                </div>
            </div>
        <?php else: ?>
            <p>タスクが見つかりませんでした。</p>
        <?php endif; ?>
    </main>
</body>
</html>
