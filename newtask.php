<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_name = trim($_POST["task_name"]);
    $deadline = trim($_POST["deadline"]);
    $user_name = $_SESSION["user_name"];
    $status = trim($_POST["status"]);
    $priority = trim($_POST["priority"]);

    if ($task_name === "" || $deadline === "" || $user_id === "" || $status === "" || $priority === "") {
        $error = "全ての項目に入力してください。";
    } else {
        $task_id = uniqid("task_");
        $file = fopen("tasks.csv", "a");
        fputcsv($file, [$task_id, $task_name, $deadline, $user_name, $status, $priority]);
        fclose($file);

        header("Location: list.php");
        exit();
    }
}
?>

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

