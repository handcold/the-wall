<?php
	session_start();
?>

<html>
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

	<title> The Wall </title>
	<style type="text/css">
		*
		{
			font-family:sans-serif;
		}
		.error{
			color:red;
		}
		.success{
			color:green;
		}
		.container-fluid{
			width:1000px;
			display:inline-block;
		}

		#registration{
			width:350px;
			display:inline-block;
			margin-right: 100px;
		}

		#login{
			width:350px;
			display:inline-block;
			vertical-align:top;
			margin-left: 100px;
		}

		h2{
			text-align:center;
		}

	</style>
</head>
<body>
	<div class="container-fluid">
		<h1>The Wall</h1>
		<?php
			if(isset($_SESSION['errors']))
			{
				foreach ($_SESSION['errors'] as $error)
				{
					echo "<p class='error'>{$error} </p>";

				}
				unset($_SESSION['errors']);
			}
			if(isset($_SESSION['success_message']))
			{
				// var_dump($_SESSION['success_message']);
				echo "<p class='success'>{$_SESSION['success_message']} </p>";
				unset($_SESSION['success_message']);
			}
		?>
		<div id="registration">
			<h2>Registration</h2>
			<form action='process.php' method='post' class='form-horizontal'>
				<input type='hidden' name='action' value='register'>
				First name: <input type='text' class="form-control" name='first_name'><br>
				Last Name: <input type='text' class="form-control" name='last_name'><br>
				Email: <input type='text' class="form-control" name='email'><br>
				Password: <input type='password' class="form-control" name='password'><br>
				Confirm password: <input type='password' class="form-control" name='confirm_password'><br>
				<input type='submit' class="btn btn-primary btn-lg btn-block" value='register'>
			</form>
		</div>
		<div id="login">
			<h2>Login</h2>
			<form action='process.php' method='post' class='form-horizontal'>
				<input type='hidden' name='action' value='login'>
				Email address: <input type='text' class="form-control" name='email'><br>
				Password: <input type='password' class="form-control" name='password'><br>
				<input type='submit' class="btn btn-primary btn-lg btn-block" value='Login'>
			</form>
		</div>
	</div>
</body>
</html>