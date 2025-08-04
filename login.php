<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $password = trim($_POST["password"]);

    if ($name === "" || $password === "") {
        $error = "名前とパスワードを入力してください。";
    } else {
        $users = fopen("users.csv", "r");
        $found = false;

        while (($data = fgetcsv($users)) !== false) {
            if ($data[1] === $name && $data[2] === $password) {
                $_SESSION["user_id"] = $data[0];
                $_SESSION["user_name"] = $data[1];
                $found = true;
                header("Location: list.php");
                exit();
            }
        }
        fclose($users);

        if (!$found) $error = "ログイン失敗";
    }
}

?>



<form method="post">
    <label>名前:<input type="text" name="name"></label><br>
    <label>パスワード:<input type="password" name="password"></label><br>
    <button type="submit">ログイン</button>
</form>
<p><?= htmlspecialchars($error) ?></p>
<p><a href="user.php">アカウントがない場合</a></p>