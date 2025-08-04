<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
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
            if ($data[1] === $name) {
                if (password_verify($password, $data[2])) {
                    $_SESSION["user_id"] = $data[0];
                    $_SESSION["user_name"] = $data[1];
                    $found = true;
                    fclose($users);
                    header("Location: list.php");
                    exit();
                }
            }
        }
        fclose($users);

        if (!$found) {
            $error = "ログインに失敗しました";
        }
    }
}
?>
    <div class="login-container">
        <div class="background-shapes">
            <div class="square square1"></div>
            <div class="square square2"></div>
            <div class="square square3"></div>
            <div class="square square4"></div>
            <div class="square square5"></div>
            <div class="square square6"></div>
            <div class="square square7"></div>
        </div>

        <div class="login-box">
            <h1>アプリ名</h1>
            <h2 class="register-title">ログイン</h2>
            <form method="post" class="register-form">
                <label for="name">Name</label>
                <input type="text" name="name" id="name">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
                <button type="submit" class="login-button">ログイン</button>
            </form>
            <p class="no-account"><?= htmlspecialchars($error) ?></p>
            <p class="no-account"><a href="user.php">アカウントがない場合</a></p>
        </div>
    </div>
</body>
</html>
