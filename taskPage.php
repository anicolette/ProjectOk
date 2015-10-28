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
</head>

<body>
    <h1>
        Tasks
    </h1>
    <?php
        $tasks = getTasks($_GET['teamName']);
        $tasks_array = json_decode($tasks,True);
        // Title, Description, CreationDate, DueDate, Creator, Responsible
        foreach ($tasks_array as $task) {
            echo "<h3>" . $task['Title'] . " to be completed by " . $task['DueDate'] . "</h3>" .
                "<h4>Description:</h4>" . $task['Description'] .
                "<br>" .
                "<h5>Created on " . $task['CreationDate'] . " by " . $task['Responsible'] . "(person responsible?)</h5>";
        }
    ?>
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
</body>
</html>
