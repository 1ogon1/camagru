var visible = document.getElementById("show");
var hidden = document.getElementById("close");
visible.onclick = function () {
    var elem = document.getElementById("forma");
    elem.style.display = 'block';
}
hidden.onclick = function () {
    var elem = document.getElementById("forma");
    elem.style.display = 'none';
}