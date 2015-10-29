<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once("teamFunctions.php");
	verifyLogin();
	
	if(!isset($_GET["teamName"]) || !verifyMembership($_GET["teamName"])){	
		echo "Invalid team\n";
		echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
		exit();
	}
?>

<!doctype html>
<html>
	<head>
		<title><?php echo $_GET["teamName"]; ?></title>
		<h1><?php echo $_GET["teamName"]; ?></h1>
	</head>
	
	<body>
		<!-- Kind of ugly to be embedding PHP into an HTML tag like this, but we need to pass this data to these scripts  -->
		<form class="form" action="<?php echo 'addUserToTeam.php?&teamName='.$_GET['teamName'] ?>" method="post">
			<label for="newMember">Add new member</label><input type="text" name="newMember" placeholder="New Member"/></br>
			<input type="submit"/></br>
		</form>
		</br>
		<form class="form" action="<?php echo 'addTask.php?&teamName='.$_GET['teamName'] ?>" method="post">
			<label for="taskName">Task Name</label><input type="text" name="taskName" placeholder="Task Name" maxlength="100"/></br>
			<label for="daysToComplete">Days to Complete</label><input type="number" name="daysToComplete" min="1" value="1"/></br>
			<label for="taskDesc">Task Description</label><textarea name="taskDesc" maxlength="3000" cols=50 rows=6></textarea></br>
			<input type="submit"/></br>
		</form>
	</body>
</html>
