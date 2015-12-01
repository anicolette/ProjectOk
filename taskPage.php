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
    <link rel="stylesheet" type="text/css" href="style.css">

	<script type="text/javascript">

		function assign(formObj){
			console.log("assign called\n");

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
        $tasks = getTasks($_GET['teamName']);
        $tasks_array = json_decode($tasks,True);
        // Title, Description, CreationDate, DueDate, Creator, Responsible
        foreach ($tasks_array as $task) {
            echo "<h3>" . $task['Title'] . " to be completed by " . $task['DueDate'] . " by " . $task['Responsible']  . "</h3>" .
                "<h4>Description:</h4>" . $task['Description'] .
                "<br>" .
                "<h5>Created on " . $task['CreationDate'] . " by " . $task['Creator'] . "</h5>" . 
		"<h5>Finished: " . ($task['Finished'] == 'Y' ? "Yes" : "No") . "</h5>";

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
		echo $setCompleteButton;
        }
    ?>
    </div>
<!--    Come back to this later to make page more responsive
<script>
    function loadTasks(){
        var req;
        if(window.XMLHttpRequest){
            req = new XMLHttpRequest();
        } else{
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        req.onreadystatechange = function request(){
            if(req.readyState == 4 && req.status == 200){
                var res = JSON.parse(req.responseText);
                var htmlRes = "";
                for(var row in res){
                    htmlRes += "<a href=\"teamPage.php?&teamName=" + res[row].TName + "\">" + res[row].TName + "</a></br>";
                }
                document.getElementById("TeamList").innerHTML = htmlRes;
            }
        }
        req.open("post", "getTasksForTeam.php?&teamName=" + getUrlVars()["teamName"]);
        req.send();
    }
</script>
-->
        </div>
</body>
</html>
