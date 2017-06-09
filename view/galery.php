<?php require_once "../config/setup.php";
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
}
?>

<?php
if (isset($_GET['like'])) {
    $flag = 0;
    $id = 0;
    $img = $_GET['img'];
    $res = $pdo->prepare(SQL_GET_ALL_LIKES);
    $res->execute([$_GET['img']]);
    foreach ($res as $row) {
        if (!(strcmp($row['login'], $_SESSION['login']))) {
            $flag = 1;
            $id = $row['id'];
        }
    }
    if ($flag) {
        $res = $pdo->prepare(SQL_DELETE_LIKE);
        $res->execute([$id]);
    }
    if (!$flag) {
        $res = $pdo->prepare(SQL_ADD_LIKE);
        $res->execute([
            $_GET['img'],
            $_SESSION['login']
        ]);
    }
    header("location:galery.php?img=$img");
}
?>

<?php
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare(SQL_ADD_COMMENT);
    $stmt->execute([
        $_GET['img'],
        $_SESSION['login'],
        $_POST['texta']
    ]);
}
?>
<?php if (isset($_GET['img'])) : ?>
    <div class="show_img">
        <div class="img">
            <img src="../foto/<?php echo $_GET['img']; ?>">
        </div>
        <div class="comment">
            <div class="show_comment">
                <?php
                $res = $pdo->prepare(SQL_GET_COMMENT);
                $res->execute([$_GET['img']]);
                foreach ($res as $row) {
                    echo '<p class="comment_login">' . $row['login'] . '</p><p class="comment_message">' . $row['message'] . '</p>';
                }
                ?>
            </div>
            <div class="add_comment">
                <form method="post" action="galery.php?img=<?php echo $_GET['img']; ?>">
                    <textarea rows="3" cols="25" name="texta" placeholder="Add your comment" required></textarea><br>
                    <input type="submit" name="add" value="send">
                </form>
            </div>
        </div>
        <div class="like">
            <div>
                <a href="galery.php?img=<?php echo $_GET['img']; ?>&like=set"><img src="../img/like.png"></a>
                <?php
                $i = 0;
                $res = $pdo->prepare(SQL_GET_ALL_LIKES);
                $res->execute([$_GET['img']]);
                foreach ($res as $row) {
                    if (!(strcmp($row['img'], $_GET['img']))) {
                        $i++;
                    }
                }
                echo $i;
                ?>
            </div>
        </div>
        <div class="hide_img">
            <a href="galery.php">
                <img src="../img/close.png">
            </a>
        </div>
    </div>
<?php endif; ?>

    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Galery</title>
        <link href="../style/style.css" rel="stylesheet" type="text/css">
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
<!--                <li><a href="index.php">Home</a></li>-->
                <li><a href="camera.php">Camera</a></li>
            </ul>
            <?php
            $login = $_SESSION['login'];
            $res = $pdo->query("SELECT logo FROM log_pas WHERE login = '$login'", PDO::FETCH_ASSOC);
            foreach ($res as $row) {
                echo '<a href="profile.php"><img src="' . $row['logo'] . '" class="avatar"></a>';
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
            echo '<div class="foto_galery"><a href="galery.php?img=' . $row['name'] . '"><img src="' . $row['path'] . '"></a></div>';
        }
        ?>
    </div>
    <div class="user_img">
        <h3><?php echo $_SESSION['login']; ?>`s images</h3>
        <?php
        $stmt = $pdo->prepare(SQL_GET_USER_IMG);
        $stmt->execute([$_SESSION['login']]);
        foreach ($stmt as $row) {
            echo '<div class="foto_galery"><a href="galery.php?img=' . $row['name'] . '"><img src="' . $row['path'] . '"></a></div>';
        }
        ?>
    </div>
    <div class="footer">
        <hr>
        <p>Camagru &copy;rkonoval 2017</p>
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