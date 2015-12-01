<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once("teamFunctions.php");
	verifyLogin();

	if(!isset($_GET["teamName"])){	
		echo "<script>setTimeout(\"window.location='error.php'\", 100);</script>";
		#echo "Invalid team\n";
		#echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
		exit();
	}

	if(!verifyMembership($_GET["teamName"])){
		echo "<script>setTimeout(\"window.location='error.php'\", 100);</script>";
		#echo "Invalid team\n";
		#echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
		exit();
	}

	if(!isset($_POST["newMember"])){
		echo "<script>setTimeout(\"window.location='error.php'\", 100);</script>";
		#echo "Must specify new team member\n";
		#echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
		exit();
	}

	if(!verifyUser($_POST["newMember"])){
		echo "<script>setTimeout(\"window.location='error.php'\", 100);</script>";
		#echo $_POST["newMember"] . " does not exist!\n";
		#echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
		exit();
	}

	addMemberToTeam($_GET["teamName"], $_POST["newMember"]);

	$teamName = $_GET["teamName"];
	header("Location: teamPage.php?&teamName=$teamName");
?>
