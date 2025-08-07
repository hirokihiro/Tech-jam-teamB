<?php
session_start();

$task_id = $_GET["task_id"] ?? "";
$error = "";

// tasks.csvの読み込み
$lines = file("tasks.csv", FILE_IGNORE_NEW_LINES);
$task_data = null;

foreach ($lines as $line) {
    $data = str_getcsv($line);
    if ($data[0] === $task_id) {
        $task_data = $data;
        break;
    }
}

if (!$task_data) {
    echo "タスクが見つかりません。";
    exit;
}

// 担当者一覧の読み込み
$user_options = [];
if (file_exists("users.csv")) {
    foreach (file("users.csv", FILE_IGNORE_NEW_LINES) as $line) {
        $data = str_getcsv($line);
        if (count($data) >= 2) {
            $user_options[$data[1]] = $data[1];
        }
    }
}

// 優先度の逆変換
$priority_reverse = ["低" => "1", "中" => "2", "高" => "3"];
$current_priority = $priority_reverse[$task_data[5] ?? "低"] ?? "1";

// 更新処理
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
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
        file_put_contents("tasks.csv", implode(PHP_EOL, $new_lines) . PHP_EOL);
        header("Location: task_syousai.php?task_id=" . urlencode($task_id));
        exit;
    }
}

// 削除処理
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"])) {
    $new_lines = [];
    foreach ($lines as $line) {
        $data = str_getcsv($line);
        if ($data[0] !== $task_id) {
            $new_lines[] = implode(",", $data);
        }
    }
    file_put_contents("tasks.csv", implode(PHP_EOL, $new_lines) . PHP_EOL);
    header("Location: task.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>タスク編集</title>
    <link rel="stylesheet" href="css/hennsyuu.css">
</head>

<body>

    <!-- 編集フォーム -->
<form method="post" class="task-card">
    <div class="task-item">
        <label for="task_name">タスク内容:</label>
        <input type="text" id="task_name" name="task_name" value="<?= htmlspecialchars($task_data[1]) ?>">
    </div>
    <div class="task-item">
        <label for="deadline">期限:</label>
        <input type="date" id="deadline" name="deadline" value="<?= htmlspecialchars($task_data[2]) ?>">
    </div>
    <div class="task-item">
        <label for="user_name">担当者:</label>
        <select name="user_name" id="user_name">
            <?php foreach ($user_options as $name): ?>
                <option value="<?= htmlspecialchars($name) ?>" <?= $task_data[3] === $name ? "selected" : "" ?>>
                    <?= htmlspecialchars($name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="task-item">
        <label for="status">ステータス:</label>
        <select name="status" id="status">
            <?php foreach (["未完了", "進行中", "完了"] as $status_option): ?>
                <option value="<?= $status_option ?>" <?= $task_data[4] === $status_option ? "selected" : "" ?>>
                    <?= $status_option ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="task-item">
        <label for="priority">優先度:</label>
        <select name="priority" id="priority">
            <option value="1" <?= $current_priority === "1" ? "selected" : "" ?>>低</option>
            <option value="2" <?= $current_priority === "2" ? "selected" : "" ?>>中</option>
            <option value="3" <?= $current_priority === "3" ? "selected" : "" ?>>高</option>
        </select>
    </div>

    <!-- ボタンエリア -->
    <div class="task-item" style="display:flex; gap:1rem;">
        <button type="submit" name="update" class="update-button">更新</button>
        <button type="submit" name="delete" class="delete-button" onclick="return confirm('本当に削除しますか？')">削除</button>
    </div>

    <!-- エラーメッセージ -->
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- 戻るリンク（ボタン風） -->
    <div style="margin-top: 20px;">
        <a href="task_syousai.php?task_id=<?= htmlspecialchars($task_id) ?>" class="back-button">← 戻る</a>
    </div>
</form>

</body>

</html>