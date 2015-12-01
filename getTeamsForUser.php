<?php
	
	/*
		This script will load the teams in which the currently logged in user is a member.
		
		You could navigate directly to this page, or more likely call it using AJAX so you 
		can parse the JSON response and format it as you wish.
	*/

	require_once("teamFunctions.php");

	#Make sure the user is actually logged in and that they didn't just manually navigate here
	verifyLogin();

	#Get the username from the session and get the team names
	$username = $_SESSION["username"];
	echo getTeamsForUser($username);
?>
