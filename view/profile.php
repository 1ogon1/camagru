<?php require_once "../config/setup.php"; ?>

<?php if (isset($_GET['admin'])) {
	$admin = $_GET['admin'];
	if ($admin === "admin") {
		header("location:admin.php");
	}
}
?> <!-- go to admin page -->

<?php
if (isset($_GET['photo'])) {
	if (!strcmp($_GET['photo'], "default")) {
		$photo = '../img/default-avatar.png';
		$res = $pdo->prepare(SQL_CHANGE_PHOTO);
		$res->execute([
			':login' => $_SESSION['login'],
			':logo' => $photo
		]);
		header("location:profile.php?change=logo");
	} else {
		$photo = '../foto/' . $_GET['photo'];
		$res = $pdo->prepare(SQL_CHANGE_PHOTO);
		$res->execute([
			':login' => $_SESSION['login'],
			':logo' => $photo
		]);
		header("location:profile.php?change=logo");
	}
}
?> <!--change avatar php-->

<?php
if (isset($_GET['photo_del'])) {
	$flag = 0;
	$photo = '../foto/' . $_GET['photo_del'];
	$res = $pdo->prepare(SQL_GET_USER_BY_LOGIN);
	$res->execute([$_SESSION['login']]);
	foreach ($res as $row) {
		if (!strcmp($row['logo'], $photo)) {
			$flag = 1;
		}
	}
	if ($flag) {
		$pho = '../img/default-avatar.png';
		$del = $_GET['photo_del'];
		$res = $pdo->prepare(SQL_CHANGE_PHOTO);
		$res->execute([
			':login' => $_SESSION['login'],
			':logo' => $pho
		]);
		$stmt = $pdo->prepare(SQL_DELETE_IMG);
		$stmt->execute([$del]);
		$res = $pdo->prepare(SQL_DELETE_IMG_COMMENT);
		$res->execute([$del]);
		$res = $pdo->prepare(SQL_DELETE_IMG_LIKE);
		$res->execute([$del]);
		header("location:profile.php?change=photo");
	} else {
		$del = $_GET['photo_del'];
		$stmt = $pdo->prepare(SQL_DELETE_IMG);
		$stmt->execute([$del]);
		$res = $pdo->prepare(SQL_DELETE_IMG_COMMENT);
		$res->execute([$del]);
		$res = $pdo->prepare(SQL_DELETE_IMG_LIKE);
		$res->execute([$del]);
		header("location:profile.php?change=photo");
	}
	unlink($photo);
}
?> <!--delete photo php-->

<?php
if (isset($_POST['submit'])) {
	if (isset($_POST['submit']) && (isset($_POST['old_pw']) && $_POST['old_pw'] !== "") &&
		(isset($_POST['new_pw1']) && $_POST['new_pw1'] !== "") &&
		(isset($_POST['new_pw2']) && $_POST['new_pw2'] !== "")
	) {
		$flag = 0;
		$error = array();
		$old_pw = hash("whirlpool", $_POST['old_pw']);
		$new_pw1 = $_POST['new_pw1'];
		$new_pw2 = $_POST['new_pw2'];
		$res = $pdo->prepare(SQL_GET_USER_BY_LOGIN);
		$res->execute([$_SESSION['login']]);
		if ($new_pw1 !== $_POST['old_pw']) {
			foreach ($res as $row) {
				if (!(strcmp($row['password'], $old_pw))) {
					$flag++;
				}
			}
			if (!$flag) {
				$error[] = "Wrong password";
			}
			if (strcmp($new_pw1, $new_pw2)) {
				$error[] = "Wrong confirm password";
			} else {
				$flag++;
			}
			if ($flag == 2) {
				$pass = hash("whirlpool", $new_pw1);
				$stmt = $pdo->prepare(SQL_UPDATE_PASSWORD);
				$stmt->execute([
					':login' => $_SESSION['login'],
					':password' => $pass
				]);
				echo '<div style="color: green;">Your password update</div>';
			} else {
				echo '<div style="color: red;">' . array_shift($error) . '</div>';
			}
		} else {
			echo '<div style="color: red;">New and old passwords are the same</div>';
		}
	}
}
?> <!--change password php-->

<?php
if ($_POST['download']) {
	$uploaddir = '../foto/';
	$uploadfile = $uploaddir . basename($_FILES['uploadfile']['name']);

	if (copy($_FILES['uploadfile']['tmp_name'], $uploadfile)) {
		$stmt = $pdo->prepare(SQL_ADD_IMAGE);
		$stmt->execute([
			$_SESSION['login'],
			$uploadfile,
			$_FILES['uploadfile']['name']
		]);
	}
	header("location:profile.php?change=logo");
}
?> <!-- download photo php -->

<?php
if (isset($_GET['photo_del_adm'])) {
	$flag = 0;
	$photo = '../foto/' . $_GET['photo_del'];
	$res = $pdo->prepare(SQL_GET_USER_BY_LOGIN);
	$res->execute([$_SESSION['login']]);
	foreach ($res as $row) {
		if (!strcmp($row['logo'], $photo)) {
			$flag = 1;
		}
	}
	if ($flag) {
		$pho = '../img/default-avatar.png';
		$del = $_GET['photo_del'];
		$res = $pdo->prepare(SQL_CHANGE_PHOTO);
		$res->execute([
			':login' => $_SESSION['login'],
			':logo' => $pho
		]);
		$stmt = $pdo->prepare(SQL_DELETE_IMG);
		$stmt->execute([$del]);
		$res = $pdo->prepare(SQL_DELETE_IMG_COMMENT);
		$res->execute([$del]);
		$res = $pdo->prepare(SQL_DELETE_IMG_LIKE);
		$res->execute([$del]);
		header("location:profile.php?change=photo_adm");
	} else {
		$del = $_GET['photo_del_adm'];
		$stmt = $pdo->prepare(SQL_DELETE_IMG);
		$stmt->execute([$del]);
		$res = $pdo->prepare(SQL_DELETE_IMG_COMMENT);
		$res->execute([$del]);
		$res = $pdo->prepare(SQL_DELETE_IMG_LIKE);
		$res->execute([$del]);
		header("location:profile.php?change=photo_adm");
	}
	unlink($photo);
}
?> <!--delete photo php by adm-->

<?php if (isset($_GET['change']) && $_GET['change'] == "passwd") : ?>
    <div id="password_block" class="block_pass">
        <form method="POST" action="profile.php?change=passwd" class="change_password">
            <input type="password" name="old_pw" value="" required placeholder="old password" class="in"><br>
            <input type="password" name="new_pw1" value="" required placeholder="new password" class="in"><br>
            <input type="password" name="new_pw2" value="" required placeholder="conform password" class="in"><br>
            <input type="submit" name="submit" value="Change" class="button">
        </form>
    </div>
<?php endif; ?> <!-- change password div -->

<?php if (isset($_GET['change']) && $_GET['change'] == "logo") : ?>
    <div id="change_logo" class="block">
        <div style="color: white; font-size: 25px"> Your photo</div>
		<?php
		$page = 1;
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
		$res = $pdo->prepare(SQL_GET_USER_IMG);
		$res->execute([$_SESSION['login']]);
		$images = $res->fetchAll();
		$img_count = count($images);
		$i = $page * 20 - 20;
		$j = $page * 20;
		$num_page = 1;
		echo '<div class="page">';
		if ($img_count > 20) {
			echo '<p style=" display: inline-block; color: white; position: relative; bottom: 0px; right: 10px;">Select page</p>';
			while ($img_count > 0) {
				echo '<a href="profile.php?change=logo&page=' . $num_page . '">' . $num_page . '</a>';
				$num_page++;
				$img_count -= 20;
			}
		}
		echo '</div>';
		$img_count = count($images);
		while ($i < $j && $i < $img_count) {
			$get_img = get_img($images[$i]['id']);
			echo '<div style="position:relative; display: inline-block;">' .
				'<img class="image" src="../foto/' . $get_img . '"><a href="profile.php?change=logo&photo=' . $get_img . '"><img src="../img/select.png" class="delete_img"></a></div>';
			$i++;
		}
		?>
    </div>
    <div class="set_avatar">
        <div style="color: white; font-size: 25px; margin-left: 5px">Download file from computer</div>
        <div class="download">
            <form action="profile.php?change=logo" method="POST" enctype="multipart/form-data">
                <input type="file" name="uploadfile">
                <input type="submit" name="download" value="Download">
            </form>
        </div>
		<?php
		$flag = 1;
		foreach ($stmt as $row) {
			if (!strcmp($row['logo'], "../img/default-avatar.png")) {
				$flag = 0;
			}
		}
		if ($flag) {
			echo '<div class="default"><a href="profile.php?change=logo&photo=default">Set default avatar</a></div>';
		}
		?>
    </div>
<?php endif; ?> <!-- change avatar div -->

<?php if (isset($_GET['change']) && $_GET['change'] == "photo") : ?>
    <div id="delete_photo" class="block">
        <div style="color: white; font-size: 25px"> Your photo</div>
		<?php
		$page = 1;
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
		$res = $pdo->prepare(SQL_GET_USER_IMG);
		$res->execute([$_SESSION['login']]);
		$images = $res->fetchAll();
		$img_count = count($images);
		$i = $page * 20 - 20;
		$j = $page * 20;
		$num_page = 1;
		echo '<div class="page">';
		if ($img_count > 20) {
			echo '<p style=" display: inline-block; color: white; position: relative; bottom: 0px; right: 10px;">Select page</p>';
			while ($img_count > 0) {
				echo '<a href="profile.php?change=photo&page=' . $num_page . '">' . $num_page . '</a>';
				$num_page++;
				$img_count -= 20;
			}
		}
		echo '</div>';
		$img_count = count($images);
		while ($i < $j && $i < $img_count) {
			$get_img = get_img($images[$i]['id']);
			echo '<div style="position:relative; display: inline-block;">' .
				'<img class="image" src="../foto/' . $get_img . '"><a href="profile.php?change=photo&photo_del=' . $get_img . '"><img src="../img/close.gif" class="delete_img"></a></div>';
			$i++;
		}
		?>
    </div>
<?php endif; ?> <!-- delete photo div -->

<?php if (isset($_GET['change']) && $_GET['change'] == "photo_adm") : ?>
    <div id="delete_photo" class="block">
        <div style="color: white; font-size: 25px"> All users photo</div>
		<?php
		$page = 1;
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
		$res = $pdo->query("SELECT * FROM images ORDER BY id DESC");
		$res->execute();
		$images = $res->fetchAll();
		$img_count = count($images);
		$i = $page * 20 - 20;
		$j = $page * 20;
		$num_page = 1;
		echo '<div class="page">';
		if ($img_count > 20) {
			echo '<p style=" display: inline-block; color: white; position: relative; bottom: 0px; right: 10px;">Select page</p>';
			while ($img_count > 0) {
				echo '<a href="profile.php?change=photo_adm&page=' . $num_page . '">' . $num_page . '</a>';
				$num_page++;
				$img_count -= 20;
			}
		}
		echo '</div>';
		$img_count = count($images);
		while ($i < $j && $i < $img_count) {
			$get_img = get_img($images[$i]['id']);
			echo '<div style="position:relative; display: inline-block;">' .
				'<img class="image" src="../foto/' . $get_img . '"><a href="profile.php?change=photo_adm&photo_del_adm=' . $get_img . '"><img src="../img/close.gif" class="delete_img"></a></div>';
			$i++;
		}
		?>

    </div>
<?php endif; ?> <!--delete photo div by adm-->

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link href="../style/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="header">
    <div class="logo">
        <a href="galery.php" title="home"><img src="../img/logo3.png" class="web" alt="home"></a>
    </div>
    <div class="block_menu">
        <ul type="none" class="menu">
            <li><?php echo $_SESSION['login']; ?></li>
            <li><a href="logout.php">Loguot</a></li>
            <li><a href="galery.php">Galery</a></li>
        </ul>
		<?php
		$login = $_SESSION['login'];
		$res = $pdo->query("SELECT logo FROM log_pas WHERE login = '$login'", PDO::FETCH_ASSOC);
		foreach ($res as $row) {
			echo '<a href="profile.php"><img src="' . $row['logo'] . '" class="avatar"></a>';
		}
		?>
    </div>
</div> <!-- header div -->

<div class="profile_settings">
    <ul class="select_section" type="none">
        <li><a href="profile.php?change=passwd">Change password</a></li>
        <li><a href="profile.php?change=logo">Change avatar</a></li>
        <li><a href="profile.php?change=photo">Delete photo</a></li>
		<?php if ($_SESSION['user_admin'] == 1) : ?>
            <li><a href="profile.php?change=photo_adm">See all photo</a></li>
		<?php endif; ?>
    </ul>
</div>

<div class="footer">
    <hr>
    <p>Camagru &copy;rkonoval 2017</p>
</div>

<script src="../js/js.js"></script>

</body>
</html>