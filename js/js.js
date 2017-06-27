var img_top = 130;
var img_left = 130;
var img_width = 100;
var img_height = 100;

function camera() {
    var canvas = document.getElementById('canvas');
    var video = document.getElementById('video');
    var button = document.getElementById('button');
    var save = document.getElementById('save');
    var clear = document.getElementById('canсel');
    var hat = document.getElementById("hat");
    var rama = document.getElementById("rama");
    var center = document.getElementById('center');
    var context = canvas.getContext('2d');
    var videoStreamUrl = false;
    var captureMe = function () {
        if (!videoStreamUrl)
            alert('То-ли вы не нажали "разрешить" в верху окна, то-ли что-то не так с вашим видео стримом');
        context.drawImage(video, 0, 0, video.width, video.height);
        if (hat != null) {
            context.drawImage(hat, img_left, img_top, img_width, img_height);
            context.drawImage(rama, 0, 0, video.width, video.height);
        }

        button.style.display = 'none';
        save.style.display = 'inline-block';
        clear.style.display = 'inline-block';

        center.style.textAlign = 'center';
    };
    var save_func = function () {
        var base64dataUrl = canvas.toDataURL('image/png');
        context.setTransform(1, 0, 0, 1, 0, 0); // убираем все кастомные трансформации canvas
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'camera.php', false, 'root', '111111');
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send('image=' + base64dataUrl);

        button.style.display = 'block';
        save.style.display = 'none';
        clear.style.display = 'none';

        center.style.textAlign = 'center';
        context.clearRect(0, 0 , video.width, video.height);
    };
    var clear_canvas = function () {
        context.clearRect(0, 0 , video.width, video.height);

        button.style.display = 'block';
        save.style.display = 'none';
        clear.style.display = 'none';

        center.style.textAlign = 'center';
    };
    button.addEventListener('click', captureMe);
    save.addEventListener('click', save_func);
    clear.addEventListener('click', clear_canvas);
    navigator.getUserMedia = navigator.webkitGetUserMedia;
    window.URL.createObjectURL = window.URL.createObjectURL;
    navigator.getUserMedia({video: true}, function (stream) {
        videoStreamUrl = window.URL.createObjectURL(stream);// получаем url поточного видео
        video.src = videoStreamUrl; // устанавливаем как источник для video
    }, function () {
        console.log('что-то не так с видеостримом или пользователь запретил его использовать :P');
    });
}

function create_img(path, type) {
    if (type === 1) {
        var hat = document.getElementById("hat");
        hat.src = path;
        hat.style.display = 'block';
    }
    else if (type === 2) {
        var rama = document.getElementById("rama");
        rama.src = path;
        rama.style.display = 'block';
    }
    else if (type === 3) {
        var hat = document.getElementById("hat");
        var rama = document.getElementById("rama");
        hat.src = "";
        rama.src = "";
        hat.style.display = 'none';
        rama.style.display = 'none';
    }
}

function create_back(path) {
    var image = document.getElementById("rama");
    image.src = path;
    image.style.display = 'block';
}

document.onkeydown = function (event) {
    var img = document.getElementById("hat");
    var keyCode = event.keyCode;

    if (keyCode === 37) {
        img_left -= 5;
        img.style.left = img_left + "px";
    }
    if (keyCode === 38) {
        img_top -= 5;
        img.style.top = img_top + "px";
    }
    if (keyCode === 39) {
        img_left += 5;
        img.style.left = img_left + "px";
    }
    if (keyCode === 40) {
        img_top += 5;
        img.style.top = img_top + "px";
    }
    if (keyCode === 52) {
        img_width -= 5;
        img.style.width = img_width + "px";
    }
    if (keyCode === 54) {
        img_width += 5;
        img.style.width = img_width + "px";
    }
    if (keyCode === 56) {
        img_height += 5;
        img.style.height = img_height + "px";
    }
    if (keyCode === 50) {
        img_height -= 5;
        img.style.height = img_height + "px";
    }
};