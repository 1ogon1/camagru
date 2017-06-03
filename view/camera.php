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
//    if (get_img_id_by_name($img_name)) {
//        $counter = 1;
//        while (get_img_id_by_name($counter.$img_name))
//            $counter++;
//        $img_name = $counter.$img_name;
//    }
    $file = $upload_dir.$img_name;
    $success = file_put_contents($file, $data);
//    add_image($_SESSION['logged_user_id'], $img_name);
//    header("Location: camera.php");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Galery</title>
    <link href="../style/style.css" rel="stylesheet">
    <style>
        body {
            color: white;
            text-align: center;
        }
        .item {
            margin: auto;
        }
        video {
            transform: scaleX(-1);
            -o-transform: scaleX(-1);
            -ms-transform: scaleX(-1);
            -moz-transform: scaleX(-1);
            -webkit-transform: scaleX(-1);
        }
    </style>
</head>
<body>

<div id="allow">▲ ▲ ▲ Разрешите использовать камеру ▲ ▲ ▲<br/> ( Сверху текущей страницы )</div>
<div class="item">
    <span> video </span>
    <video id="video" width="320" height="240" autoplay="autoplay" ></video>
</div>
<div class="item">
    <span> canvas </span>
    <canvas id="canvas" width="320" height="240" ></canvas>
</div>
<input id="button" type="submit" value="submit" />
<div><a href="galery.php" style="color: white; font-size: 20px;">Galery</a></div>

<script>
    window.onload = function () {
        var canvas = document.getElementById('canvas');
        var video = document.getElementById('video');
        var button = document.getElementById('button');
        var allow = document.getElementById('allow');
        var context = canvas.getContext('2d');
        var videoStreamUrl = false;
        var captureMe = function () {
            if (!videoStreamUrl)
                alert('То-ли вы не нажали "разрешить" в верху окна, то-ли что-то не так с вашим видео стримом');
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            context.drawImage(video, 0, 0, video.width, video.height);
            var base64dataUrl = canvas.toDataURL('image/png');
            context.setTransform(1, 0, 0, 1, 0, 0); // убираем все кастомные трансформации canvas
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'camera.php', false, 'root', '111111');
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send('image='+base64dataUrl);

        };
        button.addEventListener('click', captureMe);
        navigator.getUserMedia = navigator.webkitGetUserMedia;
        window.URL.createObjectURL = window.URL.createObjectURL;
        navigator.getUserMedia({video: true}, function (stream) {
            allow.style.display = "none";// разрешение от пользователя получено, скрываем подсказку
            videoStreamUrl = window.URL.createObjectURL(stream);// получаем url поточного видео
            video.src = videoStreamUrl; // устанавливаем как источник для video
        }, function () {
            console.log('что-то не так с видеостримом или пользователь запретил его использовать :P');
        });
    };
</script>
</body>
</html>