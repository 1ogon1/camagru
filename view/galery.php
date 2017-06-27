<?php require_once "../config/setup.php";
if (!isset($_SESSION['login'])) {
	header("Location: index.php");
}
?>

<?php if (isset($_GET['admin'])) {
	$admin = $_GET['admin'];
	if ($admin === "admin") {
		header("location:admin.php");
	}
}
?> <!-- go to admin page -->

<?php
if (isset($_GET['like'])) {
	$flag = 0;
	$id = 0;
	$img = $_GET['img'];
	$page = 0;
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}
	$res = $pdo->prepare(SQL_GET_ALL_LIKES);
	$res->execute([$_GET['img']]);
	foreach ($res as $row) {
		if (!(strcmp($row['login'], $_SESSION['login']))) {
			$flag = 1;
			$id = $row['id'];
		}
	}
	if ($flag) {
		$res = $pdo->prepare(SQL_DELETE_LIKE);
		$res->execute([$id]);
	}
	if (!$flag) {
		$res = $pdo->prepare(SQL_ADD_LIKE);
		$res->execute([
			$_GET['img'],
			$_SESSION['login']
		]);
	}
	header("location:galery.php?page=$page&img=$img");
}
?> <!-- likes php -->

<?php
if (isset($_POST['add'])) {
	$stmt = $pdo->prepare(SQL_ADD_COMMENT);
	$stmt->execute([
		$_GET['img'],
		$_SESSION['login'],
		$_POST['texta']
	]);
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}
	header("location:galery.php?page=$page&img=$_GET[img]");
}
?> <!-- add comment php -->

<?php if (isset($_GET['img'])) : ?>
    <div class="show_img">
        <div class="img">
            <img src="../foto/<?php echo $_GET['img']; ?>">
        </div>
        <div class="comment">
            <div class="show_comment">
				<?php
				$res = $pdo->prepare(SQL_GET_COMMENT);
				$res->execute([$_GET['img']]);
				foreach ($res as $row) {
					echo '<p class="comment_login">' . $row['login'] . '</p><p class="comment_message">' . $row['message'] . '</p>';
				}
				?>
            </div>
            <div class="add_comment">
                <form method="post"
                      action="galery.php?page=<?php echo $_GET['page']; ?>&img=<?php echo $_GET['img']; ?>">
                    <textarea rows="3" cols="25" name="texta" placeholder="Add your comment" required></textarea><br>
                    <input type="submit" name="add" value="send">
                </form>
            </div>
        </div>
        <div class="like">
            <div>
                <a href="galery.php?page=<?php echo $_GET['page']; ?>&img=<?php echo $_GET['img']; ?>&like=set"><img
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
        <div class="hide_img">
            <a href="galery.php?page=<?php echo $_GET['page']; ?>">
                <img src="../img/close.png">
            </a>
        </div>
    </div>
<?php endif; ?> <!-- show image -->

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Galery</title>
    <link href="../style/style.css" rel="stylesheet" type="text/css">
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
            <li><a href="camera.php">Camera</a></li>
        </ul>
		<?php
		$login = $_SESSION['login'];
		$res = $pdo->query("SELECT logo FROM log_pas WHERE login = '$login'", PDO::FETCH_ASSOC);
		foreach ($res as $row) {
			echo '<a href="profile.php?page="><img src="' . $row['logo'] . '" class="avatar"></a>';
		}
		?>
    </div>
</div> <!-- header -->

<div class="galery">
    <h3>All images</h3>
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
			echo '<a href="galery.php?page=' . $num_page . '">' . $num_page . '</a>';
			$num_page++;
			$img_count -= 20;
		}
	}
	echo '</div>';
	$img_count = count($images);
	while ($i < $j && $i < $img_count) {
		$get_img = get_img($images[$i]['id']);
		echo '<div class="foto_galery"><a href="galery.php?page=' . $page . '&img=' . $get_img . '"><img src="../foto/' . $get_img . '"></a></div>';
		$i++;
	}
	?>
</div> <!-- all images -->

<div class="user_img">
    <h3><?php echo $_SESSION['login']; ?>`s images</h3>
	<?php
	$stmt = $pdo->prepare(SQL_GET_USER_IMG);
	$stmt->execute([$_SESSION['login']]);
	$i = 1;
	foreach ($stmt as $row) {
		if ($i > 9) {
			exit();
		}
		echo '<div class="foto_galery"><a href="galery.php?page=' . $page . '&img=' . $row['name'] . '"><img src="' . $row['path'] . '"></a></div>';
		$i++;
	}
	?>
</div> <!-- user images -->

<div class="footer">
    <hr>
    <p>Camagru &copy;rkonoval 2017</p>
</div>
<script src="../js/js.js"></script>

</body>
</html>