<?php 
	session_start();
	require('new-connection.php');

	if(isset($_POST['action']) && $_POST['action'] == 'register')
	{
		//call to function
		register_user($_POST);  //use actual _POST
	}

	elseif(isset($_POST['action']) && $_POST['action'] == 'login')
	{
		login_user($_POST);
	}

	if(isset($_POST['action']) && $_POST['action'] == 'post_message')
	{
		add_message($_POST['post_message']);
	}

	if(isset($_POST['action']) && $_POST['action'] == 'post_comment')
	{
		add_comment($_POST['post_comment']);
	}

	if(isset($_POST['action']) && $_POST['action'] == 'delete')
	{
		remove_message($_POST);
	}

	else //malicious navigation to process.php or someone is trying to log off!
	{
		session_destroy();
		header('location: index.php');
		die();
	}


function register_user($post)    //just a parameter called post
{
	$esc_first_name = escape_this_string($_POST['first_name']);
	$esc_last_name = escape_this_string($_POST['last_name']);
	$esc_email = escape_this_string($_POST['email']);
	$esc_password = escape_this_string($_POST['password']);
	$esc_confirm_password = escape_this_string($_POST['confirm_password']);
	// ---------- begin validation checks ------------ //
	$_SESSION['errors'] = array();

	if(empty($esc_first_name))   //post info to be validated
	{
		$_SESSION['errors'][] = "First name can't be blank!";
	}
	if(empty($esc_last_name))   //post info to be validated
	{
		$_SESSION['errors'][] = "Last name can't be blank!";
	}
	if(empty($esc_password))   //post info to be validated
	{
		$_SESSION['errors'][] = "Password field is required";
	}
	if($esc_password !== $esc_confirm_password)
	{
		$_SESSION['errors'][] = "Passwords must match!";
	}
	if(!filter_var($esc_email, FILTER_VALIDATE_EMAIL))
	{
		$_SESSION['errors'][] = "Enter a valid email address!";
	}
	// ------------ end of validation checks ------------ //

	if(count($_SESSION['errors']) > 0)   //if there are any errors
	{
		header('location: index.php');
		die();
	}
	else  //now you need to insert the data into hte database
	{
		$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at)
				VALUES ('{$esc_first_name}', '{$esc_last_name}', '{$esc_email}', '{$esc_password}', NOW(), NOW())";
		run_mysql_query($query);
		$_SESSION['success_message'] = 'User successfully created!';
		header('location: index.php');
		die();

	}
}

function login_user($post)    //just a parameter called post
{
	$esc_email = escape_this_string($_POST['email']);
	$esc_password = escape_this_string($_POST['password']);
	$query = "SELECT * FROM users WHERE users.password = '{$esc_password}' AND users.email = '{$esc_email}';";
	$user = fetch_all($query); //go and attempt to grab user with above credentials
	if(count($user) > 0)
	{
		$_SESSION['user_id']= $user[0]['id'];
		$_SESSION['first_name'] = $user[0]['first_name'];
		$_SESSION['logged_in'] = TRUE;
		header('location: main.php');
		die();
	}
	else
	{
		$_SESSION['errors'][] = "Can't find a user with those credentials";
		header('location: index.php');
		die();
	}
}

function add_message($message)
{
	$_SESSION['message_error'] = array();
	// first let's validate
	if(empty($_POST['post_message']))
	{
		$_SESSION['message_error'][] = "Please submit a message. Your message can not be blank.";
		header('location:main.php');
		die();
	}
	if(count($_SESSION['message_error']) > 0)   //if there are any errors
	{
		header('location: main.php');
		die();
	}
	else
	{
		$query = "INSERT INTO messages (user_id, message, created_at, updated_at) VALUES ('{$_SESSION['user_id']}','{$message}', NOW(), NOW())";
		run_mysql_query($query);
		$_SESSION['success'] = "Your message has been posted";
		header('location: main.php');
		die();
	}
}

function add_comment($comment)
{
		// var_dump($_POST);
		// var_dump($comment);
		// die();
	$_SESSION['comment_error'] = array();
	// first let's validate
	if(empty($_POST['post_comment']))
	{
		$_SESSION['comment_error'][] = "Please submit a comment. Your comment can not be blank.";
		header('location:main.php');
		die();
	}
	if(count($_SESSION['comment_error']) > 0)   //if there are any errors
	{
		header('location: main.php');
		die();
	}
	else
	{
		$query = "INSERT INTO comments (user_id, message_id, comment, created_at, updated_at) VALUES ('{$_SESSION['user_id']}', '{$_POST['message_id']}', '{$comment}', NOW(), NOW())";
		run_mysql_query($query);
		$_SESSION['success'] = "Your comment has been posted";
		header('location: main.php');
		die();
	}
}

function remove_message($post)
{
	$query = "DELETE FROM messages WHERE mid = {$post['delete_message']}";
	run_mysql_query($query);
	header('location: main.php');
	die();
}
?>