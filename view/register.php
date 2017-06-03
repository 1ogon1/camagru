<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>camagru</title>
    <link href="../style/style.css" rel="stylesheet">
</head>
<body>
<div class="header">
    <div class="logo">
        <a href="index.php" title="home"><img src="../img/couv_web.png" class="web" alt="home"></a>
    </div>
    <div class="block_menu">
        <ul type="none" class="menu">
            <li><a href="index.php">Home</a></li>
        </ul>
    </div>
</div>
<div class="register_form">
    <form action="register.php" method="post" class="form">
<?php
if ((isset($_POST['logon']) && $_POST['logon'] !== "") &&
    (isset($_POST['password']) && $_POST['password'] !== "") &&
    (isset($_POST['conf_password']) && $_POST['conf_password'] !== "")) {
    try {
        require_once "../config/setup.php";
        $flag = 1;
        $errors = array();
        $res = $pdo->query(SQL_GET_LOGIN, PDO::FETCH_ASSOC);
        foreach ($res as $row) {
            if (!strcmp($row['login'], $_POST['logon'])) {
                $flag = 0;
                $errors[] = "Login already exists";
            }
            if (strcmp($_POST['password'], $_POST['conf_password'])) {
                $flag = 0;
                $errors[] = "Passwords do not match";
            }
        }
        if ($flag) {
            $passwd = hash("whirlpool", $_POST['password']);
            $stmt = $pdo->prepare(SQL_CREATE_USER);
            $stmt->execute([
                $_POST['logon'],
                $passwd,
                0
            ]);
            $login = hash("md5", $_POST['logon']);
//            $pdo->exec("INSERT INTO activate (login, login_activate) VALUES ('$_POST[logon]', '$login')");
            $stmt = $pdo->prepare(SQL_ACTIVATE_LINK);
            $stmt->execute([
                $_POST['logon'],
                $login
            ]);
            $email = 'konovalenkoruslan@gmail.com';
            $headers = "Content-Type: text/html; charset=utf-8"."\r\n";
            $subject = "Camagru Account Activation";
            $r1 = "<html><head><style>.button { background-color: #646464 ; border: none;color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer;}</style><head>";
            $r2 = "<body><h1>Camagru Account Activation</h1>";
            $r3 = "<article><p>Hi, {$_POST['logon']}!</p><p>Thanks for registration on <span>Camagru<span></p><p>To activate your account on site please click on button below</p>";
            $r4 = "<a href='http://localhost:8080/camagru/view/activate.php?login={$_POST['logon']}&active={$login}' class='button'>Activate</a></article>";
            $r5 = "<p>Best regards, Camagru Dev</p></body></html>";
            $message = $r1.$r2.$r3.$r4.$r5;
            mail($email, $subject, $message, $headers);
            header("location:index.php");
        } else {
            echo '<div style="color: red;">'.array_shift($errors).'</div>';
        }
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}
?>
        <input type="text" name="logon" value="" placeholder="login" required>
        <br>
        <input type="password" name="password" value="" placeholder="password" required>
        <br>
        <input type="password" name="conf_password" value="" placeholder="confirm password" required>
        <br>
        <input type="submit" name="submit" value="Register" class="submit">
    </form>
</div>
<div class="footer">
    <hr>
    <p>&copy;rkonoval 2017</p>
</div>
</body>
</html>