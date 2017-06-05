<?php

require_once "sql.php";
require_once "function.php";

$pdo = new PDO('mysql:host=localhost;dbname=camagru_db', 'root', '111111');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec(SQL_CREATE_TABLE_LP);
$pdo->exec(SQL_CREATE_TABLE_IMG);
$pdo->exec(SQL_CREATE_TABLE_ACT);
$pdo->exec(SQL_CREATE_TABLE_COMMENT);
$pdo->exec(SQL_CREATE_TABLE_LIKES);

session_start();
?>