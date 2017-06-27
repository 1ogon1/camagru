<?php
require_once "../config/setup.php";

unset($_SESSION['login']);
unset($_SESSION['id_user']);
unset($_SESSION['user_admin']);
header("location:index.php");
?>