<?php

require_once "sql.php";

$pdo = new PDO('mysql:host=localhost;dbname=camagru_db', 'root', '111111');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();
?>