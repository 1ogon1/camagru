<?php require_once "../config/setup.php"; ?>

<?php
if (isset($_GET['delete'])) {
	$img = $_GET['img'];
	$photo = '../foto/' . $_GET['img'];
	$res = $pdo->prepare(SQL_DELETE_IMG);
	$res->execute([$img]);
	$res = $pdo->prepare(SQL_DELETE_IMG_COMMENT);
	$res->execute([$img]);
	$res = $pdo->prepare(SQL_DELETE_IMG_LIKE);
	$res->execute([$img]);
	unlink($photo);
	header("location:?change=photo&page=$_GET[page]");
}
?> <!--delete photo php-->

<?php
if (isset($_POST['change_user'])) {
	$user_id = $_GET['user'];
	$login = $_POST['login'];
	$email = $_POST['email'];
	$active = $_POST['active'];
	$set_admin = $_POST['set_admin'];
	if (isset($_POST['password']) && $_POST['password'] != "") {
		$password = hash("whirlpool", $_POST['password']);
		$res = $pdo->prepare(SQL_CHANGE_USER_PW);
		$res->execute([
			':id' => $user_id,
			':login' => $login,
			':password' => $password,
			':email' => $email,
			':active' => $active,
			':admin' => $set_admin
		]);
	} else {
		$res = $pdo->prepare(SQL_CHANGE_USER);
		$res->execute([
			':id' => $user_id,
			':login' => $login,
			':email' => $email,
			':active' => $active,
			':admin' => $set_admin
		]);
	}
	echo '<p class="changes">Changes are saved</p>';
}
?> <!--change user php-->

<?php
if (isset($_POST['user_add'])) {
	$login = $_POST['login'];
	$email = $_POST['email'];
	$password = hash("whirlpool", $_POST['password']);
	$active = $_POST['active'];
	$set_admin = $_POST['set_admin'];
	$errors = array();
	$flag = 1;
	$res = $pdo->query(SQL_GET_LOGIN, PDO::FETCH_ASSOC);
	foreach ($res as $row) {
		if (!strcmp($row['login'], $_POST['login'])) {
			$flag = 0;
			$errors[] = "Login already exists";
		}
	}
	if ($flag) {
		$default = '../img/default-avatar.png';
		$stmt = $pdo->prepare(SQL_CREATE_USER);
		$stmt->execute([
			$login,
			$password,
			$email,
			$default,
			$active,
			$set_admin
		]);
		echo '<p class="changes">Account was created</p>';
	} else {
		echo '<p class="changes" style="color: red">' . array_shift($errors) . '</p>';
	}
}
?> <!-- change user php -->

<?php
if (isset($_GET['adm_del_img'])) {
	$id = $_GET['adm_del_img'];
	$stmt = $pdo->prepare(SQL_DELETE_COMMENT);
	$stmt->execute([$id]);
	header("location:admin.php?change=photo&page=$_GET[page]&img=$_GET[img]");
}
?> <!--delete comment php-->

<?php
if ($_POST['add_mask']) {
	$uploaddir = '../img/';
	$type = $_POST['type'];
	$uploadfile = $uploaddir . basename($_FILES['uploadfile']['name']);

	if (copy($_FILES['uploadfile']['tmp_name'], $uploadfile)) {
		$stmt = $pdo->prepare(SQL_ADD_MASKS);
		$stmt->execute([
			$uploadfile,
			$_FILES['uploadfile']['name'],
            $type
		]);
	}
	header("location:admin.php?change=pic");
}
?> <!-- download masks php -->

<?php
if (isset($_GET['delete_masks'])) {
    $stmt = $pdo->prepare(SQL_DELETE_MASK);
    $stmt->execute([$_GET['delete_masks']]);
    $path = "../img/".$_GET['delete_masks'];
    unlink($path);
    header("location:admin.php?change=pic");
}
?> <!-- delete masks php -->

<?php if (isset($_GET['change']) && $_GET['change'] == "user") : ?>
    <div id="password_block" class="block_user">
		<?php

		if (isset($_GET['user'])) {
			$user_id = $_GET['user'];
			$res = $pdo->query("SELECT * FROM log_pas WHERE id = '$user_id'", PDO::FETCH_ASSOC);
			echo '<form method="post" action="admin.php?change=user&user=' . $user_id . '" class="user_form">';
			foreach ($res as $row) {
				echo '<p style="color: white;">Login</p><input type="text" name="login" value="' . $row[login] . '" required class="in">' . '<br>' .
					'<p style="color: white;">Password</p><input type="password" name="password" value="" placeholder="enter new password" class="in">' . '<br>' .
					'<p style="color: white;">E-mail</p><input type="email" name="email"  value="' . $row[email] . '" required class="in">' . '<br>' .
					'<p style="color: white;">Active status</p><input type="text" name="active" value="' . $row[active] . '" required class="in">' . '<br>' .
					'<p style="color: white;">Admin status</p><input type="text" name="set_admin" value="' . $row[admin] . '" required class="in">' . '<br>' .
					'<input type="submit" name="change_user" value="Submit" class="button">' . '<br>';
			}
			echo '</form>';
		} // change user form

		if (isset($_GET['add_user'])) {
			echo '<form method="post" action="admin.php?change=user&add_user=ok" class="user_form">';
			echo '<p style="color: white;">Login</p><input type="text" name="login" placeholder="login" required class="in">' . '<br>' .
				'<p style="color: white;">E-mail</p><input type="email" name="email"  placeholder="example@email.com" required class="in">' . '<br>' .
				'<p style="color: white;">Password</p><input type="password" name="password"  placeholder="password" required class="in">' . '<br>' .
				'<p style="color: white;">Active status</p><input type="text" name="active" placeholder="active" required class="in">' . '<br>' .
				'<p style="color: white;">Admin status</p><input type="text" name="set_admin" placeholder="admin" required class="in">' . '<br>' .
				'<input type="submit" name="user_add" value="Add" class="button">' . '<br>';
			echo '</form>';
		} // add user form

		$res = $pdo->query(SQL_GET_LOGIN, PDO::FETCH_ASSOC);
		echo '<p style="color: white; font-size: 25px;">User list:</p>';
		echo '<ul class="user_list" type="none">';
		foreach ($res as $row) {
			echo '<li style="margin-bottom: 5px"><a href="admin.php?change=user&user=' . $row[id] . '">' . $row[login] . '</a></li>';
		}
		echo '<li style="margin-bottom: 5px"><a href="admin.php?change=user&add_user=ok">add user</a></li>';
		echo '</ul>';
		?>
    </div>
<?php endif; ?> <!-- change user div -->

<?php if (isset($_GET['change']) && $_GET['change'] == "photo") : ?>
    <div id="delete_photo" class="block">
        <div style="color: white; font-size: 25px">All users photo</div>
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
				echo '<a href="admin.php?change=photo&page=' . $num_page . '">' . $num_page . '</a>';
				$num_page++;
				$img_count -= 20;
			}
		}
		echo '</div>';
		$img_count = count($images);
		while ($i < $j && $i < $img_count) {
			$get_img = get_img($images[$i]['id']);
			echo '<div class="foto_galery"><a href="admin.php?change=photo&page=' . $page . '&img=' . $get_img . '"><img src="../foto/' . $get_img . '"></a></div>';
			$i++;
		}
		?>
    </div>
<?php endif; ?> <!-- delete photo div -->

<?php if (isset($_GET['img'])) : ?>
    <div class="show_img">
        <div class="img">
            <img src="../foto/<?php echo $_GET['img']; ?>">
        </div>
        <div class="comment_adm">
			<?php
			$res = $pdo->prepare(SQL_GET_COMMENT);
			$res->execute([$_GET['img']]);
			foreach ($res as $row) {
				echo '<div style="position: relative;"><p class="comment_login">' . $row['login'] . '</p>' .
					'<a href="admin.php?change=photo&page=' . $_GET[page] . '&img=' . $_GET[img] . '&adm_del_img=' . $row[id] . '" class="delete_img_adm" >delete</a>' .
					'<p class="comment_message">' . $row['message'] . '</p></div>';
			}
			?>
        </div>
        <div class="like">
            <div>
                <a href="admin.php?change=photo&page=<?php echo $_GET['page']; ?>&img=<?php echo $_GET['img']; ?>&like=set"><img
                            src="../img/like.png"></a>
				<?php
				$i = 0;
				$res = $pdo->prepare(SQL_GET_ALL_LIKES);
				$res->execute([$_GET['img']]);
				foreach ($res as $row) {
					if (!(strcmp($row['img'], $_GET['img']))) {
						$i++;
					}
				}
				echo $i;
				?>
            </div>
        </div>
        <div class="delete_adm">
            <a href="admin.php?change=photo&page=<?php echo $_GET['page']; ?>&img=<?php echo $_GET['img']; ?>&delete=ok">Delete</a>
        </div>
        <div class="hide_img">
            <a href="admin.php?change=photo&page=<?php echo $_GET['page']; ?>">
                <img src="../img/close.png">
            </a>
        </div>
    </div>
<?php endif; ?> <!-- show image -->

<?php if (isset($_GET['change']) && $_GET['change'] == "pic") : ?>
    <div class="mask">
        <div class="filter_adm">
			<?php
			$res = $pdo->prepare(SQL_SELECT_ALL_MASKS);
			$res->execute();
			foreach ($res as $row) {
				echo '<div class="click"><img src="' . $row[path] . '"><a href="admin.php?change=pic&delete_masks='.$row[name].'"><img src="../img/close.gif" class="delete_img_adm" style="width: 20px; height: 20px"></a></div>';
			}
			?>
        </div>
        <form method="post" action="admin.php?change=pic" enctype="multipart/form-data" style="position: absolute; top: 130px; left: 5px">
            <input type="text" name="type" placeholder="type" required class="in"><br>
            <label class="button_div">
                Add file
                <input type="file" name="uploadfile" required>
            </label><br>
            <!--            <div class="button_div">--><?php //echo $_POST['uploadfile'];?><!--</div>-->
            <input type="submit" name="add_mask" value="Download" class="button" style="top: 85px; margin-top: 20px">
        </form>
    </div>
<?php endif; ?> <!-- change masks div -->

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Administrator</title>
    <link href="../style/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="header">
    <div class="logo">
        <a href="admin.php" title="home"><img src="../img/logo3.png" class="web" alt="home"></a>
    </div>
    <div class="block_menu">
		<?php
		echo '<p style="position: absolute; right: 50px; top: 20px; color: white; font-size: 50px">ADMIN</p>';
		?>
    </div>
</div> <!-- header div -->

<div class="profile_settings">
    <ul class="select_section" type="none">
        <li><a href="admin.php?change=user">Change user</a></li>
        <li><a href="admin.php?change=photo">Delete photo</a></li>
        <li><a href="admin.php?change=pic">Change masks</a></li>
    </ul>
</div>

<div class="footer">
    <hr>
    <p>Camagru &copy;rkonoval 2017</p>
</div>

<script src="../js/js.js"></script>

</body>
</html>