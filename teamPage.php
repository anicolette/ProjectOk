<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once("teamFunctions.php");
	verifyLogin();
	
	if(!isset($_GET["teamName"]) || !verifyMembership($_GET["teamName"])){	
		echo "<script>setTimeout(\"window.location='error.php'\", 100);</script>";
		#echo "Invalid team\n";
		#echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
		exit();
	}
?>

<!doctype html>
<html>
	<head>
		<title><?php echo $_GET["teamName"]; ?></title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	
	<body id="teamPageBody">
		<div id="header"><?php echo $_GET["teamName"]; ?></div>
		<div id="navteam"><a href="index.html">home</a><br/>
		<a href="profile.php">user panel</a></br>
		<a href="<?php echo 'taskPage.php?&teamName='.$_GET['teamName'] ?>">tasks</a><br />
		<a href="login.php">login</a></div>
		<div id="teamPageOut" align="center">
		<div id="teamPageForms">
		<!-- <a href="<?php echo 'taskPage.php?&teamName='.$_GET['teamName'] ?>">View team tasks</a> -->
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
		<form class="form" action="<?php echo 'addEvent.php?&teamName='.$_GET['teamName'] ?>" method="post">
			<table id="createEventTable">
				<tr>
					<td><label for="eventName">Event Name</label></td>
					<td><input type="text" name="eventName" placeholder="Event Name" maxlength="100"/></td>
				</tr>
				<tr>
					<!-- TODO: Add calendar for selecting date of event -->
					<td><label for="daysUntilEvent">Days to Complete</label></td>
					<td><input type="number" name="daysUntilEvent" min="1" value="1"/></td>
				</tr>
				<tr>
					<td><label for="eventDesc">Event Description</label></td>
					<td><textarea name="eventDesc" maxlength="3000" cols=50 rows=6></textarea></td>
				</tr>
			</table>
			<input type="submit"/></br>

		</form>
		</div></div>
		</br>
		<form action="getTeamTasksForUser.php" method="get">
			<input type="hidden" name="username" value="<?php echo $_SESSION['username']?>"/>
			<input type="hidden" name="teamName" value="<?php echo $_GET['teamName']   ?>"/>
			<input type="submit" value="View my tasks"/>
		</form>
		</br>
		<form action="getTeamTasksForUser.php" method="get">
			<input type="text" name="username" placeholder="Username" maxlength="100"/>
			<input type="hidden" name="teamName" value="<?php echo $_GET['teamName']  ?>"/>
			<input type="submit" value="View user's tasks"/>
		</form>

	</body>
</html>
