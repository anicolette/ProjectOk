<?php

/*
    This script loads all of the events for the user over all their teams
*/

    require_once("teamFunctions.php");

    #Make sure the user is actually logged in and that they didn't just manually navigate here
    verifyLogin();

    #Get the username from the session and get the team names
    $username = $_SESSION["username"];
    $teamWithEvents = json_decode(getEventsForUser($username), true);
?>

<!doctype html>
<html>
<head>
    <title>
        <?php
        echo $_GET['teamName'] . " Task List";
        ?>
    </title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body id="taskList">
<div id="header">Events</div>
	<ul style="border: 1px solid #3B3B3B;">
		<li><a href="index.html">home</a></li>
		<li><a href="profile.php">user panel</a></li>
		<li><a href="getEventsForUser.php">events</a></li>
		<ul style="float:right; list-style-type:none;">
			<li><a href="login.php">login</a></li>
			<li><a href="newuser.php">register</a></li>
		</ul>
	  </ul>
<div id="events">
    <?php
    foreach($teamWithEvents as $teamWithEvent) {
        if ($teamWithEvent[1] != []) {
            echo "<h1>" . "Team: " . $teamWithEvent[0] . "</h1></br>";
            foreach ($teamWithEvent[1] as $event) {
                // Title, Description, CreationDate, DateOf, Creator
                echo "<h2>" . $event['Title'] . "</h2>"
                    . "<p style='padding-left:20px;'>" . $event['Description'] . "</p>"
                    . "Created: "  . $event['CreationDate'] . "</br>"
                    . "Date of: " . $event['DateOf'] . "</br>"
                    . "Created by: " . $event['Creator'] . "</br>";
            }
        }
    }
    ?>
</div>
</div>
</body>
</html>
