<?php require_once "../config/setup.php"; ?>

<?php
if (isset($_GET['photo'])) {
    if (!strcmp($_GET['photo'], "default")) {
        $photo = '../img/default-avatar.png';
        $res = $pdo->prepare(SQL_CHANGE_PHOTO);
        $res->execute([
            ':login' => $_SESSION['login'],
            ':logo' => $photo
        ]);
        header("location:profile.php?change=logo");
    } else {
        $photo = '../foto/' . $_GET['photo'];
        $res = $pdo->prepare(SQL_CHANGE_PHOTO);
        $res->execute([
            ':login' => $_SESSION['login'],
            ':logo' => $photo
        ]);
        header("location:profile.php?change=logo");
    }
}
?> <!--change avatar php-->

<?php
if (isset($_GET['photo_del'])) {
    $flag = 0;
    $photo = '../foto/' . $_GET['photo_del'];
    $res = $pdo->prepare(SQL_GET_USER_BY_LOGIN);
    $res->execute([$_SESSION['login']]);
    foreach ($res as $row) {
        if (!strcmp($row['logo'], $photo)) {
            $flag = 1;
        }
    }
    if ($flag) {
        $pho = '../img/default-avatar.png';
        $del = $_GET['photo_del'];
        $res = $pdo->prepare(SQL_CHANGE_PHOTO);
        $res->execute([
            ':login' => $_SESSION['login'],
            ':logo' => $pho
        ]);
        $stmt = $pdo->prepare(SQL_DELETE_IMG);
        $stmt->execute([$del]);
        header("location:profile.php?change=photo");
    } else {
        $del = $_GET['photo_del'];
        $stmt = $pdo->prepare(SQL_DELETE_IMG);
        $stmt->execute([$del]);
        header("location:profile.php?change=photo");
    }
    unlink($photo);
}
?> <!--delete photo php-->

<?php
if (isset($_POST['submit'])) {
    if (isset($_POST['submit']) && (isset($_POST['old_pw']) && $_POST['old_pw'] !== "") &&
        (isset($_POST['new_pw1']) && $_POST['new_pw1'] !== "") &&
        (isset($_POST['new_pw2']) && $_POST['new_pw2'] !== "")
    ) {
        $flag = 0;
        $error = array();
        $old_pw = hash("whirlpool", $_POST['old_pw']);
        $new_pw1 = $_POST['new_pw1'];
        $new_pw2 = $_POST['new_pw2'];
        $res = $pdo->prepare(SQL_GET_USER_BY_LOGIN);
        $res->execute([$_SESSION['login']]);
        if ($new_pw1 !== $_POST['old_pw']) {
            foreach ($res as $row) {
                if (!(strcmp($row['password'], $old_pw))) {
                    $flag++;
                }
            }
            if (!$flag) {
                $error[] = "Wrong password";
            }
            if (strcmp($new_pw1, $new_pw2)) {
                $error[] = "Wrong confirm password";
            } else {
                $flag++;
            }
            if ($flag == 2) {
                $pass = hash("whirlpool", $new_pw1);
                $stmt = $pdo->prepare(SQL_UPDATE_PASSWORD);
                $stmt->execute([
                    ':login' => $_SESSION['login'],
                    ':password' => $pass
                ]);
                echo '<div style="color: green;">Your password update</div>';
            } else {
                echo '<div style="color: red;">' . array_shift($error) . '</div>';
            }
        } else {
            echo '<div style="color: red;">New and old passwords are the same</div>';
        }
    }
}
?> <!--change password php-->

<?php if (isset($_GET['change']) && $_GET['change'] == "passwd") : ?>
    <div id="password_block" class="block_pass">
        <form method="POST" action="profile.php?change=passwd" class="change_password">
            <input type="password" name="old_pw" value="" required placeholder="old password" class="in"><br>
            <input type="password" name="new_pw1" value="" required placeholder="new password" class="in"><br>
            <input type="password" name="new_pw2" value="" required placeholder="conform password" class="in"><br>
            <input type="submit" name="submit" value="Change" class="button">
        </form>
    </div> <!--change password-->
<?php endif; ?>

<?php if (isset($_GET['change']) && $_GET['change'] == "logo") : ?>
    <div id="change_logo" class="block">
        <div style="color: white; font-size: 25px"> Your photo</div>
        <?php
        $stmt = $pdo->prepare(SQL_GET_USER_IMG);
        $stmt->execute([$_SESSION['login']]);
        foreach ($stmt as $row) {
            echo '<div style="position:relative; display: inline-block;">' .
                '<img class="image" src="' . $row['path'] . '"><a href="profile.php?change=logo&photo=' . $row['name'] . '"><img src="../img/select.png" class="delete_img"></a></div>';
        }
        ?>
        <?php
        $stmt = $pdo->prepare(SQL_GET_USER_BY_LOGIN);
        $stmt->execute([$_SESSION['login']]);
        $flag = 1;
        foreach ($stmt as $row) {
            if (!strcmp($row['logo'], "../img/default-avatar.png")) {
                $flag = 0;
            }
        }
        if ($flag) {
            echo '<div class="default"><a href="profile.php?change=logo&photo=default">Set default avatar</a></div>';
        }
        ?>
    </div> <!--change avatar div-->
<?php endif; ?>

<?php if (isset($_GET['change']) && $_GET['change'] == "photo") : ?>
    <div id="delete_photo" class="block">
        <div style="color: white; font-size: 25px"> Your photo</div>
        <?php
        $stmt = $pdo->prepare(SQL_GET_USER_IMG);
        $stmt->execute([$_SESSION['login']]);
        foreach ($stmt as $row) {
            echo '<div style="position:relative; display: inline-block;">' .
                '<img class="image" src="' . $row['path'] . '"><a href="profile.php?change=photo&photo_del=' . $row['name'] . '"><img src="../img/close.gif" class="delete_img"></a></div>';
        }
        ?>
    </div> <!--delete photo div-->
<?php endif; ?>

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
        <a href="galery.php" title="home"><img src="../img/logo3.png" class="web" alt="home"></a>
    </div>
    <div class="block_menu">
        <ul type="none" class="menu">
            <li><?php echo $_SESSION['login']; ?></li>
            <li><a href="logout.php">Loguot</a></li>
            <li><a href="galery.php">Galery</a></li>
        </ul>
        <?php
        $login = $_SESSION['login'];
        $res = $pdo->query("SELECT logo FROM log_pas WHERE login = '$login'", PDO::FETCH_ASSOC);
        foreach ($res as $row) {
            echo '<a href="profile.php"><img src="' . $row['logo'] . '" class="avatar"></a>';
        }
        ?>
    </div>
</div> <!-- header div -->

<div class="profile_settings">
    <ul class="select_section" type="none">
        <li><a href="profile.php?change=passwd">Change password</a></li>
        <li><a href="profile.php?change=logo">Change avatar</a></li>
        <li><a href="profile.php?change=photo">Delete photo</a></li>
    </ul>
</div>

<div class="footer">
    <hr>
    <p>Camagru &copy;rkonoval 2017</p>
</div>

<script src="../js/js.js"></script>

</body>
</html>