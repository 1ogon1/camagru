function open_login() {

    var elem = document.getElementById("forma");
    elem.style.display = 'block';
}

function close_login() {
    var elem = document.getElementById("forma");
    elem.style.display = 'none';
}

// function change_passwd() {
//     var elem1 = document.getElementById("password_block");
//     var elem2 = document.getElementById("change_logo");
//     var elem3 = document.getElementById("delete_photo");
//         var style = elem1.style.display;
//         if (style === 'block') {
//             elem1.style.display = 'none';
//         }
//         else {
//             elem1.style.display = 'block';
//             elem2.style.display = 'none';
//             elem3.style.display = 'none';
//         }
// }
//
// function change_logo() {
//     var elem1 = document.getElementById("password_block");
//     var elem2 = document.getElementById("change_logo");
//     var elem3 = document.getElementById("delete_photo");
//     var style = elem2.style.display;
//     if (style === 'block') {
//         elem2.style.display = 'none';
//     }
//     else {
//         elem1.style.display = 'none';
//         elem2.style.display = 'block';
//         elem3.style.display = 'none';
//     }
// }
//
// function delete_photo() {
//     var elem1 = document.getElementById("password_block");
//     var elem2 = document.getElementById("change_logo");
//     var elem3 = document.getElementById("delete_photo");
//     var style = elem3.style.display;
//     if (style === 'block') {
//         elem3.style.display = 'none';
//     }
//     else {
//         elem1.style.display = 'none';
//         elem2.style.display = 'none';
//         elem3.style.display = 'block';
//     }
// }

function camera() {
       var canvas = document.getElementById('canvas');
       var video = document.getElementById('video');
       var button = document.getElementById('button');
       var allow = document.getElementById('allow');
       var hat = document.getElementById("hat");
       var context = canvas.getContext('2d');
       // context = hat.getContext('2d');
       var videoStreamUrl = false;
       var captureMe = function () {
           if (!videoStreamUrl)
               alert('То-ли вы не нажали "разрешить" в верху окна, то-ли что-то не так с вашим видео стримом');
           context.translate(canvas.width, 0);
           context.scale(-1, 1);
           context.drawImage(video, 0, 0, video.width, video.height);
           if (hat != null) {
               var top = hat.style.top;
               var left = hat.style.left;
               console.log(top, left);
               context.drawImage(hat, 100, 150, 100, 100);
           }
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
}

function create_img(id) {
    var image = document.getElementById(id);
    image.src='../img/hat.png';
    image.style.display = 'block';
    // image.className='img_png';
    // var obj=document.getElementById('add_img');
    // obj.appendChild(image);
//
}

function key(event) {
    var img = document.getElementById("hat");
    var keyCode = event.keyCode;
    if (keyCode === 37) {
        img.style.left -= 10+"px";
    }
    if (keyCode === 38) {
        img.style.top -= 10+"px";
    }
    if (keyCode === 39) {
        img.style.left += 10+"px";
    }
    if (keyCode === 40) {
        img.style.top += 10+"px";
    }
};