<?php
if (isset($_GET["name"])) {
	require "util.php";
	$c = get_database();
	if (authenticate_user($c)) {
		$user = get_user_by_name($c, $_COOKIE["username"]);
		$chat_name = $_GET["name"];
		if (strlen($chat_name) > 0 && strlen($chat_name) <= 32) {
			mysqli_get($c, "INSERT INTO chat (name) VALUES (?)", 's', $chat_name); $chat_id = $c->insert_id;
			mysqli_get($c, "INSERT INTO member (user_id, chat_id, is_admin) VALUES (?, ?, 1)", 'ii', $user["id"], $chat_id);
		}
	}
	$c->close();
}
header('Location: index.php?chat='.$chat_id);
?>