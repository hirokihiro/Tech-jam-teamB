<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$allowed_keys = ["deadline", "status", "priority"];
$sort_key = $_GET["sort"] ?? "deadline";
if (!in_array($sort_key, $allowed_keys)) {
    $sort_key = "deadline";
}

$tasks = [];
if (($fp = fopen("tasks.csv", "r")) !== false) {
    while (($line = fgetcsv($fp)) !== false) {
        $tasks[] = $line;
    }
    fclose($fp);
}


$key = [
    "deadline" => 2,
    "status" => 4,
    "priority" => 5,
];


$priority_labels = [
    "1" => "低",
    "2" => "中",
    "3" => "高",
];

$status_order = [
    "未完了" => 0,
    "進行中" => 1,
    "完了" => 2,
];

usort($tasks, function ($a, $b) use ($key, $sort_key, $status_order) {
    $index = $key[$sort_key];
    $valA = $a[$index] ?? "";
    $valB = $b[$index] ?? "";

    if ($sort_key === "priority") {
        return (int)$valB - (int)$valA;
    } elseif ($sort_key === "status") {
        $posA = $status_order[$valA] ?? 99;
        $posB = $status_order[$valB] ?? 99;
        return $posA - $posB;
    } else {
        return strcmp((string)$valA, (string)$valB);
    }
});
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>タスク一覧</title>
</head>
<body>
    <h2><a href="list.php" style="text-decoration:none; color:inherit;">アプリ名</a></h2>
    <a href="logout.php">ログアウト</a>
    <a href="newtask.php">新規タスク追加</a>
    <table>
        <tr>
            <th><a href="?sort=deadline">期限</a></th>
            <th><a href="?sort=status">進捗度</a></th>
            <th><a href="?sort=priority">優先度</a></th>
        </tr>
        <?php foreach ($tasks as $t): ?>
        <tr>
            <td><a href="detail.php?id=<?= urlencode($t[0]) ?>"><?= htmlspecialchars($t[1]) ?></a></td>
            <td><?= htmlspecialchars($t[2]) ?></td>
            <td><?= htmlspecialchars($t[3]) ?></td>
            <td><?= htmlspecialchars($t[4]) ?></td>
            <td><?= htmlspecialchars($priority_labels[$t[5]] ?? $t[5]) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
