<?php

/*
    This script loads all of the events for the user over all their teams
*/

    require_once("teamFunctions.php");

    #Make sure the user is actually logged in and that they didn't just manually navigate here
    verifyLogin();

    #Get the username from the session and get the team names
    $username = $_SESSION["username"];
    $teamWithEvents = json_decode(getEventsForUser($username), true);
    foreach($teamWithEvents as $teamWithEvent) {
        if ($teamWithEvent[1] != []) {
            echo $teamWithEvent[0] . "</br>";
            foreach ($teamWithEvent[1] as $event) {
                // Title, Description, CreationDate, DateOf, Creator
                echo $event['Title'] . "</br>"
                    . $event['Description'] . "</br>"
                    . $event['CreationDate'] . "</br>"
                    . $event['DateOf'] . "</br>"
                    . $event['Creator'] . "</br>";
            }
        }
    }
?>
