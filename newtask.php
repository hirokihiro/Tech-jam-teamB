<?php
session_start();
$error = "";


$user_options = [];
if (file_exists("users.csv")) {
    $lines = file("users.csv", FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
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

        $task_id = uniqid("task_");
        $file = fopen("tasks.csv", "a");
        fputcsv($file, [$task_id, $task_name, $deadline, $user_name, $status, $priority_label]);
        fclose($file);

        header("Location: list.php");
        exit();
    }
}
?>

<form method="post">
  <label>タスク内容: <input type="text" name="task_name"></label><br>
  <label>期限: <input type="date" name="deadline"></label><br>
  <label>担当者:
    <select name="user_name">
      <option value="">選択してください</option>
      <?php foreach ($user_options as $name): ?>
        <option value="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></option>
      <?php endforeach; ?>
    </select>
  </label><br>
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

