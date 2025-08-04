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

<form method="post">
    <label>Name<input type="text" name="name"></label><br>
    <label>Password<input type="password" name="password"></label><br>
    <button type="submit">ログイン</button>
</form>
<p><?= htmlspecialchars($error) ?></p>
<p><a href="user.php">アカウントがない場合</a></p>
