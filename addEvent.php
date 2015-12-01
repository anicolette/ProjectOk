
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("teamFunctions.php");
verifyLogin();

if(!isset($_GET["teamName"]) || empty($_POST["eventName"]) || empty($_POST["dateOfEvent"]) || empty($_POST["eventDesc"]) ){
    echo "Must fill out all fields\n";
    echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
    exit();
}
$teamName = $_GET["teamName"];


try{
    #Verify that the given team name is valid (Someone could have spoofed the GET value and verify that the current user is a member of the given team
    if(!verifyTeam($teamName) || !verifyMembership($teamName, $_SESSION["username"])){
        echo "Invalid team\n";
        echo "<script>setTimeout(\"window.location='profile.php'\", 3000);</script>";
        exit();
    }
    #Get the current date
    $currentDate = date("Y-m-d");

    /*
    #Calculate the due date
    $timeOf = mktime(0, 0, 0, date("m")  , date("d")+$_POST["daysUntilEvent"], date("Y"));
    $dateOf = date("Y-m-d", $timeOf);
    */

    #Get the creator
    $creator = $_SESSION["username"];

    #Insert the task information into the team database. Person responsible will be set later.
    addEvent($teamName, $_POST["eventName"], $_POST["eventDesc"], $currentDate, $_POST['dateOfEvent'], $creator);

    header("Location: teamPage.php?&teamName=$teamName");


}catch (Exception $e){
    die($e);
}

?>
