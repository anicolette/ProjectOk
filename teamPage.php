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
    <a href="<?php echo 'taskPage.php?&teamName='.$_GET['teamName'] ?>"> <?php echo $_GET['teamName'] ?> tasks</a><br />
    <a href="getEventsForUser.php">events</a>
</div>
<div id="teamPageOut" align="center">
    <div id="teamPageForms">

        <form action="getTeamTasksForUser.php" method="get">
		<input type="hidden" name="teamName" value="<?php echo $_GET['teamName'] ?>"/>
            <input type="hidden" name="username" value="<?php echo $_SESSION['username']?>"/>
            <input type="submit" value="View my tasks for this team!"/>
        </form>
        </br>

        <form action="getTeamTasksForUser.php" method="get">
		<input type="hidden" name="teamName" value="<?php echo $_GET['teamName'] ?>"/>
            <input type="text" name="username" placeholder="Username" maxlength="100"/>
            <input type="submit" value="View user's tasks"/>
        </form>

        <!-- <a href="<?php echo 'taskPage.php?&teamName='.$_GET['teamName'] ?>">View team tasks</a> -->
        <!-- Kind of ugly to be embedding PHP into an HTML tag like this, but we need to pass this data to these scripts  -->
        </br>
        <form id="addTaskForm" class="form" action="<?php echo 'addTask.php?&teamName='.$_GET['teamName'] ?>" method="post">
            <table id="createTaskTable">
                <caption>Add new task</caption>
                <tr>
                    <td><label for="taskName">Task name</label></td>
                    <td><input type="text" name="taskName" placeholder="Task name" maxlength="100"/></td>
                </tr>
                <tr>
                    <td><label for="daysToComplete">Days to complete</label></td>
                    <td><input type="number" name="daysToComplete" min="1" value="1"/></td>
                </tr>
                <tr>
                    <td><label for="taskDesc">Task description</label></td>
                    <td><textarea name="taskDesc" maxlength="3000" cols=50 rows=6></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit"/></br></td>
                </tr>
            </table>
        </form>
        <form class="form" action="<?php echo 'addEvent.php?&teamName='.$_GET['teamName'] ?>" method="post">
            <table id="createEventTable">
                <caption>Add new event</caption>
                <tr>
                    <td><label for="eventName">Event name</label></td>
                    <td><input type="text" name="eventName" placeholder="Event name" maxlength="100"/></td>
                </tr>
                <tr>
                    <!-- TODO: Add calendar for selecting date of event -->
                    <td><label for="dateOfEvent">Date of event</label></td>
                    <td><input type="date" name="dateOfEvent" required/></td>
                </tr>
                <tr>
                    <td><label for="eventDesc">Event description</label></td>
                    <td><textarea name="eventDesc" maxlength="3000" cols=50 rows=6></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit"/></br></td>
                </tr>
            </table>
        </form>
        </br>

	<form class="form" action=tasksByTag.php method="get">
        <table id="addMemberTable">
		<tr>
			<input type="hidden" name="teamName" value="<?php echo $_GET['teamName'] ?>"/>
			<td><label for="tag">Search by tag</label></td>
			<td><input type="text" name="tag" placeholder="Tag"/></td>
			<td><input type="submit" value="View tasks with this tag"/></td>
		</tr>
        </table>
	</form>
    </br>
    <form class="form" action="<?php echo 'addUserToTeam.php?&teamName='.$_GET['teamName'] ?>" method="post">
        <table id ="addMemberTable">
            <tr>
                <td><label for="newMember">Add new member</label></td>
                <td><input type="text" name="newMember" placeholder="New member"/></td>
                <td><input type="submit"/></br></td>
            </tr>
        </table>
    </form>

    </div></div>
</br>
<script>
    // NYI
</script>
</body>
</html>
