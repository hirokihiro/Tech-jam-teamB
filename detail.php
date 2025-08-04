<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$task_id = $_GET["id"] ?? "";
$task = null;

if ($task_id !== "" && file_exists("tasks.csv")) {
    $lines = file("tasks.csv", FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        $data = str_getcsv($line);
        if ($data[0] === $task_id) {
            $task = $data;
            break;
        }
    }
}

if (!$task) {
    echo "タスクが見つかりません。";
    exit;
}

$priority_labels = [
    "1" => "低",
    "2" => "中",
    "3" => "高",
];


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"])) {
    if (file_exists("tasks.csv")) {
        $lines = file("tasks.csv", FILE_IGNORE_NEW_LINES);
        $new_lines = [];
        foreach ($lines as $line) {
            $data = str_getcsv($line);
            if ($data[0] !== $task_id) {
                $new_lines[] = $line;
            }
        }
        file_put_contents("tasks.csv", implode("\n", $new_lines) . "\n");
    }
    header("Location: list.php");
    exit;
}
?>

<h2>タスク詳細</h2>
<p><strong>タスク内容：</strong><?= htmlspecialchars($task[1]) ?></p>
<p><strong>担当者名：</strong><?= htmlspecialchars($task[3]) ?></p>
<p><strong>期限：</strong><?= htmlspecialchars($task[2]) ?></p>
<p><strong>進捗度：</strong><?= htmlspecialchars($task[4]) ?></p>
<p><strong>優先度：</strong><?= htmlspecialchars($priority_labels[$task[5]] ?? $task[5]) ?></p>

<a href="edit.php?id=<?= urlencode($task_id) ?>">編集する</a><br>
<a href="list.php">一覧に戻る</a>

<form method="post" onsubmit="return confirm('本当にこのタスクを削除しますか？');" style="margin-top:20px;">
  <input type="hidden" name="delete" value="1">
  <button type="submit">削除</button>
</form>

