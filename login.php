<?php
	require_once("session.php");
?>

<!doctype html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">

		<title>Login</title>
	</head>
	
	<body>
		<div id="outerlogin">
		<div id="login">
		<div id="motto">login to your account</div>

		<form class="form" action="processLogin.php" method="post">
			<input type="text" name="username" placeholder="username"/></br>
			<input type="password" name="password" placeholder="password"/></br>
			<input type="submit"/></br>
		</form>
		<a href="newuser.php">Create a new account</a></div></div>
	</body>
</html>
