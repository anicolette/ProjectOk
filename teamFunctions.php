<?php

	#Checks that the user is logged in and redirects to the login page if they aren't
	function verifyLogin(){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		require_once("session.php");

		if(!isset($_SESSION["username"])){
			header("Location: login.php");
			exit();
		}
	}

	#Verify that the given username exists
	function verifyUser($username){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		require("logindb.php");

		try{
			$userQuery = $db->prepare("SELECT Name FROM USERS WHERE Name=:username");
			$nameParam = array(":username" => $username);
			$userQuery->execute($nameParam);
			$userRes = $userQuery->fetch();

			if(!$userRes){
				return 0;
			}
			return 1;
		} catch (Exception $e){
			die($e);
		}

	}

	#Verifies that the given team exists
	function verifyTeam($teamName){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
		try{
			require("logindb.php");

			$nameQuery = $db->prepare("SELECT Name FROM TEAMS WHERE Name=:teamName");
			$nameParam = array(":teamName" => $teamName);
			$nameQuery->execute($nameParam);
			$nameRes = $nameQuery->fetch();
			if(!$nameRes){
				return 0;
			}
			return 1;
		} catch(Exception $e){
			die($e);
		}
	}

	#Checks that the currently logged in user is a member of the given team
	function verifyMembership($teamName){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		verifyLogin();

		$username = $_SESSION["username"];
	
		return isMemberOfTeam($username, $teamName);

	}

	#Checks that the given user is a member of the given team
	function isMemberOfTeam($username, $teamName){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		require("logindb.php");
	
		try{	
			$memberQuery = $db->prepare("SELECT UName FROM TEAM_MEMBERSHIP WHERE UName=:username AND TName=:teamName");
			$memberParams = array(":username" => $username, ":teamName" => $teamName);
			$memberQuery->execute($memberParams);
			$res = $memberQuery->fetch();
			if(!$res){
				return 0;
			}
			return 1;
		} catch (Exception $e){
			die($e);
		}
	}

	#Adds a task to a team's database
	function addTask($teamName, $taskName, $taskDescription, $date, $dueDate, $creator, $responsible){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			$teamDb = teamLogin(spaceReplace($teamName));	
				
			$insertQuery = $teamDb->prepare("INSERT INTO TASKS (Title, Description, CreationDate, DueDate, Creator, Responsible) VALUES (?, ?, ?, ?, ?, ?)");
			$insertParams = array($taskName, $taskDescription, $date, $dueDate, $creator, $responsible);
			$insertQuery->execute($insertParams);
		} catch(Exception $e){
			die($e);
		}
	}

	#Gets all the tasks from a team's database and returns the JSON representation
	function getTasks($teamName){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			$teamDb = teamLogin(spaceReplace($teamName));	

			$taskQ = $teamDb->prepare("SELECT * FROM TASKS");
			$taskQ->setFetchMode(PDO::FETCH_ASSOC);
			$taskQ->execute();

			$taskList = $taskQ->fetchAll();
			return (json_encode($taskList));
		} catch(Exception $e){
			die($e);
		}
		
	}

	#Logs into a given team's database
	function teamLogin($teamName){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			#Variables may need to be modified for security/depending on server configuration
			$dbhost = "localhost";
			$dbuser = "root";
			$dbpassword = "projectok";
			$dbname = $teamName;
	
			$db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $db;
			
		} catch(Exception $e){
			die($e);
		}

	}

	#Checks that a team with the given name doesn't already exists, and creates it if there is no conflict	
	function createTeam($teamName){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

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
			
			createTeamDatabase($teamName);

			addMemberToTeam($teamName, $_SESSION["username"]);

	
			header("Location: profile.php");
		} catch (Exception $e){
			die($e);
		} 
	}

	#Adds a member to a team
	function addMemberToTeam($teamName, $memberName){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		require("logindb.php");
		if(verifyUser($memberName) && verifyTeam($teamName)){
			if(isMemberOfTeam($memberName, $teamName)){
				echo "$memberName is already a member of $teamName!\n";
				echo "<script>setTimeout(\"window.location='teamPage.php?&teamName=$teamName'\", 3000);</script>";
				exit();
			}
			try{
				$updateMembership = $db->prepare("INSERT INTO TEAM_MEMBERSHIP (TName, UName) VALUES ((SELECT Name FROM TEAMS WHERE TEAMS.Name=:teamName), (SELECT Name FROM USERS WHERE USERS.Name=:username))");
				$updateParams = array(":teamName" => $teamName, ":username" => $memberName);
				if(!$updateMembership->execute($updateParams)){
					die("Failed to update team membership!");
				}
				
			} catch (Exception $e){
				die($e);
			}
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
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			#Variables may need to be modified for security/depending on server configuration
			$dbhost = "localhost";
			$dbuser = "root";
			$dbpassword = "projectok";
	
			$pdo = new PDO("mysql:host=$dbhost", $dbuser, $dbpassword);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			#Create the database
			$spaceReplacedTeamName = spaceReplace($teamName);
			
			$createQuery = "CREATE DATABASE $spaceReplacedTeamName";	
			$create = $pdo->prepare($createQuery);
			$create->execute();

			$teamPdo = new PDO("mysql:host=$dbhost;dbname=$spaceReplacedTeamName", $dbuser, $dbpassword);
			$teamPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


			$taskTable = "CREATE TABLE IF NOT EXISTS TASKS( TaskID INT NOT NULL PRIMARY KEY AUTO_INCREMENT, INDEX(TaskID), UNIQUE(TaskID)," . 
				" Title VARCHAR(100) NOT NULL, INDEX(Title), UNIQUE(Title)," .
				" Description VARCHAR(3000) NOT NULL," .
				" CreationDate DATE NOT NULL, INDEX(CreationDate)," .
				" DueDate DATE NOT NULL, INDEX(DueDate)," .
				" Creator VARCHAR(100), INDEX(Creator)," .
				" Responsible VARCHAR(100), INDEX(Responsible)" . 
				" )";
			echo "$taskTable";

			$taskTableCom = $teamPdo->prepare($taskTable);
			$taskTableCom->execute();
			
		} catch (Exception $e){
			die($e);
		}
	}

	#Gets a list of all the teams where the given user is a member
	function getTeamsForUser($username){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

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
