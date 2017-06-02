<?php require_once "../config/setup.php";?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Galery</title>
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
            <li><a href="camera.php">Camera</a></li>
        </ul>
        <img src="../img/default-avatar.png" class="avatar">
    </div>
</div>
<div class="download">
    <form action="galery.php" method="post" enctype="multipart/form-data">
        <input type="file" name="uploadfile">
        <input type="submit" value="submit"></form>
</div>


<script src="../js/js.js"></script>
</body>
</html>

<?php
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
}
//if ($_POST['submit']) {
    $uploaddir = '../foto/';
    $uploadfile = $uploaddir . basename($_FILES['uploadfile']['name']);

    if (copy($_FILES['uploadfile']['tmp_name'], $uploadfile)) {
        echo "<h3>Файл успешно загружен на сервер</h3>";
    }
//}
?>