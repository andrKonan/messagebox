<?php
if (isset($_GET["chat"])) {
	require "util.php";
	$c = get_database();
	if (authenticate_user($c)) {
		$user = get_user_by_name($c, $_COOKIE["username"]);
		$chat = mysqli_get($c, "SELECT * FROM chat WHERE id=?", "i", $_GET["chat"])->fetch_assoc();
		if (null !== $chat) {
			if (is_user_chat_admin($c, $user["id"], $_GET["chat"])) {
				mysqli_get($c, "DELETE FROM chat WHERE id=?", "i", $_GET["chat"]);
				mysqli_get($c, "DELETE FROM member WHERE chat_id=?", "i", $_GET["chat"]);
			}
		}
	}
	$c->close();
}
header('Location: index.php');
?>