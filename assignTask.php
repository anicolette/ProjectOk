<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once("teamFunctions.php");
	verifyLogin();

	if(empty($_POST["teamName"]) || empty($_POST["taskId"]) || empty($_POST["username"])  ){
		echo "Must fill out all fields\n";
		echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
		exit();
	}
	$teamName = $_POST["teamName"];
	$username = $_POST["username"];
	$taskId = $_POST["taskId"];

	echo $teamName . "</br>" . $username . "</br>" . $taskId . "</br>";
	die("Implement me!");


	try{
		#Verify that the given team name is valid (Someone could have spoofed the GET value and verify that the current user is a member of the given team
		if(!verifyTeam($teamName) || !verifyMembership($teamName, $_SESSION["username"]) || !verifyMembership($username)){
			echo "Invalid team\n";
			echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
			exit();
		}
		
		
	}catch (Exception $e){
		die($e);
	}
	
?>
