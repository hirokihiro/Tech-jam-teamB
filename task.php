<?php
session_start();
$error = "";

// ユーザー名（仮ログイン）
if (!isset($_SESSION["user_name"])) {
    $_SESSION["user_name"] = "ゲスト";
}

// タスク追加処理
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_name = trim($_POST["task_name"]);
    $deadline = trim($_POST["deadline"]);
    $user_name = $_SESSION["user_name"];
    $status = trim($_POST["status"]);
    $priority = trim($_POST["priority"]);

    if ($task_name === "" || $deadline === "" || $user_name === "" || $status === "" || $priority === "") {
        $error = "全ての項目に入力してください。";
    } else {
        $task_id = uniqid("task_");
        $file = fopen("tasks.csv", "a");
        fputcsv($file, [$task_id, $task_name, $deadline, $user_name, $status, $priority]);
        fclose($file);

        header("Location: " . $_SERVER['PHP_SELF'] . "?sort=" . ($_GET['sort'] ?? ''));
        exit();
    }
}

// ソート条件取得
$sort_key = $_GET['sort'] ?? 'priority';

// タスク読み込み＆ソート
$tasks = [];
if (file_exists("tasks.csv")) {
    $file = fopen("tasks.csv", "r");
    while (($data = fgetcsv($file)) !== false) {
        $tasks[] = $data;
    }
    fclose($file);

    // ソート処理
    usort($tasks, function ($a, $b) use ($sort_key) {
        switch ($sort_key) {
            case 'priority': // 優先度（数字が大きいほど優先）
                return (int)$b[5] - (int)$a[5];
            case 'deadline': // 期限（昇順）
                return strtotime($a[2]) - strtotime($b[2]);
            case 'status': // ステータス（文字順）
                return strcmp($a[4], $b[4]);
            default:
                return 0;
        }
    });
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>タスク管理アプリ</title>
    <link rel="stylesheet" href="css/task.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>

</head>
<script src="js/task.js"></script>

<body>
    <header>
        <h1>タスク管理アプリ</h1>
    </header>

    <main class="container">
        <section class="left-panel">
            <div class="sort-tabs">
                <a href="?sort=priority"><button class="<?= $sort_key === 'priority' ? 'active' : '' ?>">優先度順</button></a>
                <a href="?sort=deadline"><button class="<?= $sort_key === 'deadline' ? 'active' : '' ?>">期限順</button></a>
                <a href="?sort=status"><button class="<?= $sort_key === 'status' ? 'active' : '' ?>">進捗度順</button></a>
            </div>

            <div class="task-list">
                <?php foreach ($tasks as $task): ?>
                    <div class="task">
                        <div class="task-content">
                            <?= htmlspecialchars($task[1]) ?>（期限: <?= htmlspecialchars($task[2]) ?>）<br>
                            ステータス: <?= htmlspecialchars($task[4]) ?> / 優先度: <?= htmlspecialchars($task[5]) ?>
                        </div>
                        <button class="detail-btn">詳細</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="add-task">
                <h2>タスク追加フォーム</h2>
                <form method="post">
                    <label>タスク名: <input type="text" name="task_name"></label><br>
                    <label>期限: <input type="date" name="deadline"></label><br>
                    <label>ステータス:
                        <select name="status">
                            <option value="未完了">未完了</option>
                            <option value="進行中">進行中</option>
                            <option value="完了">完了</option>
                        </select>
                    </label><br>
                    <label>優先度:
                        <select name="priority">
                            <option value="1">低</option>
                            <option value="2">中</option>
                            <option value="3">高</option>
                        </select>
                    </label><br>
                    <button type="submit">追加</button>
                </form>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            </div>
        </section>

       <aside class="calendar">
                <div class="calendar-box">
                <div id="calendar-container"></div>

                <!-- 黒枠のボックス -->
                <div id="selected-date-box" class="date-display-box">
                <!-- JSでここに日付を表示 -->
                </div>
            </div>
        </aside>




        
    </main>
</body>
</html>
