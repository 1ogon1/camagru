<?php

require_once "sql.php";
require_once "function.php";

$host = 'localhost';
$root = 'root';
$pass = '111111';
try {

	$pdo = new PDO("mysql:host=$host", $root, $pass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo $e->getMessage();
}

$pdo->exec(SQL_CREATE_DATABASE);
$pdo->exec("use camagru_db");

$pdo->exec(SQL_CREATE_TABLE_LP);
$pdo->exec(SQL_CREATE_TABLE_IMG);
$pdo->exec(SQL_CREATE_TABLE_ACT);
$pdo->exec(SQL_CREATE_TABLE_LIKES);
$pdo->exec(SQL_CREATE_TABLE_MASKS);
$pdo->exec(SQL_CREATE_TABLE_COMMENT);

$stmt = $pdo->prepare(SQL_SELECT_ALL_MASKS);
$stmt->execute();
if ($stmt->rowCount() === 0) {
	$pdo->exec(SQL_ADD_MASK_1);
	$pdo->exec(SQL_ADD_MASK_2);
	$pdo->exec(SQL_ADD_MASK_3);
	$pdo->exec(SQL_ADD_MASK_4);
	$pdo->exec(SQL_ADD_MASK_5);
	$pdo->exec(SQL_ADD_MASK_6);
}

session_start();
?>