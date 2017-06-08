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
    $img_name = $_SESSION['login'].'.png';
    if (get_name_img($img_name)) {
        $counter = 1;
        while (get_name_img($counter.$img_name))
            $counter++;
        $img_name = $counter.$img_name;
    }
    $file = $upload_dir.$img_name;
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
<body onload="camera()" onkeydown="key(e)">
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
<div onclick="create_img('hat')" class="click"><img src="../img/hat.png"></div>
<div class="item" id="add_img">
    <video id="video" width="320" height="240" autoplay="autoplay" ></video>
    <img id="hat" src="" class="img_png">
</div>
<div id="allow"></div>
<div class="can">
    <canvas id="canvas" width="320" height="240" ></canvas>
</div>
<input id="button" type="submit" value="LETS DO THIS" style="position:absolute; top: 400px; right: 100px;">
<script src="../js/js.js"></script>
<script>
    //    window.onload = function () {
    //        var canvas = document.getElementById('canvas');
    //        var video = document.getElementById('video');
    //        var button = document.getElementById('button');
    //        var allow = document.getElementById('allow');
    //        var context = canvas.getContext('2d');
    //        var videoStreamUrl = false;
    //        var captureMe = function () {
    //            if (!videoStreamUrl)
    //                alert('То-ли вы не нажали "разрешить" в верху окна, то-ли что-то не так с вашим видео стримом');
    //            context.translate(canvas.width, 0);
    //            context.scale(-1, 1);
    //            context.drawImage(video, 0, 0, video.width, video.height);
    //            var base64dataUrl = canvas.toDataURL('image/png');
    //            context.setTransform(1, 0, 0, 1, 0, 0); // убираем все кастомные трансформации canvas
    //            var xhr = new XMLHttpRequest();
    //            xhr.open('POST', 'camera.php', false, 'root', '111111');
    //            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    //            xhr.send('image='+base64dataUrl);
    //
    //        };
    //        button.addEventListener('click', captureMe);
    //        navigator.getUserMedia = navigator.webkitGetUserMedia;
    //        window.URL.createObjectURL = window.URL.createObjectURL;
    //        navigator.getUserMedia({video: true}, function (stream) {
    //            allow.style.display = "none";// разрешение от пользователя получено, скрываем подсказку
    //            videoStreamUrl = window.URL.createObjectURL(stream);// получаем url поточного видео
    //            video.src = videoStreamUrl; // устанавливаем как источник для video
    //        }, function () {
    //            console.log('что-то не так с видеостримом или пользователь запретил его использовать :P');
    //        });
    //    };
</script>
</body>
</html>