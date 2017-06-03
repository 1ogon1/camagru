<?php
require_once "../config/setup.php";
if ((isset($_GET['login']) && $_GET['login'] !== "") && (isset($_GET['active']) && $_GET['active'] !== "")) {
    $res = $pdo->query(SQL_GET_ACTIVE, PDO::FETCH_ASSOC);
    $login = $_GET['login'];
    foreach ($res as $row) {
        if (strcmp($_GET['login'], $row['login']) == 0) {

            if (strcmp($_GET['active'], $row['login_activate']) == 0) {
                $stmt = $pdo->prepare(SQL_ACTIVATE_USER);
                $stmt->execute([
                    ':login' => $login,
                    ':active' => 1
                ]);
                $stmt = $pdo->prepare(SQL_DELETE_ACTIVE);
                $stmt->execute([$login]);
            }
        }
    }
    header("location:index.php");
}
?>