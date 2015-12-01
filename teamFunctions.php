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

	#Gets all the incompleted tasks from a team's database and returns the JSON representation
	function getTasksByCompletion($teamName, $completed){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			$teamDb = teamLogin(spaceReplace($teamName));	

			$taskQ = $teamDb->prepare("SELECT * FROM TASKS WHERE FINISHED='" . ($completed ? 'Y' : 'N')  . "'");
			$taskQ->setFetchMode(PDO::FETCH_ASSOC);
			$taskQ->execute();

			$taskList = $taskQ->fetchAll();
			return (json_encode($taskList));
		} catch(Exception $e){
			die($e);
		}	
	}

	#Gets the tasks that have the given tag attached
	function getTasksByTag($teamName, $tag){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			$teamDb = teamLogin(spaceReplace($teamName));	

			$taskQ = $teamDb->prepare("SELECT * FROM TASKS JOIN TAGS ON TASKS.TaskID=TAGS.TaskID AND TAGS.Tag=:tag");
			$taskQP = array(":tag" => $tag);
			$taskQ->setFetchMode(PDO::FETCH_ASSOC);
			$taskQ->execute($taskQP);

			$taskList = $taskQ->fetchAll();
			return (json_encode($taskList));
		} catch(Exception $e){
			die($e);
		}	
	}

	#Change the completion status of a task
	function setTaskCompletion($teamName, $taskNo, $completed){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			$teamDb = teamLogin(spaceReplace($teamName));	

			$taskQ = $teamDb->prepare("UPDATE TASKS SET FINISHED='" . ($completed ? 'Y' : 'N')  . "' WHERE TASKS.TaskID=:taskId" );
			$taskQP = array(":taskId" => $taskNo);
			$taskQ->setFetchMode(PDO::FETCH_ASSOC);
			$taskQ->execute($taskQP);

		} catch(Exception $e){
			die($e);
		}	
	}

	#Adds a tag to a given task
	function addTag($teamName, $taskNo, $tag){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			$teamDb = teamLogin(spaceReplace($teamName));	

			$tagQ = $teamDb->prepare("REPLACE INTO TAGS(TaskID, Tag) VALUES (:taskNo, :tag)");
			$tagQP = array(":taskNo" => $taskNo, ":tag" => $tag);
			$tagQ->setFetchMode(PDO::FETCH_ASSOC);
			$tagQ->execute($tagQP);
			
		} catch(Exception $e){
			die($e);
		}	
	}

	#Gets the tags attached to a given task
	function getTags($teamName, $taskNo){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			$teamDb = teamLogin(spaceReplace($teamName));	

			$tagQ = $teamDb->prepare("SELECT DISTINCT Tag from TAGS WHERE TaskID=:taskNo");
			$tagQP = array(":taskNo" => $taskNo);
			$tagQ->setFetchMode(PDO::FETCH_ASSOC);
			$tagQ->execute($tagQP);
			$tagList = $tagQ->fetchAll();

			return (json_encode($tagList));
			
		} catch(Exception $e){
			die($e);
		}	
	}

	#Gets all the tasks that a given user is responsible for from a team's database and returns the JSON representation
	function getTasksForUser($teamName, $username){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			$teamDb = teamLogin(spaceReplace($teamName));	

			$taskQ = $teamDb->prepare("SELECT * FROM TASKS WHERE TASKS.Responsible=:username");
			$taskParams = array(":username" => $username);
			$taskQ->setFetchMode(PDO::FETCH_ASSOC);
			$taskQ->execute($taskParams);

			$taskList = $taskQ->fetchAll();
			return (json_encode($taskList));
		} catch(Exception $e){
			die($e);
		}
		
	}

	#Assigns the given task to the given user
	function assignTask($teamName, $username, $taskId){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			$teamDb = teamLogin(spaceReplace($teamName));	

			$assignQuery = "UPDATE TASKS SET TASKS.Responsible=:username WHERE TASKS.TaskId=:taskId ";
			$assignParams = array(":taskId" => $taskId, ":username" => $username);

			$assign = $teamDb->prepare($assignQuery);
			$assign->execute($assignParams);

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
				" Responsible VARCHAR(100), INDEX(Responsible)," . 
				" Finished CHAR NOT NULL DEFAULT 'N' " .
				" )";


			$taskTableCom = $teamPdo->prepare($taskTable);
			$taskTableCom->execute();

			$tagTable = "CREATE TABLE IF NOT EXISTS TAGS( TaskID INT NOT NULL," . 
				" Tag VARCHAR(50) NOT NULL," .
				" FOREIGN KEY (TaskID) REFERENCES TASKS(TaskID) ON UPDATE CASCADE ON DELETE CASCADE," .
				" PRIMARY KEY (TaskID, Tag)" .   
				" )";

			$tagTableCom = $teamPdo->prepare($tagTable);
			$tagTableCom->execute();

			createEventTable($teamPdo);

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
			return json_encode($resultList);
		} catch(Exception $e){
			die($e);
		}
	}

	# Creates team's event table
	function createEventTable($pdo) {
		// Create table for team events
		$eventTable = "CREATE TABLE IF NOT EXISTS EVENTS( EventId INT NOT NULL PRIMARY KEY AUTO_INCREMENT, INDEX(EventID), UNIQUE(EventID)," .
			" Title VARCHAR(100) NOT NULL, INDEX(Title), UNIQUE(Title)," .
			" Description VARCHAR(3000) NOT NULL," .
			" CreationDate DATE NOT NULL, INDEX(CreationDate)," .
			" DateOf DATE NOT NULL, INDEX(DateOf)," .
			" Creator VARCHAR(100), INDEX(Creator)" .
			" )";
		echo "$eventTable";

		$eventTableCom = $pdo->prepare($eventTable);
		$eventTableCom->execute();
	}

	#Adds an event to a team's database
	function addEvent($teamName, $eventTitle, $eventDescription, $date, $dateOf, $creator){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		try{
			$teamDb = teamLogin(spaceReplace($teamName));

			$insertQuery = $teamDb->prepare("INSERT INTO EVENTS (Title, Description, CreationDate, DateOf, Creator) VALUES (?, ?, ?, ?, ?)");
			$insertParams = array($eventTitle, $eventDescription, $date, $dateOf, $creator);
			$insertQuery->execute($insertParams);
		} catch(Exception $e){
			die($e);
		}
	}

	function getEventsForUser($username) {

		$teams = json_decode(getTeamsForUser($username), true);
		$userEvents = [];

		foreach($teams as $team) {
			array_push($userEvents, array($team['TName'], json_decode(getTeamEvents($team['TName']), true)));
		}

		return json_encode($userEvents);
	}

	function getTeamEvents($teamName) {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		try {
			$teamDb = teamLogin(spaceReplace($teamName));
			$getEvents = $teamDb->prepare("SELECT * FROM EVENTS");
			$getEvents->setFetchMode(PDO::FETCH_ASSOC);
			$getEvents->execute();

			return json_encode($getEvents->fetchAll());

		} catch (Exception $e) {
			die($e);
		}
	}

?>
