<?php
    require_once("teamFunctions.php");

    #Make sure the user is actually logged in and that they didn't just manually navigate here
    verifyLogin();

    #Get the username from the session and get the tasks
    $teamName = $_SESSION["teamName"];
    getTasks($teamName);
?>