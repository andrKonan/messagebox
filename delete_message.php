<?php
if (isset($_GET["message"])) {
	require "util.php";
	$c = get_database();
	if (authenticate_user($c)) {
		$user = get_user_by_name($c, $_COOKIE["username"]);
		$msg = mysqli_get($c, "SELECT user_id FROM message WHERE id=?", "i", $_GET["message"])->fetch_assoc();
		if (null !== $msg) {
			if ($msg["user_id"] == $user["id"] || is_user_chat_admin($c, $user["id"], $_GET["chat"])) {
				mysqli_get($c, "DELETE FROM message WHERE id=?", "i", $_GET["message"]);
			}
		}
	}
	$c->close();
}
header('Location: index.php?chat='.$_GET["chat"]);
?>