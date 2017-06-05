var visible = document.getElementById("show");
var hidden = document.getElementById("close");
var hidden_img = document.getElementById("close_img");
visible.onclick = function () {
    var elem = document.getElementById("forma");
    elem.style.display = 'block';
};

hidden.onclick = function () {
    var elem = document.getElementById("forma");
    elem.style.display = 'none';
};