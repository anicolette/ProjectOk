<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	require_once("teamFunctions.php");
	verifyLogin();

	$teamName = $_GET["teamName"];

	try{
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


		echo getTeamTasksByTag($teamName, $tag);	
		
	}catch (Exception $e){
		die($e);
	}
?>
