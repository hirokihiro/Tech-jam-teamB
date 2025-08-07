<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["task_id"])) {
    $task_id = $_POST["task_id"];
    $new_lines = [];

    if (file_exists("tasks.csv")) {
        foreach (file("tasks.csv", FILE_IGNORE_NEW_LINES) as $line) {
            $data = str_getcsv($line);
            if ($data[0] !== $task_id) {
                $new_lines[] = $line;
            }
        }
        // 改行コードを明示的に付与
        file_put_contents("tasks.csv", implode(PHP_EOL, $new_lines) . PHP_EOL);
    }
    header("Location: task.php");
    exit;
} else {
    echo "不正なアクセスです。";
}
