<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$sort_key = $_GET["sort"] ?? "deadline";
$tasks = [];

if (($fp = fopen("tasks.csv", "r")) !== false) {
    while (($line = fgetcsv($fp)) !== false) {
        $tasks[] = $line;
    }
    fclose($fp);
}

$key = ["deadline" => 2, "status" => 4, "priority" => 5];
usort($tasks, function ($a, $b) use ($keys, $sort_key) {
    return strcmp($a[$keys[$sort_key]], $b[$keys[$sort_key]]);
});
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タスク一覧</title>
</head>
<h2>アプリ名</h2>
<a href="newtask.php">新規タスク追加</a>
<table>
  <tr>
    <th><a href="?sort=">名前</a></th>
    <th><a href="?sort=deadline">期限</a></th>
    <th>担当者</th>
    <th><a href="?sort=status">ステータス</a></th>
    <th><a href="?sort=priority">優先度</a></th>
  </tr>
  <?php foreach ($tasks as $t): ?>
    <tr>
      <td><a href="detail.php?id=<?= urlencode($t[0]) ?>"><?= htmlspecialchars($t[0]) ?></a></td>
      <td><?= htmlspecialchars($t[1]) ?></td>
      <td><?= htmlspecialchars($t[2]) ?></td>
      <td><?= htmlspecialchars($t[3]) ?></td>
      <td><?= htmlspecialchars($t[4]) ?></td>
    </tr>
  <?php endforeach; ?>
</table>