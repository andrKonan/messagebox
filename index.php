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
		$c = get_database();
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
					echo "<div id='messages'>";
						echo "<div class='header'>".get_chat_name($c, $_GET["chat"])."</div>";
						echo '<script async>
const delay = ms => new Promise(res => setTimeout(res, ms));

var last_message_id=0, prev_post_date=new Date("1970-01-01 01:00:00"), is_admin='.is_user_chat_admin($c, $user["id"], $_GET["chat"]).', self_id='.$user["id"].', chat_id='.$_GET["chat"].';

function add_message(messagesDiv, message_id, author, author_id, text, post_date) {
	post_date = new Date(post_date);
	if (post_date.getDate() != prev_post_date.getDate() || post_date.getMonth() != prev_post_date.getMonth() || post_date.getFullYear() != prev_post_date.getFullYear()) {
		date_delimiter = document.createElement("div"); date_delimiter.classList.add("message_date_delimiter"); date_delimiter.innerHTML = "<hr><span>"+(post_date.toLocaleString("default", {day: "numeric", month: "short", year: "numeric"}))+"</span><hr>";
		messagesDiv.append(date_delimiter);
	}
	prev_post_date = post_date;

	messageDiv = document.createElement("div"); messageDiv.classList.add("message"); messageTextDiv = document.createElement("div"); messageTextDiv.classList.add("message_text");
	messageDateSpan = document.createElement("span"); messageDateSpan.classList.add("message_date"); messageDateSpan.innerText = post_date.toLocaleString("default", {hour: "numeric", minute: "numeric", second: "numeric"}); messageTextDiv.append(messageDateSpan); messageTextDiv.insertAdjacentText("beforeend", " ");
	messageAuthorSpan = document.createElement("span"); messageAuthorSpan.classList.add("message_author"); messageAuthorSpan.innerText = author; messageTextDiv.append(messageAuthorSpan); messageTextDiv.insertAdjacentHTML("beforeend", "<br>");
	messageTextSpan = document.createElement("span"); messageTextSpan.classList.add("message_text"); messageTextSpan.innerText = text; messageTextDiv.append(messageTextSpan);
	messageDiv.append(messageTextDiv); 

	if (author_id == self_id || is_admin) {
		messageDeleteForm = document.createElement("form"); messageDeleteForm.classList.add("delete_button"); messageDeleteForm.action="delete_message.php";
		inputChatIdHidden = document.createElement("input"); inputChatIdHidden.type="hidden"; inputChatIdHidden.name="chat"; inputChatIdHidden.value=chat_id; messageDeleteForm.append(inputChatIdHidden);
		inputMessageIdHidden = document.createElement("input"); inputMessageIdHidden.type="hidden"; inputMessageIdHidden.name="message"; inputMessageIdHidden.value=message_id; messageDeleteForm.append(inputMessageIdHidden);
		inputSubmit = document.createElement("input"); inputSubmit.type="submit"; inputSubmit.value="x"; messageDeleteForm.append(inputSubmit);
		messageDiv.append(messageDeleteForm);
	}

	messagesDiv.append(messageDiv);
}

function load_chat() {
	const httpRequest = new XMLHttpRequest();
	
	httpRequest.onreadystatechange = () => {
		if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status === 200) {
			messagesDiv = document.getElementById("messages");
			messagesJson = JSON.parse(httpRequest.response); messagesJson.reverse();
			
			messagesJson.forEach(elem => add_message(messagesDiv, elem["message_id"], elem["author_name"], elem["user_id"], elem["text"], elem["post_date"]));
			if (messagesJson.length > 0) {
				last_message_id = messagesJson[messagesJson.length-1]["message_id"];
			};

		};
	};

	httpRequest.open("POST", "/load_messages.php?chat='.$_GET["chat"].'&last_message_id="+last_message_id, false);
	httpRequest.send();
}
(async()=>{
	while (true) {
		load_chat();
		await delay(1000);
	}
})();
</script>';
					echo "</div>";
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