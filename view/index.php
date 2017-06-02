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
        <?php require_once "../config/setup.php" ?>
        <?php if (isset($_SESSION['login'])) : ?>
            <ul type="none" class="menu">
                <li><?php echo $_SESSION['login']; ?></li>
                <li><a href="logout.php">Loguot</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="galery.php">Galery</a></li>
            </ul>
        <?php else : ?>
            <ul type="none" class="menu">
                <li class="active" id="show">Login</li>
                <li><a href="register.php">Register</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="galery.php">Galery</a></li>
            </ul>
        <?php endif; ?>
        <img src="../img/default-avatar.png" class="avatar">
    </div>
    <form action="index.php" method="post" class="login_form" id="forma">
        <?php
        if ((isset($_POST['login']) && $_POST['login'] !== "") && (isset($_POST['password']) && $_POST['password'] !== "")) {
            $login = $_POST['login'];
            $find = 0;
            $errors  = array();
            $passwd = hash("whirlpool", $_POST['password']);
            $res = $pdo->query("SELECT * FROM log_pas", PDO::FETCH_ASSOC);
            foreach ($res as $row) {
                if (!strcmp($row['login'], $login)) {
                    if (!strcmp($row['password'], $passwd)) {
                        if (!strcmp($row['active'], "1")) {
                            $find = 1;
                        } else {
                            $errors[] = "/Account is not activate./";
                        }
                    } else {
                        $errors[] = "Wrong password.";
                    }
                } else {
                    $errors[] = "Wrong login.";
                }
            }
            if ($find === 1) {
                $_SESSION['login'] = $_POST['login'];
                header("location:index.php");
            }
            else {
                echo '<div style="color: red">' . array_shift($errors) . '</div>' .
                    '<script>var elem = document.getElementById("forma");' .
                    'elem.style.display = \'block\';</script>';
            }
        }
        ?>
        <div id="close"><img src="../img/close.gif"></div>
        <input type="text" name="login" value="" placeholder="login" required />
        <br />
        <input type="password" name="password" value="" placeholder="password" required />
        <br />
        <input type="submit" name="submit" value="OK">
    </form>
</div>
<script src="../js/js.js"></script>
</body>
</html>