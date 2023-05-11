<?php
if (isset($_GET["user"]) && isset($_GET["chat"])) {
	require "util.php";
	$c = get_database();
	if (authenticate_user($c)) {
		$user = get_user_by_name($c, $_COOKIE["username"]);
		$member = mysqli_get($c, "SELECT id, user_id, chat_id FROM member WHERE user_id=? AND chat_id=?", "ii", $_GET["user"], $_GET["chat"])->fetch_assoc();
		if (null !== $member) {
			if ($member["user_id"] == $user["id"] || is_user_chat_admin($c, $user["id"], $_GET["chat"])) {
				mysqli_get($c, "DELETE FROM member WHERE id=?", "i", $member["id"]);
			}
		}
	}
	$c->close();
}
header('Location: index.php');
?>