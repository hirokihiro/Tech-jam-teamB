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

        // ユーザー情報をCSVに保存
        $file = fopen("users.csv", "a");
        if ($file) {
            fputcsv($file, [$user_id, $name, $hashed_password, $register_date]);
            fclose($file);

            // セッションに保存してログイン済みとする（仮）
            $_SESSION["user_name"] = $name;

            header("Location: list.php");
            exit();
        } else {
            $error = "ユーザー情報の保存に失敗しました。";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー登録 | タスク管理アプリ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <h1>タスク管理アプリ - ユーザー登録</h1>
</header>


<form method="post">
    <label>Name<input type="text" name="name" value="<?= htmlspecialchars($name ?? '') ?>"></label><br>
    <label>Password<input type="password" name="password"></label><br>
    <button type="submit">新規登録</button>
</form>
<p><?= htmlspecialchars($error) ?></p>


<main class="container">
    <section class="form-section">
        <h2>新規ユーザー登録</h2>
        <form method="post" class="register-form">
            <label>名前:
                <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
            </label><br>
            <label>パスワード:
                <input type="password" name="password">
            </label><br>
            <button type="submit">登録する</button>
        </form>
        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
