<?php
require "util.php";
if (isset($_GET["username"]) && isset($_GET["password"])) {
	$c = mysqli_connect("localhost", "root", "root", "message_box");
	$user = get_user_by_name($c, $_GET["username"]);
	if ($user && password_verify($_GET["password"], $user["token"])) {
		setcookie("token", $user["token"], time()+3600*24*7, "/");
		setcookie("username", $user["name"], time()+3600*24*7, "/");
	}
}
header('Location: index.php');
?>