<?php require_once "../config/setup.php" ?>

<?php
if (isset($_POST['log_in'])) {
    if ((isset($_POST['login']) && $_POST['login'] !== "") && (isset($_POST['password']) && $_POST['password'] !== "")) {
        $login = $_POST['login'];
        $find = 0;
        $errors = array();
        $passwd = hash("whirlpool", $_POST['password']);
        $res = $pdo->query(SQL_GET_LOGIN, PDO::FETCH_ASSOC);
        foreach ($res as $row) {
            if (!strcmp($row['login'], $login)) {
                if (!strcmp($row['password'], $passwd)) {
                    if (!strcmp($row['active'], "1")) {
                        $find = 1;
                    } else {
                        $errors[] = "Account is not activate.";
                    }
                } else {
                    $errors[] = "Wrong password.";
                }
            }
        }
        if (!$find) {
            $errors[] = "Wrong login.";
        }
        if ($find === 1) {
            $_SESSION['login'] = $_POST['login'];
            header("location:galery.php");
        } else {
            echo '<div class="wrong">' . array_shift($errors) . '</div>' .
                '<script>var elem = document.getElementById("forma");' .
                'elem.style.display = \'block\';</script>';
        }
    }
}
?> <!-- LOGIN PHP -->

<?php
if (isset($_POST['regi_ster'])) {
    if ((isset($_POST['logon']) && $_POST['logon'] !== "") &&
        (isset($_POST['password']) && $_POST['password'] !== "") &&
        (isset($_POST['conf_password']) && $_POST['conf_password'] !== "")
    ) {
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
                $default = '../img/default-avatar.png';
                $passwd = hash("whirlpool", $_POST['password']);
                $stmt = $pdo->prepare(SQL_CREATE_USER);
                $stmt->execute([
                    $_POST['logon'],
                    $passwd,
                    $default,
                    0
                ]);
                $login = hash("md5", $_POST['logon']);
                $stmt = $pdo->prepare(SQL_ACTIVATE_LINK);
                $stmt->execute([
                    $_POST['logon'],
                    $login
                ]);
                $email = 'konovalenkoruslan@gmail.com';
                $headers = "Content-Type: text/html; charset=utf-8" . "\r\n";
                $subject = "Camagru Account Activation";
                $r1 = "<html><head><style>.button { background-color: #646464 ; border: none;color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer;}</style><head>";
                $r2 = "<body><h1>Camagru Account Activation</h1>";
                $r3 = "<article><p>Hi, {$_POST['logon']}!</p><p>Thanks for registration on <span>Camagru<span></p><p>To activate your account on site please click on button below</p>";
                $r4 = "<a href='http://localhost:8080/camagru/view/activate.php?login={$_POST['logon']}&active={$login}' class='button'>Activate</a></article>";
                $r5 = "<p>Best regards, Camagru Dev</p></body></html>";
                $message = $r1 . $r2 . $r3 . $r4 . $r5;
                mail($email, $subject, $message, $headers);
                echo '<div class="finish">Check your e-mail to finish registration</div>';

            } else {
                echo '<div style="color: red;">' . array_shift($errors) . '</div>';
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}
?> <!-- REGISTER PHP -->

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Camagru</title>
    <link href="../style/style.css" rel="stylesheet">
</head>
<body>
<div class="header">
    <div class="logo">
        <a href="index.php" title="home"><img src="../img/logo3.png" class="web" alt="home"></a>
    </div>
    <div class="block_menu">
        <ul type="none" class="menu">
            <?php if (isset($_GET['register'])) : ?>
                <li><a href="index.php">Home</a></li>
            <?php else : ?>
                <li><a href="index.php?register=true">Register</a></li>
        </ul>
        <?php endif; ?>
    </div>
</div>
<?php if (!$_SESSION['login']) : ?>
    <?php if ($_GET['register']) : ?>
        <div class="welcome">
            <h1>Welcome to Camagru</h1>
        </div>
        <div class="register_form">
            <form action="index.php?register=true" method="post" class="form">
                <input type="text" name="logon" value="" placeholder="login" class="in" required>
                <br>
                <input type="password" name="password" value="" placeholder="password" class="in" required>
                <br>
                <input type="password" name="conf_password" value="" placeholder="confirm password" class="in" required>
                <br>
                <input type="submit" name="regi_ster" value="Register" class="button">
            </form>
        </div>
    <?php else : ?>
        <?php if (isset($_GET['active'])) : ?>
            <div class="activate">
                <h2>Your account is activate</h2>
                <p>Now you can login and do something funny</p>
            </div>
        <?php else : ?>
            <div class="welcome">
                <h1>Welcome to Camagru</h1>
            </div>
        <?php endif; ?>
        <form action="index.php" method="post" class="index_login">
            <input type="text" name="login" value="" placeholder="login" required class="in">
            <br/>
            <input type="password" name="password" value="" placeholder="password" required class="in">
            <br/>
            <input type="submit" name="log_in" value="Login" class="button">
        </form>
    <?php endif; ?>
<?php endif; ?>
<div class="footer">
    <hr>
    <p>Camagru &copy;rkonoval 2017</p>
</div>
<script src="../js/js.js"></script>
</body>
</html>