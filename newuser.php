<!doctype html>
<html>
	<head>
		<title>New User</title>
		<h1>Create a new account</h1>
	</head>

	<body>
		<form class="form" action="adduser.php" method="post">
			<label for="username">Username</label><input type="text" id="username" name="username" placeholder="username" /></br>
			<label for="email">Email</label><input type="text" id="email" name="email" placeholder="email" /></br>
			<label for="password">Password</label><input type="password" id="password" name="password" placeholder="password" /></br>
			<label for="repeatpass">Repeat Password</label><input type="password" id="repeatpass" name="repeatpass" placeholder="repeat password" /></br>
			<input type="submit" />
		</form>
	</body>
</html>
