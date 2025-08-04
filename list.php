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