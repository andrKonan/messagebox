<!DOCTYPE html>
<html>
<head>
	<title>Message box</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php
		require "util.php";
		$c = mysqli_connect("localhost", "root", "root", "message_box");
		if (authenticate_user($c)) {
			$user = get_user_by_name($c, $_COOKIE["username"]);
			echo "<div id='nav'>";
				echo "<div id='chats'>";
					$chats = get_user_chats($c, $user["id"]);
					echo "<div class='header'>Chats</div>";
					foreach ($chats as $chat) {
						echo "<form class='nav_chat_item'><input type='hidden' name='chat' value='".htmlspecialchars($chat[0])."'><input type='submit' value='".$chat[1]."'></form>";
					}
					echo "<form action='create_chat.php' class='add_button'><input type='text' name='name' placeholder='Create new chat'><input type='submit' value='+'></form>";
				echo "</div>";
				echo "<hr><div id='logout'><form action='logout.php' method='get'><span class='message_author'>".$_COOKIE["username"]."</span> <input type='submit' value='Log out'></form></div>";
			echo "</div>";
			echo "<div id='chat'>";
				if (isset($_GET["chat"])) {
					$messages = get_chat_messages($c, $_GET["chat"]);
					echo "<div id='messages'>";
						echo "<div class='header'>".get_chat_name($c, $_GET["chat"])."</div>";
						foreach ($messages as $message) {
							echo "<div class='message'><div class='message_text'><span class='message_author'>".get_user_name($c, $message[3])."</span><br><span class='message_text'>".htmlspecialchars($message[1])."</span> <span class='message_date'>".$message[2]."</span></div>";
							if ($message[3] == $user["id"] || is_user_chat_admin($c, $user["id"], $_GET["chat"])) {
								echo "<form class='delete_button' action='delete_message.php'><input type='hidden' name='chat' value=".$_GET["chat"]."><input type='hidden' name='message' value='".$message[0]."'><input type='submit' value='x'></form>";
							}
							echo "</div>";
						}
					echo "</div><hr>";
					echo "<form id='input_send' action='send_message.php' method='get'><input type='hidden' name='chat' value=".$_GET["chat"]."> <input type='text' maxlenght='1024' name='message' id='input_box' placeholder='Input message'> <input type='submit' value='Send message'></form>";
				}
			echo "</div>";
			echo "<div id='menu'>";
				if (isset($_GET["chat"])) {
					$members = get_chat_members($c, $_GET["chat"]);
					echo "<div id='members'>";
					echo "<div class='header'>Members</div>";
						foreach ($members as $member) {
							if ($member[2]) {$is_admin = "member_admin";} else {$is_admin = "member_user";}
							echo "<div class='member ".$is_admin."'><div class='member_name'>".$member[1]."</div>";
							if ($member[0] == $user["id"] || is_user_chat_admin($c, $user["id"], $_GET["chat"])) {
								echo "<form class='delete_button' action='remove_member.php'><input type='hidden' name='user' value=".$member[0]."><input type='hidden' name='chat' value='".$_GET["chat"]."'><input type='submit' value='x'></form>";
							}
							echo "</div>";	
						}
						if (is_user_chat_admin($c, $user["id"], $_GET["chat"])) {
							echo "<form action='add_member.php' class='add_button'><input type='text' name='user' placeholder='Add new member'><input type='hidden' name='chat' value=".$_GET["chat"]."><input type='submit' value='+'></form>";
						}
					echo "</div><hr>";
					
					if (is_user_chat_admin($c, $user["id"], $_GET["chat"])) {
						echo "<form action='delete_chat.php'><input type='hidden' name='chat' value=".$_GET["chat"]."><input type='submit' value='Delete chat'></form>";
					}
				}
			echo "</div>";
		} else {
			echo "<div id='login'><form action='login.php' method='get'><h1>Message box</h1><input type='text' name='username' placeholder='Username'><input type='password' name='password' placeholder='Password'><input type='submit' value='Log in'></form></div>";
		}
		$c->close();
	?>
</body>
</html>