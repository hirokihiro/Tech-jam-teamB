<?php
session_start();

$task_id = $_GET["id"] ?? "";
$error = "";

if (!file_exists("tasks.csv")) {
    echo "タスクファイルが見つかりません。";
    exit;
}

$lines = file("tasks.csv", FILE_IGNORE_NEW_LINES);
$task = null;
foreach ($lines as $line) {
    $data = str_getcsv($line);
    if ($data[0] === $task_id) {
        $task = $data;
        break;
    }
}
if (!$task) {
    echo "タスクが見つかりません。";
    exit;
}

$user_options = [];
if (file_exists("users.csv")) {
    foreach (file("users.csv", FILE_IGNORE_NEW_LINES) as $line) {
        $data = str_getcsv($line);
        if (count($data) >= 2) {
            $user_options[$data[1]] = $data[1];
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_name = trim($_POST["task_name"]);
    $deadline = trim($_POST["deadline"]);
    $user_name = trim($_POST["user_name"]);
    $status = trim($_POST["status"]);
    $priority = trim($_POST["priority"]);

    if ($task_name === "" || $deadline === "" || $user_name === "" || $status === "" || $priority === "") {
        $error = "全ての項目に入力してください。";
    } else {
        $priority_labels = ["1" => "低", "2" => "中", "3" => "高"];
        $priority_label = $priority_labels[$priority] ?? "不明";

        $new_lines = [];
        foreach ($lines as $line) {
            $data = str_getcsv($line);
            if ($data[0] === $task_id) {
                $data = [$task_id, $task_name, $deadline, $user_name, $status, $priority_label];
            }
            $new_lines[] = implode(",", $data);
        }
        file_put_contents("tasks.csv", implode("\n", $new_lines));

        header("Location: list.php");
        exit;
    }
}


$priority_reverse = ["低" => "1", "中" => "2", "高" => "3"];
$current_priority = $priority_reverse[$task[5]] ?? "1";
?>

<form method="post">
  <label>タスク内容: <input type="text" name="task_name" value="<?= htmlspecialchars($task[1]) ?>"></label><br>
  <label>期限: <input type="date" name="deadline" value="<?= htmlspecialchars($task[2]) ?>"></label><br>
  <label>担当者:
    <select name="user_name">
      <?php foreach ($user_options as $name): ?>
        <option value="<?= htmlspecialchars($name) ?>" <?= $task[3] === $name ? "selected" : "" ?>>
          <?= htmlspecialchars($name) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label><br>
  <label>ステータス:
    <select name="status">
      <?php foreach (["未完了", "進行中", "完了"] as $status_option): ?>
        <option value="<?= $status_option ?>" <?= $task[4] === $status_option ? "selected" : "" ?>>
          <?= $status_option ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label><br>
  <label>優先度:
    <select name="priority">
      <option value="1" <?= $current_priority === "1" ? "selected" : "" ?>>低</option>
      <option value="2" <?= $current_priority === "2" ? "selected" : "" ?>>中</option>
      <option value="3" <?= $current_priority === "3" ? "selected" : "" ?>>高</option>
    </select>
  </label><br>
  <button type="submit">更新</button>
</form>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>

