<?php
require "util.php";
if (isset($_GET["username"]) && isset($_GET["password"])) {
	$c = get_database();
	$user = get_user_by_name($c, $_GET["username"]);
	if ($user && password_verify($_GET["password"], $user["token"])) {
		setcookie("token", $user["token"], time()+3600*24*7, "/");
		setcookie("username", $user["name"], time()+3600*24*7, "/");
	}
	$c->close();
}
header('Location: index.php');
?>