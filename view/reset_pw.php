<?php require_once "../config/setup.php" ?>
<?php if (isset($_SESSION['login'])) {
	header("location:galery.php");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Camagru</title>
    <link href="../style/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="header">
    <div class="logo">
        <a href="index.php" title="home"><img src="../img/logo3.png" class="web" alt="home"></a>
    </div>
    <div class="block_menu">
        <ul type="none" class="menu">
            <li><a href="index.php">Home</a></li>
        </ul>
    </div>
</div>
<form action="reset_pw.php" method="post" class="index_login">
    <p>Enter your email</p>
    <input type="email" name="email" placeholder="example@email.com" class="in" required>
    <br>
    <input type="submit" name="submit" value="Continue" class="button">
</form>
<div class="footer">
    <hr>
    <p>Camagru &copy;rkonoval 2017</p>
</div>
<script src="../js/js.js"></script>
</body>
</html>