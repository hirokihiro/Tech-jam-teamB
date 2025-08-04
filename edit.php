<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$task_id = $_GET["id"] ?? "";
$error = "";
$task = null;

if ($task_id === "") {
    echo "タスクIDが指定されていません。";
    exit;
}

if (file_exists("tasks.csv")) {
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["task_name"] ?? "");
    $deadline = $_POST["deadline"] ?? "";
    $status = $_POST["status"] ?? "";
    $priority = $_POST["priority"] ?? "";

    if ($name === "" || $deadline === "") {
        $error = "タスク名と期限は必須です。";
    } else {
        $new_lines = [];
        foreach ($lines as $line) {
            $data = str_getcsv($line);
            if ($data[0] === $task_id) {
                // 担当者名は変更しない（$data[3]）
                $data = [
                    $task_id,
                    $name,
                    $deadline,
                    $data[3],  // 担当者名をキープ
                    $status,
                    $priority
                ];
                $new_lines[] = implode(",", $data);
            } else {
                $new_lines[] = $line;
            }
        }
        file_put_contents("tasks.csv", implode("\n", $new_lines) . "\n");

        header("Location: list.php");
        exit;
    }
}
?>

<form method="post">
  <label>タスク名: <input type="text" name="task_name" value="<?= htmlspecialchars($task[1]) ?>"></label><br>
  <label>期限: <input type="date" name="deadline" value="<?= htmlspecialchars($task[2]) ?>"></label><br>
  <label>進捗度:
    <select name="status">
      <option value="未完了" <?= $task[4] === "未完了" ? "selected" : "" ?>>未完了</option>
      <option value="進行中" <?= $task[4] === "進行中" ? "selected" : "" ?>>進行中</option>
      <option value="完了" <?= $task[4] === "完了" ? "selected" : "" ?>>完了</option>
    </select>
  </label><br>
  <label>優先度:
    <select name="priority">
      <option value="1" <?= $task[5] === "1" ? "selected" : "" ?>>低</option>
      <option value="2" <?= $task[5] === "2" ? "selected" : "" ?>>中</option>
      <option value="3" <?= $task[5] === "3" ? "selected" : "" ?>>高</option>
    </select>
  </label><br>
  <button type="submit">保存</button>
</form>

<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<a href="detail.php?id=<?= urlencode($task_id) ?>">詳細に戻る</a><br>
<a href="list.php">一覧に戻る</a>
