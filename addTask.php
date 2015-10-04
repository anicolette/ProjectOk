
<?php
	require_once("teamFunctions.php");
	verifyLogin();

	$teamName = $_GET["teamName"];

	require("logindb.php");

	try{
		#Verify that the given team name is valid (Someone could have spoofed the GET value)
		$nameQuery = $db->prepare("SELECT Name FROM TEAMS WHERE Names=:teamName");
		$nameParam = array(":teamName" => $teamName);
		$nameQuery->execute($nameParam);
		$nameRes = $nameQuery->fetch();
		if(!$nameRes[0]){
			echo "Invalid team\n";
			echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
			exit();
		}

		#Verify that the current user is a member of the given team
		$username = $_SESSION["username"];
		$memberQuery = $db->Prepare("SELECT UName FROM TEAM_MEMBERSHIP WHERE UName=:username AND TName=:teamName"); 
		$memberParams = array(":username" => $username, ":teamName" => $teamName);
		$memberQuery->execute($memberParams);
		$memberRes = $memberQuery->fetch();
		if(!$memberRes[0]){
			echo "Invalid team\n";
			echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
			exit();
		}

	}catch (Exception $e){
		die($e);
	}
	
?>
