<?php
session_start();
$error = "";


if (!isset($_SESSION["user_name"])) {
    $_SESSION["user_name"] = "ゲスト";
}

$users = [];
if (file_exists("users.csv")) {
    $file = fopen("users.csv", "r");
    while (($data = fgetcsv($file)) !== false) {
        if (!empty($data[1])) {
            $users[] = $data[1]; 
        }
    }
    fclose($file);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_name = trim($_POST["task_name"]);
    $deadline = trim($_POST["deadline"]);
    $status = trim($_POST["status"]);
    $priority = trim($_POST["priority"]);
    $assignee = trim($_POST["assignee"]);

    if ($task_name === "" || $deadline === "" || $status === "" || $priority === "" || $assignee === "") {
        $error = "全ての項目に入力してください。";
    } else {
        $task_id = uniqid("task_");
        $file = fopen("tasks.csv", "a");
        fputcsv($file, [$task_id, $task_name, $deadline, $assignee, $status, $priority]);
        fclose($file);

        header("Location: " . $_SERVER['PHP_SELF'] . "?sort=" . ($_GET['sort'] ?? ''));
        exit();
    }
}


$sort_key = $_GET['sort'] ?? 'priority';


$tasks = [];
if (file_exists("tasks.csv")) {
    $file = fopen("tasks.csv", "r");
    while (($data = fgetcsv($file)) !== false) {
        $tasks[] = $data;
    }
    fclose($file);

    usort($tasks, function ($a, $b) use ($sort_key) {
        switch ($sort_key) {
            case 'priority':
                return (int)$b[5] - (int)$a[5];
            case 'deadline':
                return strtotime($a[2]) - strtotime($b[2]);
            case 'status':
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
                            担当者: <?= htmlspecialchars($task[3]) ?> / ステータス: <?= htmlspecialchars($task[4]) ?> / 優先度: <?= htmlspecialchars($task[5]) ?>
                        </div>
                        <button class="detail-btn">詳細</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <aside class="calendar">
            <div class="calendar-box">
                <div id="calendar-container"></div>
                <div id="selected-date-box" class="date-display-box"></div>
            </div>
        </aside>

        <div class="fixed-add-button">
            <button id="show-form-btn">＋ タスクを追加</button>
        </div>

        <div class="floating-form" id="floating-form">
            <form method="post">
                <h2>タスク追加</h2>
                <?php if ($error): ?>
                    <p style="color:red;"><?= $error ?></p>
                <?php endif; ?>
                <label for="task_name">タスク名:</label>
                <input type="text" id="task_name" name="task_name" required><br>

                <label for="deadline">期限:</label>
                <input type="date" id="deadline" name="deadline" required><br>

                <label for="status">ステータス:</label>
                <select name="status" id="status">
                    <option value="未完了">未完了</option>
                    <option value="進行中">進行中</option>
                    <option value="完了">完了</option>
                </select><br>

                <label for="priority">優先度:</label>
                <select name="priority" id="priority">
                    <option value="1">低</option>
                    <option value="2">中</option>
                    <option value="3">高</option>
                </select><br>

                <label for="assignee">担当者:</label>
                <select name="assignee" id="assignee" required>
                    <option value="">-- 選択してください --</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user) ?>"><?= htmlspecialchars($user) ?></option>
                    <?php endforeach; ?>
                </select><br>

                <button type="submit">追加</button>
                <button type="button" id="cancel-form-btn">キャンセル</button>
            </form>
        </div>
    </main>

    <script src="js/task.js"></script>
</body>
</html>


