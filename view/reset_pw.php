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
<?php if (isset($_GET['reset'])) : ?>
    <form action="reset_pw.php?reset=ok" method="post" class="index_login">

		<?php
		if (isset($_POST['sub_reset'])) {
			$login = $_POST['login'];
			$pw = $_POST['password'];
			$c_pw = $_POST['conf_password'];
			$flag = 0;
			if (!strcmp($pw, $c_pw)) {
				$res = $pdo->query(SQL_GET_LOGIN, PDO::FETCH_ASSOC);
				foreach ($res as $row) {
					if (!strcmp($login, $row['login'])) {
						$flag = 1;
					}
				}
				if ($flag) {
				    $hash_pw = hash("whirlpool", $pw);
				    $res = $pdo->prepare(SQL_UPDATE_PW);
				    $res->execute([
						':login' => $login,
						':password' => $hash_pw
                    ]);
					echo '<div style="color: greenyellow;">Password update</div>';
				} else {
					echo '<div style="color: red;">Wrong login</div>';
                }
			} else {
				echo '<div style="color: red;">Passwords do not match</div>';
			}
		}
		?> <!-- update password php -->

        <input type="text" name="login" value="" placeholder="login" class="in" required>
        <br>
        <input type="password" name="password" value="" placeholder="password" class="in" required>
        <br>
        <input type="password" name="conf_password" value="" placeholder="confirm password" class="in" required>
        <br>
        <input type="submit" name="sub_reset" value="Login" class="button">
    </form>
<?php else : ?>
    <form action="reset_pw.php" method="post" class="index_login">

		<?php
		if (isset($_POST['submit'])) {
			$email = $_POST['email'];
			$headers = "Content-Type: text/html; charset=utf-8" . "\r\n";
			$subject = "Camagru Reset password";
			$r1 = "<html><head><style>.button { background-color: #646464 ; border: none;color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer;}</style><head>";
			$r2 = "<body><h1>Camagru Reset password</h1>";
			$r3 = "<article><p>To reset your passwor please click on button below</p>";
			$r4 = "<a href='http://localhost:8080/camagru/view/reset_pw.php?reset=ok' class='button'>Reset</a></article>";
			$r5 = "<p>Best regards, Camagru Dev</p></body></html>";
			$message = $r1 . $r2 . $r3 . $r4 . $r5;
			mail($email, $subject, $message, $headers);
			echo '<div style="color: greenyellow;">Chack your email</div>';
		}
		?> <!-- reset password, send email php -->

        <p>Enter your email</p>
        <input type="email" name="email" placeholder="example@email.com" class="in" required>
        <br>
        <input type="submit" name="submit" value="Continue" class="button">
    </form>
<?php endif; ?>
<div class="footer">
    <hr>
    <p>Camagru &copy;rkonoval 2017</p>
</div>
<script src="../js/js.js"></script>
</body>
</html>