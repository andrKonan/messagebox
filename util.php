<?php 

function mysqli_get($connection, $question, $parameter_type, ...$parameters) {
	$statement = $connection->prepare($question);
	$statement->bind_param($parameter_type, ...$parameters);
	$statement->execute();
	return $statement->get_result();
}

function authenticate_user($c) {
	if (isset($_COOKIE["token"]) && isset($_COOKIE["username"])) {
		$user_stmt = $c->prepare("SELECT token FROM user WHERE name = ?");
		$user_stmt->bind_param('s', $_COOKIE["username"]); $user_stmt->execute(); $user_result = $user_stmt->get_result(); $user_result = $user_result->fetch_assoc();
		if ($user_result && null !== $user_result) {
			return $_COOKIE["token"] == $user_result["token"];
		} else {return false;}
	} else {return false;}
}

function get_user_by_name($c, $username) {
	return mysqli_get($c, "SELECT * FROM user WHERE name = ?", "s", $username)->fetch_assoc();
}

function get_user_id($c, $username) {
	return mysqli_get($c, "SELECT id FROM user WHERE name = ?", "s", $username)->fetch_assoc()["id"];
}

function get_user_name($c, $user_id) {
	return mysqli_get($c, "SELECT name FROM user WHERE id = ?", "s", $user_id)->fetch_assoc()["name"];
}

function get_user_chats($c, $user_id) {
	return mysqli_get($c, "SELECT chat.id, chat.name FROM chat JOIN member ON chat.id=member.chat_id WHERE member.user_id=?", "i", $user_id)->fetch_all();
}

function is_user_chat_admin($c, $user_id, $chat_id) {
	return mysqli_get($c, "SELECT is_admin FROM member WHERE user_id=? AND chat_id=?", "ii", $user_id, $chat_id)->fetch_assoc()["is_admin"];
}

function is_user_chat_member($c, $user_id, $chat_id) {
	return null !== mysqli_get($c, "SELECT id FROM member WHERE user_id=? AND chat_id=?", "ii", $user_id, $chat_id)->fetch_assoc();
}

function get_chat_name($c, $chat_id) {
	return mysqli_get($c, "SELECT name FROM chat WHERE id = ?", "s", $chat_id)->fetch_assoc()["name"];
}

function get_chat_messages($c, $chat_id) {
	return mysqli_get($c, "SELECT id, text, post_date, user_id, chat_id FROM message WHERE message.chat_id=? LIMIT 25", "i", $chat_id)->fetch_all();
}

function get_chat_members($c, $chat_id) {
	return mysqli_get($c, "SELECT user.id, user.name, member.is_admin FROM member JOIN user ON member.user_id=user.id WHERE member.chat_id=?", "i", $chat_id)->fetch_all();
}
?>