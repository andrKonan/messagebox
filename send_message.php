<?php
if (isset($_GET["message"]) && isset($_GET["chat"])) {
	require "util.php";
	$c = get_database();
	if (authenticate_user($c)) {
		$user = get_user_by_name($c, $_COOKIE["username"]);
		$message_text = $_GET["message"];
		if (strlen($message_text) > 0 && strlen($message_text) <= 1024) {
			if (is_user_chat_member($c, $user["id"], $_GET["chat"])) {
				mysqli_get($c, "INSERT INTO message (text, post_date, user_id, chat_id) VALUES (?, CURRENT_TIMESTAMP, ?, ?)", 'sii', $_GET["message"], $user["id"], $_GET["chat"]);
			}
		}
	}
}
$c->close();
header('Location: index.php?chat='.$_GET["chat"]);
?>