<?php
$error = "";

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

        header("Location: list.php");
        exit();
    }
}
?>

<form method="post">
    <label>Name<input type="text" name="name" value="<?= htmlspecialchars($name ?? '') ?>"></label><br>
    <label>Password<input type="password" name="password"></label><br>
    <button type="submit">新規登録</button>
</form>
<p><?= htmlspecialchars($error) ?></p>
