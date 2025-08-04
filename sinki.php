<!-- http://localhost:8888/TECH-JAM-TEAMB/index.php　-->

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
                <input type="text" id="name" name="name" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="login-button">新規登録</button>
            </form>
            <p class="no-account">
                <a href="login.php">ログイン画面へ戻る</a>
            </p>
        </div>
    </div>
</body>
</html>
