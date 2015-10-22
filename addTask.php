
<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once("teamFunctions.php");
	verifyLogin();

	$teamName = $_GET["teamName"];


	try{
		#Verify that the given team name is valid (Someone could have spoofed the GET value and verify that the current user is a member of the given team
		if(!verifyTeam($teamName) || !verifyMembership($teamName, $_SESSION["username"])){
			echo "Invalid team\n";
			echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
			exit();
		}
		#Get the current date
		$currentDate = date("Y-m-d");
		#Insert the task information into the team database
		addTask($teamName, $_POST["taskName"], $_POST["taskDesc"], $currentDate);
		
		header("Location: teamPage.php?&teamName=$teamName");
		
		
	}catch (Exception $e){
		die($e);
	}
	
?>
