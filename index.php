<!-- http://localhost:8888/TECH-JAM-TEAMB/
-->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録画面</title>
    <link rel="stylesheet" href="css/style.css">
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

            <form action="register_process.php" method="post" class="register-form">
                <label for="name">Name</label><br>
                <input type="text" id="name" name="name" required><br>

                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" required><br>

                <button type="submit" class="login-button">登録</button>
            </form>
        </div>
    </div>
</body>
</html>
