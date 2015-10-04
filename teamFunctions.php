<?php

	#Checks that the user is logged in and redirects to the login page if they aren't
	function verifyLogin(){
		require_once("session.php");

		if(!isset($_SESSION["username"])){
			header("Location: login.php");
			exit();
		}
	}

	#Checks that a team with the given name doesn't already exists, and creates it if there is no conflict	
	function createTeam($teamName){

		require("logindb.php");
	
		#Check both for the given name and for the name with spaces replaces with _'s	
		$checkName = $db->prepare("SELECT Name FROM TEAMS WHERE Name=:teamName OR Name=:teamNameSpaceReplaced OR Name=:teamNameSpaceRestored");
		$checkNameParam = array(":teamName" => $teamName, ":teamNameSpaceReplaced" => spaceReplace($teamName), ":teamNameSpaceRestored" => spaceRestore($teamName));
		$checkName->execute($checkNameParam);
		
		$nameRow = $checkName->fetch();
		if($nameRow[0]){
			echo "$teamName already exists!\n";
			echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
			exit();
		}

		#No name conflicts, we can go ahead and create the team, add an entry in the TEAM_MEMBERSHIP table, and create the team's database
		try{
			$createTeam = $db->prepare("INSERT INTO TEAMS (Name) VALUES (:teamName)"); #This will probably be expanded as we start recording more information about teams
			$createTeamParams = array(":teamName" => $teamName); 
			if(!$createTeam->execute($createTeamParams)){
				die("Failed to create team!");
			}

			$updateMembership = $db->prepare("INSERT INTO TEAM_MEMBERSHIP (TName, UName) VALUES ((SELECT Name FROM TEAMS WHERE TEAMS.Name=:teamName), (SELECT Name FROM USERS WHERE USERS.Name=:username))");
			$updateParams = array(":teamName" => $teamName, ":username" => $_SESSION["username"]);
			if(!$updateMembership->execute($updateParams)){
				die("Failed to update team membership!");
			}

			createTeamDatabase($teamName);
	
			header("Location: profile.php");
		} catch (Exception $e){
			die($e);
		} 
	}

	#Replace _'s with spaces
	function spaceRestore($string){
		return str_replace("_", " ", $string);
	}

	#Replace spaces with _'s (used for team name sanitizing)
	function spaceReplace($string){
		return str_replace(" ", "_", $string);
	}

	#Creates a new team database. Do not call this method except through createTeam!
	function createTeamDatabase($teamName){
		try{
			#Variables may need to be modified for security/depending on server configuration
			$dbhost = "localhost";
			$dbuser = "root";
			$dbpassword = "projectok";
			$dbname = "LOGINDB";
	
			$pdo = new PDO("mysql:host=$dbhost", $dbuser, $dbpassword);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			#Create the database
			$spaceReplacedTeamName = spaceReplace($teamName);
			
			$createQuery = "CREATE DATABASE $spaceReplacedTeamName";	
			$create = $pdo->prepare($createQuery);
			$create->execute();
		} catch (Exception $e){
			die($e);
		}
	}

	#Gets a list of all the teams where the given user is a member
	function getTeamsForUser($username){

		require("logindb.php");
		
		#Get the teams this user is a member of and put them into a list
		try{
		
			$getTeams = $db->prepare("SELECT DISTINCT TName from TEAM_MEMBERSHIP WHERE UName=:username"); #This may expand as we start storing more Team information
			$getTeams->setFetchMode(PDO::FETCH_ASSOC);
			$teamParam = array(":username" => $username);
			$getTeams->execute($teamParam);		
	
			$resultList = $getTeams->fetchall();
			echo json_encode($resultList);
		} catch(Exception $e){
			die($e);
		}
	}

?>
