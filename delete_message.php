<?php
require "util.php";
$c = mysqli_connect("localhost", "root", "root", "message_box");
if (authenticate_user($c)) {
	$user = get_user_by_name($c, $_COOKIE["username"]);
	if (isset($_GET["message"])) {
		$msg = mysqli_get($c, "SELECT * FROM message WHERE id=?", "i", $_GET["message"])->fetch_assoc();
		if (null !== $msg) {
			if ($msg[3] == $user["id"] || is_user_chat_admin($c, $user["id"], $_GET["chat"])) {
				mysqli_get($c, "DELETE FROM message WHERE id=?", "i", $_GET["message"]);
			}
		}
	}
}
$c->close();
header('Location: index.php?chat='.$_GET["chat"]);
?>