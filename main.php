<?php

session_start();
echo "Welcome to the Wall, {$_SESSION['first_name']}!       ";
echo "<a href='process.php'>Click to log off </a>";
require('new-connection.php');

$query = "SELECT users.id, users.first_name, users.last_name, messages.id as 'mid', messages.created_at, messages.message FROM users LEFT JOIN messages ON users.id = messages.user_id GROUP BY created_at DESC;";
$posted_messages = fetch_all($query);
$query2 = "SELECT user_id, message_id, comment, comments.created_at, users.first_name, users.last_name FROM comments JOIN users ON comments.user_id = users.id GROUP BY created_at ASC;";
$posted_comments = fetch_all($query2);
?>

<html>
<head>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<title>THE WALL MAIN</title>
</head>
<body>
	<div class="container">
		<?php
			if(isset($_SESSION['success']))
			{
				echo "<h2>{$_SESSION['success']}</h2>";
				unset($_SESSION['success']);
			}
		?>
		<h1>Post a message</h1>
		<form action='process.php' method='post'>
			<textarea rows='4' cols='50' name='post_message'></textarea><br>
			<input type='submit' value='Post message'>
			<input type='hidden' name='action' value='post_message'>
		</form>
		<ul>
			<?php
				foreach($posted_messages as $posted_message)
				{
					// var_dump($posted_message);
					// var_dump($_SESSION);
					// die();
					if($posted_message['message'] !== null)
					{
						echo "<h4>{$posted_message['first_name']} {$posted_message['last_name']} - {$posted_message['created_at']}</h4><br>{$posted_message['message']}
							<form action='process.php' method='post'>
							<textarea rows='1' cols='40' name='post_comment'></textarea><br>
							<input type='submit' value='Post comment'>
							<input type='hidden' name='action' value='post_comment'>
							<input type='hidden' name='message_id' value='{$posted_message['mid']}'>
							</form>";

						if($_SESSION['user_id'] == $posted_message['id'])
						{
						echo "<form action='process.php' method='post'>
							<input type='submit' value='Delete message'>
							<input type='hidden' name='action' value='delete'>
							<input type='hidden' name='delete_message' value='{$posted_message['mid']}'>
							</form>";
						}

						foreach($posted_comments as $posted_comment)
						{

							if($posted_comment['message_id'] == $posted_message['mid'])
							{
								echo "''{$posted_comment['comment']}'' commented by {$posted_comment['first_name']} {$posted_comment['last_name']} at {$posted_comment['created_at']}<br>";
							}
						}  
					echo "<hr>";
					}
					// foreach($posted_comments as $posted_comment)
					// {
					// 	if($posted_comment['message_id'] == $posted_message['id'])
					// 	{
					// 		echo "<li>{$posted_comment['comment']}</li>";
					// 	}
					// }
				}
			?>
		</ul>
	</div>
</body>
</html>