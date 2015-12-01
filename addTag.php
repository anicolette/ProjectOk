<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once("teamFunctions.php");
	verifyLogin();

	if(empty($_POST["teamName"]) || empty($_POST["taskId"]) || empty($_POST["tag"])  ){
		echo "Must fill out all fields\n";
		echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
		exit();
	}
	$teamName = $_POST["teamName"];
	$taskId = $_POST["taskId"];
	$tag = $_POST["tag"];

	try{
		#Verify that the given team name is valid (Someone could have spoofed the GET value and verify that the current user is a member of the given team
		if(!verifyTeam($teamName) || !isMemberOfTeam($_SESSION["username"], $teamName) ){
			echo 0;
		} else{
			addTag($teamName, $taskId, $tag);
			echo 1;
		}
		
	}catch (Exception $e){
		die($e);
	}
	
?>
