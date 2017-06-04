<?php
require_once "../config/setup.php";

unset($_SESSION['login']);
header("location:index.php");
?>