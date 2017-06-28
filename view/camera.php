<?php require_once "../config/setup.php"; ?>

<?php if (isset($_GET['admin'])) {
	$admin = $_GET['admin'];
	if ($admin === "admin") {
		header("location:admin.php");
	}
}
?> <!-- go to admin page -->

<?php
if (!isset($_SESSION['login']))
	header("Location: index.php");
if (isset($_POST['image'])) {
	$upload_dir = '../foto/';
	$img = $_POST['image'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$img_name = $_SESSION['login'] . '.png';
	if (get_name_img($img_name)) {
		$counter = 1;
		while (get_name_img($counter . $img_name))
			$counter++;
		$img_name = $counter . $img_name;
	}
	$file = $upload_dir . $img_name;
	$success = file_put_contents($file, $data);
	add_img_to_base($_SESSION['login'], $img_name, $file);
	header("Location: camera.php");
}
?> <!-- add image php -->

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Camera</title>
    <link href="../style/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body onload="camera()">
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
</div>
<div class="camera_img">
	<?php
	$stmt = $pdo->prepare(SQL_GET_USER_IMG);
	$stmt->execute([$_SESSION['login']]);
	$i = 1;
	foreach ($stmt as $row) {
		if ($i <= 9) {
			echo '<img src="' . $row['path'] . '" style="width: 110px; height: 90px; margin-left: 5px;">';
		}
		$i++;
	}
	?>
</div> <!-- user images -->

<div class="filter">
	<?php
	$res = $pdo->prepare(SQL_SELECT_ALL_MASKS);
	$res->execute();
	foreach ($res as $row) {
		if ($row['type'] == 1) {
			echo '<div onclick="create_img(\'' . $row[path] . '\', 1)" class="click"><img src="' . $row[path] . '"></div>';
		} else if ($row['type'] == 2) {
			echo '<div onclick="create_img(\'' . $row[path] . '\', 2)" class="click"><img src="' . $row[path] . '"></div>';
		}
	}
	?>
    <div onclick="create_img('../img/reload.png', 3)" class="click"><img src="../img/reload.png"></div>
</div>
<div class="camera" id="center">
    <div class="item" id="add_img">
        <video id="video" width="320" height="240" autoplay="autoplay"></video>
        <img id="hat" src="" class="img_png">
        <img id="rama" src="" class="img_back">
    </div>
    <div class="can">
        <canvas id="canvas" width="320" height="240"></canvas>
    </div>
    <input id="button" type="submit" value="LETS DO THIS" class="button">
    <input type="submit" id="save" value="Save" class="button">
    <input type="submit" id="canсel" value="Canсel" class="button_clear">
</div>

<div class="footer">
    <hr>
    <p>Camagru &copy;rkonoval 2017</p>
</div>

<script src="../js/js.js"></script>
</body>
</html>