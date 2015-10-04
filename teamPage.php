<?php
	require_once("teamFunctions.php");
	verifyLogin();
?>

<!doctype html>
<html>
	<head>
		<title><?php echo $_GET["teamName"]; ?></title>
		<h1><?php echo $_GET["teamName"]; ?></h1>
	</head>
	
	<body>
		<!-- Kind of ugly to be embedding PHP into an HTML tag like this, but we need to pass this data to addTask.php  -->
		<form class="form" action="<?php echo 'addTask.php?&teamName='.$_GET['teamName'] ?>" method="post">
			<label for="taskName">Task Name</label><input type="text" name="taskName" placeholder="Task Name"/></br>
			<label for="taskDays">Days to Complete Task</label><input type="number" name="taskDays" value="0"/></br>
			<label for="taskDesc">Task Description</label><textarea name="taskDesc" cols=50 rows=6></textarea></br>
			<input type="submit"/></br>
		</form>
	</body>
</html>
