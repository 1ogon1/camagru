<?php

require_once "setup.php";

    function get_name_img($name) {
        global $pdo;

        $stmt = $pdo->prepare(SQL_GET_IMG_TABLE);
        $stmt->execute([$name]);
        $res = $stmt->fetch(PDO::FETCH_LAZY);
        return $res[0];
    }

    function add_img_to_base($login, $name, $path) {
        global $pdo;

        $stmt = $pdo->prepare(SQL_ADD_IMAGE);
        $stmt->execute([
            $login,
            $path,
            $name
        ]);
    }

    function get_img($id) {
    	global $pdo;

    	$stmt = $pdo->prepare(SQL_GET_IMG_BY_ID);
    	$stmt->execute([$id]);
    	$img = $stmt->fetch(PDO::FETCH_LAZY);
    	return $img[0];
	}
?>