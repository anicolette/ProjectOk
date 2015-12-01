<?php
require_once("teamFunctions.php");
// User must be logged in to view team tasks
verifyLogin();
?>

<!doctype html>
<html>
<head>
	<title>
		<?php
		echo $_GET['teamName'] . " Task List";
		?>
	</title>
	<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	require_once("teamFunctions.php");
	verifyLogin();

	$teamName = $_GET["teamName"];
	$tag = $_GET["tag"];
	?>
	<link rel="stylesheet" type="text/css" href="style.css">

	<script type="text/javascript">

		function assign(formObj){

			var req;
			if(window.XMLHttpRequest){
				req = new XMLHttpRequest();
			} else{
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}

			req.onreadystatechange = function request(){
				console.log(req.readyState + " " + req.status + "\n" + req.responseText );
				if(req.readyState == 4 && req.status == 200){
					if(req.responseText=="0"){
						alert("Invalid User!");
					}
				}
			}
			req.open("post", "assignTask.php", false);
			req.send(new FormData(formObj));
		}

		function setComplete(formObj){
			console.log("setComplete called\n");

			var req;
			if(window.XMLHttpRequest){
				req = new XMLHttpRequest();
			} else{
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}

			req.onreadystatechange = function request(){
				console.log(req.readyState + " " + req.status + "\n" + req.responseText );
				if(req.readyState == 4 && req.status == 200){
					if(req.responseText=="0"){
						alert("Invalid User!");
					}			
				}
			}
			req.open("post", "setTaskCompletion.php", false);
    			req.send(new FormData(formObj));
		}

		function addTag(formObj){
			console.log("addTag called\n");

			var req;
			if(window.XMLHttpRequest){
				req = new XMLHttpRequest();
			} else{
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}

			req.onreadystatechange = function request(){
				console.log(req.readyState + " " + req.status + "\n" + req.responseText );
				if(req.readyState == 4 && req.status == 200){
					if(req.responseText=="0"){
						alert("Invalid User!");
					}			
				}
			}
			req.open("post", "addTag.php", false);
    			req.send(new FormData(formObj));
		}
	</script>

</head>

<body id="taskList">
<div id="header">Tasks</div>
<div id="navteam"><a href="index.html">home</a><br/>
	<a href="profile.php">user panel</a></br>
	<a href="<?php echo 'taskPage.php?&teamName='.$_GET['teamName'] ?>">tasks</a><br />
	<a href="login.php">login</a></div>
<div id="tasks">
	<?php
	try{
		#Verify that a user was provided through POST
		if(empty($_GET["tag"]) ){
			echo "Must fill out all fields\n";
			exit();
		}


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

		#Verify that the current user is a member of the given team
		$memberRes = verifyMembership($teamName);
		if(!$memberRes){
			echo "Invalid team\n";
			echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
			exit();
		}

		$tasks = getTasksByTag($teamName,$tag);
		$tasks_array = json_decode($tasks,True);


	}catch (Exception $e){
		die($e);
	}
	// Title, Description, CreationDate, DueDate, Creator, Responsible
	foreach ($tasks_array as $task) {
	
           	 echo "<h3>" . $task['Title'] . " to be completed by " . $task['DueDate'] . " by " . $task['Responsible']  . "</h3>" .
                "<h4>Description:</h4>" . $task['Description'] .
                "<br>" .
                "<h5>Created on " . $task['CreationDate'] . " by " . $task['Creator'] . "</h5>" . 
		"<h5>Finished: " . ($task['Finished'] == 'Y' ? "Yes" : "No") . "</h5>" . 
		"Tags:</br>";
		$tags = getTags($_GET['teamName'], $task['TaskID']);
		$tags_array = json_decode($tags,True); 
		foreach($tags_array as $tag){
			echo "\"" . $tag['Tag'] . "\"     ";
		}
		echo "</br>";

		$assignButton = "<form class=\"form\" accept-charset=utf-8 action=\"\" onsubmit=\"javascript:assign(this)\" method=\"post\"><label for=\"username\">Assign to user</label> ";
                $assignButton .= "<input type=\"text\" name=\"username\" placeholder=\"username\" maxlength=\"100\"/>";
                $assignButton .= "<input type=\"hidden\" name=\"taskId\" value=\"" . $task["TaskID"] . "\"/>";
                $assignButton .= "<input type=\"hidden\" name=\"teamName\" value=\"" . $_GET["teamName"] . "\"/>";
                $assignButton .= "<input type=\"submit\" value=\"Assign\" />";
                $assignButton .= "</form>";
		echo $assignButton;

		$setCompleteButton = "<form class=\"form\" accept-charset=utf-8 action=\"\" onsubmit=\"javascript:setComplete(this)\" method=\"post\">";
		$setCompleteButton .= "<input type=\"hidden\" name=\"teamName\" value=\"" . $_GET["teamName"]  ."\"/>";
		$setCompleteButton .= "<input type=\"hidden\" name=\"taskId\" value=\"" . $task["TaskID"]  .  "\"/>";
		$setCompleteButton .= "<input type=\"hidden\" name=\"completed\" value=\"" . ($task["Finished"] == 'Y' ? "0" : "1")  . "\"/>";
		$setCompleteButton .= "<input type=\"submit\" value=\"Set " . ($task["Finished"] == 'N' ? "Complete" : "Incomplete") . "\"/>";
		$setCompleteButton .= "</form>";
		echo $setCompleteButton;
		echo "</br>";

		$addTagButton = "<form class=\"form\" accept-charset=utf-8 action=\"\" onsubmit=\"javascript:addTag(this)\" method=\"post\">";
		$addTagButton .= "<input type=\"hidden\" name=\"teamName\" value=\"" . $_GET["teamName"] . "\"/>";
		$addTagButton .= "<input type=\"hidden\" name=\"taskId\" value=\"" . $task["TaskID"]  .  "\"/>";
		$addTagButton .= "<input type=\"text\" name=\"tag\" placeholder=\"Add Tag\" maxlength=\"100\"/>";
		$addTagButton .= "<input type=\"submit\" value=\"Add Tag\" />";
		$addTagButton .= "</form>";
		echo $addTagButton;

	}
	?>
</div>
</div>
</body>
</html>
