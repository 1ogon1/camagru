<?php
require_once '../config/setup.php';
if (!isset($_SESSION['login']))
    header("Location: index.php");
if (isset($_POST['image'])) {
    $upload_dir = '../foto/';
    $img = $_POST['image'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $img_name = $_SESSION['login'] . '.png';
    if (get_name_img($img_name)) {
        $counter = 1;
        while (get_name_img($counter . $img_name))
            $counter++;
        $img_name = $counter . $img_name;
    }
    $file = $upload_dir . $img_name;
    $success = file_put_contents($file, $data);
    add_img_to_base($_SESSION['login'], $img_name, $file);
    header("Location: camera.php");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Camera</title>
    <link href="../style/style.css" rel="stylesheet">
</head>
<body onload="camera()">
<div class="header">
    <div class="logo">
        <a href="galery.php" title="home"><img src="../img/logo3.png" class="web" alt="home"></a>
    </div>
    <div class="block_menu">
        <ul type="none" class="menu">
            <li><?php echo $_SESSION['login']; ?></li>
            <li><a href="logout.php">Loguot</a></li>
<!--            <li><a href="index.php">Home</a></li>-->
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
</div>
<div class="filter">
    <div onclick="create_img('../img/hat.png')" class="click"><img src="../img/hat.png"></div>
    <div onclick="create_img('../img/beard.png')" class="click"><img src="../img/beard.png"></div>
    <div onclick="create_img('../img/beard2.png')" class="click"><img src="../img/beard2.png"></div>
    <div onclick="create_img('../img/snap.png')" class="click"><img src="../img/snap.png"></div>
    <div onclick="create_img('../img/sunglases.png')" class="click"><img src="../img/sunglases.png"></div>
    <div onclick="create_back('../img/flowers.png')" class="click"><img src="../img/flowers.png"></div>
    <div onclick="create_back('../img/Vintage.png')" class="click"><img src="../img/Vintage.png"></div>
</div>
<div class="camera">
    <div class="item" id="add_img">
        <video id="video" width="320" height="240" autoplay="autoplay"></video>
        <img id="hat" src="" class="img_png">
        <img id="rama" src="" class="img_back">
    </div>
    <div class="can">
        <canvas id="canvas" width="320" height="240"></canvas>
    </div>
    <input id="button" type="submit" value="LETS DO THIS">
</div>
<script src="../js/js.js"></script>
</body>
</html>