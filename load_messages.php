<?php 
// $messages = get_chat_messages($c, $_GET["chat"]);
// $previous_datetime = "1970-01-01 01:00:00";
// echo "<div id='messages'>";
// echo "<div class='header'>".get_chat_name($c, $_GET["chat"])."</div>";
// foreach ($messages as $message) {
// 	$message_post_datetime = new DateTimeImmutable($message[2]); $message_post_date = $message_post_datetime->format("Y-m-d"); $message_post_time = $message_post_datetime->format("H:i");
// 	if (strcmp(date('Y-m-d', strtotime($message_post_date)), date('Y-m-d', strtotime($previous_datetime))) != 0) {
// 		echo "<div class='message_date_delimiter'><hr><span>".date('Y-m-d', strtotime($message_post_date))."</span><hr></div>";
// 		$previous_datetime = $message_post_date;
// 	}
// 	echo "<div class='message'><div class='message_text'><span class='message_date'>".$message_post_time."</span> <span class='message_author'>".get_user_name($c, $message[3])."</span><br><span class='message_text'>".htmlspecialchars($message[1])."</span></div>";
// 	if ($message[3] == $user["id"] || is_user_chat_admin($c, $user["id"], $_GET["chat"])) {
// 		echo "<form class='delete_button' action='delete_message.php'><input type='hidden' name='chat' value=".$_GET["chat"]."><input type='hidden' name='message' value='".$message[0]."'><input type='submit' value='x'></form>";
// 	}
// 	echo "</div>";
// }
// echo "</div><hr>";

if (isset($_GET["chat"])) {
	require "util.php";
	$c = get_database();
	if (authenticate_user($c)) {
		$messages = get_chat_messages($c, $_GET["chat"]);
		header('Content-Type: application/json');
		echo json_encode($messages);
	}
}
?>