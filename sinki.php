<?php
session_start();
$error = "";
$name = "";

// POSTで送信されたときだけ処理
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $password = trim($_POST["password"]);

    // 入力チェック
    if ($name === "" || $password === "") {
        $error = "名前とパスワードを入力してください。";
    } else {
        // 重複チェック（既に登録されている名前を確認）
        $is_duplicate = false;
        if (file_exists("users.csv")) {
            if (($handle = fopen("users.csv", "r")) !== false) {
                while (($data = fgetcsv($handle)) !== false) {
                    if ($data[1] === $name) {
                        $is_duplicate = true;
                        break;
                    }
                }
                fclose($handle);
            }
        }

        if ($is_duplicate) {
            $error = "この名前はすでに使用されています。別の名前を入力してください。";
        } else {
            // ユーザー登録処理
            $register_date = date("Y-m-d");
            $user_id = uniqid("user_");
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // ファイル書き込み（ロック付き）
            $file = fopen("users.csv", "a");
            if ($file) {
                if (flock($file, LOCK_EX)) {
                    fputcsv($file, [$user_id, $name, $hashed_password, $register_date]);
                    flock($file, LOCK_UN);
                    fclose($file);

                    // 登録完了 → ログインページへ
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "ファイルロックに失敗しました。";
                    fclose($file);
                }
            } else {
                $error = "ユーザー情報の保存に失敗しました。";
            }
        }
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
            <h1>チータス君</h1>
            <h2 class="register-title">新規登録</h2>

            <form method="post" class="register-form">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="login-button">新規登録</button>
            </form>

            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <p class="no-account">
                <a href="login.php">ログイン画面へ戻る</a>
            </p>
        </div>
    </div>
</body>
</html>
