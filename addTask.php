
<?php
	require_once("teamFunctions.php");
	verifyLogin();

	$teamName = $_GET["teamName"];

	require("logindb.php");

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

		#Insert the task information into the team database
		addTask($teamName, $_POST["taskName"], $_POST["taskDesc"]);
		
		header("Location: teamPage.php?&teamName=$teamName");
		
		
	}catch (Exception $e){
		die($e);
	}
	
?>
