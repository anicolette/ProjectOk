<?php
	require_once("session.php");
?>

<!doctype html>
<html>
	<head>
		<title>Login</title>
		<h1>Login to your account</h1>
	</head>
	
	<body>
		<form class="form" action="processLogin.php" method="post">
			<label for="username">Username</label><input type="text" name="username" placeholder="username"/></br>
			<label for="password">Username</label><input type="text" name="password" placeholder="password"/></br>
			<input type="submit"/></br>
		</form>
		<a href="newuser.php">Create a new account</a>
	</body>
</html>
