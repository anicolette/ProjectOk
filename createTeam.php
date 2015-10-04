<?php
	require_once("teamFunctions.php");

	#Make sure the user is actually logged in and that they didn't just manually navigate here
	verifyLogin();

	#Get new team name from POST
	$teamName = $_POST["teamName"];

	#Create the team
	createTeam($teamName);

?>
