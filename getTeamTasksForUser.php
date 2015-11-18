<?php
	#Get all of the given user's tasks for the given team

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	require_once("teamFunctions.php");
	verifyLogin();

	$teamName = $_GET["teamName"];
	$taskUser = $_POST["taskUser"];

	try{
		#Verify that a user was provided through POST
		if(empty($_POST["taskUser"]) ){
			echo "Must fill out all fields\n";
			echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
			exit();
		}


		#Verify that the given team name is valid (Someone could have spoofed the GET value)
		$nameRes = verifyTeam($teamName);
		if(!$nameRes){
			echo "Invalid team\n";
			echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
			exit();
		}

		#Verify that the current user is a member of the given team
		$memberRes = verifyMembership($teamName);
		if(!$memberRes){
			echo "Invalid team\n";
			echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
			exit();
		}
		
		#Verify that the current user is a member of the given team
		$memberRes = verifyMembership($teamName);
		if(!$memberRes){
			echo "Invalid team\n";
			echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
			exit();
		}

		#Verify that the given user is a member of the given team
		$userRes = isMemberOfTeam($taskUser, $teamName);
		if(!$userRes){
			echo "Invalid team\n";
			echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
			exit();
		}

		echo getTasksForUser($teamName,$taskUser);	
		
	}catch (Exception $e){
		die($e);
	}
?>
