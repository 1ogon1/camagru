<?php require_once "../config/setup.php" ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link href="../style/style.css" rel="stylesheet">
</head>
<body>
<div class="header">
    <div class="logo">
        <a href="index.php" title="home"><img src="../img/couv_web.png" class="web" alt="home"></a>
    </div>
    <div class="block_menu">
        <ul type="none" class="menu">
            <li><?php echo $_SESSION['login']; ?></li>
            <li><a href="logout.php">Loguot</a></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="galery.php">Galery</a></li>
        </ul>
        <?php
        $login = $_SESSION['login'];
        $res = $pdo->query("SELECT logo FROM log_pas WHERE login = '$login'", PDO::FETCH_ASSOC);
        foreach ($res as $row) {
            echo '<a href="profile.php"><img src="'.$row['logo'].'" class="avatar"></a>';
        }
        ?>
    </div>
</div>
<div class="profile_settings">
    <ul class="select_section" type="none">
        <li id="ch_password">Change password</li>
        <li id="logo">Change logo</li>
        <li id="delete_photo">Delete photo</li>
    </ul>
</div>
<div id="password_block">
    <form method="post" action="profile.php" class="change_password">
        <?php
        if (isset($_POST['submit'])) {
            $old_pw = hash("whirlpool", $_POST['old_pw']);
            $new_pw1 = $_POST['new_pw1'];
            $new_pw2 = $_POST['new_pw2'];
            echo '<script> var elem = document.getElementById("password_block");elem.style.display = \'block\';</script>';
        }
        ?>
        <input type="password" name="old_pw" value="" required placeholder="old password"><br>
        <input type="password" name="new_pw1" value="" required placeholder="new password"><br>
        <input type="password" name="new_pw2" value="" required placeholder="conform password"><br>
        <input type="submit" name="submit">
    </form>
</div>
<div class="footer">
    <hr>
    <p>&copy;rkonoval 2017</p>
</div>
<script src="../js/js.js"></script>
<script>
    var password = document.getElementById("ch_password");
    password.onclick = function () {
        var elem = document.getElementById("password_block");
        elem.style.display = 'block';
    };
</script>
</body>
</html>