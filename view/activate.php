<?php
require_once "../config/setup.php";
if ((isset($_GET['login']) && $_GET['login'] !== "") && (isset($_GET['active']) && $_GET['active'] !== "")) {
    $res = $pdo->query("SELECT * FROM activate", PDO::FETCH_ASSOC);
    $login = $_GET['login'];
    foreach ($res as $row) {
        if (strcmp($_GET['login'], $row['login']) == 0) {

            if (strcmp($_GET['active'], $row['login_activate']) == 0) {
                $pdo->exec("UPDATE log_pas SET active = 1 WHERE login = '$login'");
            }
        }
    }
    header("location:index.php");
}
?>