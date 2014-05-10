<!DOCTYPE html>
<html>
<head>
	<title>Submitting</title>
</head>
<body>
	<?php 
	try{
	function dbConnect(){
		include '../protected/project_share.php';
		$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
		return $db;
	}

	if ($_POST["title"] != null) {
		$title=$_POST['title'];
		$details=$_POST['details'];
		$slots=$_POST['slots'];
		$expire=$_POST['expire'];
		$db = dbConnect();
		$insert = $db->prepare("INSERT INTO posts (title, details, init_user, slots, expire) VALUES ('$title', '$details', 1, $slots, '$expire')");
		$insert->execute();
		$getid = $db->query("SELECT LAST_INSERT_ID()");
		while ($row = $getid->fetch()) {
			$pid = $row['LAST_INSERT_ID()'];
		}

		$insert_status = $db->prepare("INSERT INTO posts_status (pid, uid, main) VALUES ($pid, 1, 1)");
		$insert_status->execute();
	}
}catch(PDOException $e) {
			echo "Error: " . $e;
		}
	?>
</body>
</html>