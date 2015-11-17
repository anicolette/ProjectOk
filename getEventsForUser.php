<?php

/*
    This script loads all of the events for the user over all their teams
*/

    require_once("teamFunctions.php");

    #Make sure the user is actually logged in and that they didn't just manually navigate here
    verifyLogin();

    #Get the username from the session and get the team names
    $username = $_SESSION["username"];
    getEventsForUser($username);
?>