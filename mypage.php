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
$deadline_dates = [];
$taskTitlesByDate = [];
$statusData = ["未完了" => 0, "進行中" => 0, "完了" => 0];

if (file_exists("tasks.csv")) {
    $file = fopen("tasks.csv", "r");
    while (($data = fgetcsv($file)) !== false) {
        if (count($data) < 6) continue;
        $tasks[] = $data;

        if (!empty($data[2])) {
            $deadline_dates[] = $data[2];
            $taskTitlesByDate[$data[2]][] = $data[1];
        }

        if ($data[3] === $_SESSION["user_name"] && isset($statusData[$data[4]])) {
            $statusData[$data[4]]++;
        }
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

function getPriorityClass($priority)
{
    switch ($priority) {
        case '高':
            return 'priority-high';
        case '中':
            return 'priority-middle';
        case '低':
            return 'priority-low';
        default:
            return '';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
    <link rel="stylesheet" href="css/mypage.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
</head>

<body>
    <header>
        <h1><?= htmlspecialchars($_SESSION['user_name']) ?>さんのマイページ</h1>
        <div class="nav-links">
            <a href="task.php">戻る</a>
            <button id="logout-btn" onclick="location.href='logout.php'">ログアウト</button>
        </div>
    </header>
    <main class="container">
        <section class="box">
            <h2>プロフィール</h2>
            <p>ユーザー名: <?= htmlspecialchars($_SESSION['user_name']) ?></p>
            <p>担当タスク数: <?= count(array_filter($tasks, fn($t) => $t[3] === $_SESSION['user_name'])) ?> 件</p>
            <p>完了率: <?= array_sum($statusData) > 0 ? round($statusData['完了'] / array_sum($statusData) * 100) : 0 ?>%</p>
        </section>

        <section class="box">
            <h2>期限カレンダー</h2>
            <div class="calendar">
                <div id="calendar"></div>
            </div>
        </section>

        <section class="box">
            <h2>優先度中以上の自分のタスク一覧</h2>
            <ul>
                <?php foreach ($tasks as $t):
                    if ($t[3] === $_SESSION['user_name'] && in_array($t[5], ['中', '高'])): ?>
                        <li class="<?= getPriorityClass($t[5]) ?>">
                            <?= htmlspecialchars($t[1]) ?>（期限: <?= htmlspecialchars($t[2]) ?>）
                        </li>
                <?php endif;
                endforeach; ?>
            </ul>
        </section>

        <section class="box">
            <h2>⏳ 近日のタスク（3日以内）</h2>
            <ul>
                <?php
                $now = strtotime(date('Y-m-d'));
                foreach ($tasks as $t):
                    $diff = (strtotime($t[2]) - $now) / 86400;
                    if ($t[3] === $_SESSION['user_name'] && $diff >= 0 && $diff <= 3): ?>
                        <li><?= htmlspecialchars($t[1]) ?>（<?= htmlspecialchars($t[2]) ?>）</li>
                <?php endif;
                endforeach; ?>
            </ul>
        </section>

        <section class="box">
            <h2>📊 ステータス統計</h2>
            <canvas id="statusChart" width="300" height="300"></canvas>
        </section>
    </main>

    <script>
        const statusData = <?= json_encode($statusData, JSON_UNESCAPED_UNICODE) ?>;
        const deadlineDates = <?= json_encode(array_values(array_unique($deadline_dates))) ?>;
        const taskTitlesByDate = <?= json_encode($taskTitlesByDate, JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <script src="js/mypage.js"></script>
</body>

</html>