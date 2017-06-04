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
        <?php require_once "../config/setup.php" ?>
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
    <h2>Chande your logo</h2>
    <div class="download">
        <form action="profile.php" method="post" enctype="multipart/form-data">
            <input type="file" name="uploadfile">
            <input type="submit" value="submit"></form>
    </div>
</div>
<div class="footer">
    <hr>
    <p>&copy;rkonoval 2017</p>
</div>
<script src="../js/js.js"></script>
</body>
</html>

<?php
if (isset($_POST['submit']))
{
    $login = $_SESSION['login'];
    $uploaddir = '../foto/';
    $uploadfile = $uploaddir.basename($_FILES['uploadfile']['name']);

    $stmt = $pdo->prepare(SQL_SET_USER_LOGO);
    $stmt->execute([
        ':login' => $login,
        ':path' => $uploadfile
    ]);
}
//if (copy($_FILES['uploadfile']['tmp_name'], $uploadfile)) {
//    echo "<h3>Файл успешно загружен на сервер</h3>";
//}
?>