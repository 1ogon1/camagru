var visible = document.getElementById("show");
var hidden = document.getElementById("close");
var password = document.getElementById("ch_password");

visible.onclick = function () {
    var elem = document.getElementById("forma");
    elem.style.display = 'block';
};

hidden.onclick = function () {
    var elem = document.getElementById("forma");
    elem.style.display = 'none';
};

password.onclick = function () {
    var elem = document.getElementById("password_block");
    elem.style.display = 'block';
};