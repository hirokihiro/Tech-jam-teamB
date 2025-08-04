<?php
session_start();
$error = "";
$name = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $password = trim($_POST["password"]);

    if ($name === "" || $password === "") {
        $error = "名前とパスワードを入力してください。";
    } else {
        $register_date = date("Y-m-d");
        $user_id = uniqid("user_");

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $file = fopen("users.csv", "a");
        fputcsv($file, [$user_id, $name, $hashed_password, $register_date]);
        fclose($file);

        $_SESSION["user_name"] = $name;
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録画面</title>
    <link rel="stylesheet" href="css/sinki.css">
</head>
<body>
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
            <h2 class="register-title">新規登録</h2>

            <form method="post" class="register-form">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($name) ?>">

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="login-button">新規登録</button>
            </form>

            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <p class="no-account">
                <a href="login.php">ログイン画面へ戻る</a>
            </p>
        </div>
    </div>
</body>
</html>
