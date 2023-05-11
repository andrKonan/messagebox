<?php
if (isset($_GET["user"]) && isset($_GET["chat"])) {
	require "util.php";
	$c = get_database();
	if (authenticate_user($c)) {
		$user = get_user_by_name($c, $_COOKIE["username"]);
		if (is_user_chat_admin($c, $user["id"], $_GET["chat"])) {
			mysqli_get($c, "INSERT INTO member (user_id, chat_id, is_admin) VALUES (?, ?, 0)", 'ii', get_user_id($c, $_GET["user"]), $_GET["chat"]);
		}
	}
	$c->close();
}
header('Location: index.php?chat='.$_GET["chat"]);
?>