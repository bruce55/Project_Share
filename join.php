<?php 
function dbConnect() {
	include '../protected/project_share.php';
	$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
	return $db;
}
$db = dbConnect();
$pid = $_GET['pid'];
$check = $db->query("SELECT null FROM posts_status where (pid = $pid) AND (uid = 2)");
$dup = false;
while ($row = $check->fetch()) {
	$dup = true;
}
if (!$dup) {
	$insert_status = $db->prepare("INSERT INTO posts_status (pid, uid) VALUES ($pid, 2)");
	$insert_status->execute();
	echo "inserted";
}
?>
