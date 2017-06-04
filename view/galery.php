<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Galery</title>
<!--    <link href="../style/style.css" rel="stylesheet">-->
    <link href="../style/style.css" rel="stylesheet">
</head>
<body>
<?php require_once "../config/setup.php";
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
}
?>
<div class="header">
    <div class="logo">
        <a href="index.php" title="home"><img src="../img/couv_web.png" class="web" alt="home"></a>
    </div>
    <div class="block_menu">
        <ul type="none" cjlass="menu">
            <li><?php echo $_SESSION['login']; ?></li>
            <li><a href="logout.php">Loguot</a></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="galery.php">Galery</a></li>
            <li><a href="camera.php">Camera</a></li>
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
<!--<div class="download">-->
<!--    <form action="galery.php" method="post" enctype="multipart/form-data">-->
<!--        <input type="file" name="uploadfile">-->
<!--        <input type="submit" value="submit"></form>-->
<!--</div>-->

<div class="galery">
    <h3>All images</h3>
    <?php
        $res = $pdo->query(SQL_GET_ALL_IMG, PDO::FETCH_ASSOC);
        foreach ($res as $row) {
            echo '<img src="'.$row['path'].'">';
        }
    ?>
</div>
<div class="user_img">
    <h3><?php echo $_SESSION['login'];?>`s images</h3>
    <?php
        $stmt = $pdo->prepare(SQL_GET_USER_IMG);
        $stmt->execute([$_SESSION['login']]);
        foreach ($stmt as $row) {
            echo '<img src="'.$row['path'].'">';
        }
    ?>
</div>
<div class="footer">
    <hr>
    <p>&copy;rkonoval 2017</p>
</div>
<script src="../js/js.js"></script>
</body>
</html>

<?php
//if ($_POST['submit']) {
//    $uploaddir = '../foto/';
//    $uploadfile = $uploaddir . basename($_FILES['uploadfile']['name']);
//
//    if (copy($_FILES['uploadfile']['tmp_name'], $uploadfile)) {
//        echo "<h3>Файл успешно загружен на сервер</h3>";
//    }
//}
?>