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
		<h1 style="font-style:italic;"><?php echo $_GET["teamName"]; ?></h1>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	
	<body id="teamPageBody">
		<div id="teamPageForms">
		<a href="<?php echo 'taskPage.php?&teamName='.$_GET['teamName'] ?>">View team tasks</a>
		<!-- Kind of ugly to be embedding PHP into an HTML tag like this, but we need to pass this data to these scripts  -->
		<form class="form" action="<?php echo 'addUserToTeam.php?&teamName='.$_GET['teamName'] ?>" method="post">
			<table id ="addMemberTable">
				<tr>
					<td><label for="newMember">Add new member</label></td>
					<td><input type="text" name="newMember" placeholder="New Member"/></td>
				</tr>
			</table>
			<input type="submit"/></br>
		</form>
		</br>
		<form class="form" action="<?php echo 'addTask.php?&teamName='.$_GET['teamName'] ?>" method="post">
			<table id="createTaskTable">
				<tr>
					<td><label for="taskName">Task Name</label></td>
					<td><input type="text" name="taskName" placeholder="Task Name" maxlength="100"/></td>
				</tr>
				<tr>
					<td><label for="daysToComplete">Days to Complete</label></td>
					<td><input type="number" name="daysToComplete" min="1" value="1"/></td>
				</tr>
				<tr>
					<td><label for="taskDesc">Task Description</label></td>
					<td><textarea name="taskDesc" maxlength="3000" cols=50 rows=6></textarea></td>
				</tr>
			</table>
			<input type="submit"/></br>

		</form>
		</div>
	</body>
</html>
