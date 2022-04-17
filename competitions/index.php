<?php
/**
 * Competitions
 */
session_start();

/*
* if they are not logged in redirect to home page.
*/
//if(!isset($_SESSION['user'])){
if (empty($_SESSION['user'])) {
    header('Location: ../');
    exit();
}

/*
 * Error reporting
 * Comment this out in production
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../php/db-functions.php";
if (isset($_GET['competitionid'])) {
    $competitionId = $_GET['competitionid'];

    $tablename = "uid" . $_SESSION['uid'] . "competitions";
    $sql = "DELETE FROM $tablename WHERE id = :competitionid";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':competitionid', $competitionId);
    $stmt->execute();

    // now delete rows from teams
    $tablename = "uid" . $_SESSION['uid'] . "teams";
    $sql = "DELETE FROM $tablename WHERE id_competitions = :competitionid";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':competitionid', $competitionId);
    $stmt->execute();

    // now delete rows from teams_players
    $tablename = "uid" . $_SESSION['uid'] . "teams_players";
    $sql = "DELETE FROM $tablename WHERE id_competitions = " . $competitionId;
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $tablename = "uid" . $_SESSION['uid'] . "scores";
    $sql = "DELETE FROM $tablename WHERE id_competitions = " . $competitionId;
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $tablename = "uid" . $_SESSION['uid'] . "emails";
    $sql = "DELETE FROM $tablename WHERE id_competitions = " . $competitionId;
    $stmt = $db->prepare($sql);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../css/main.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Golf</title>
    </head>
    <body>
<?php
/**
 * Selects all the competitions from the uid123courses table
 * 
 * @param object $db PDO Object
 * 
 * @return An array of rows from the table
 */
function getCompetitions($db)
{
    $tableName = "uid".$_SESSION["uid"]."competitions";
    $sql = "SELECT id, name, id_course FROM $tableName";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}
$competitions = getCompetitions($db);
?>
    <p><a href="../">Home</a></p>
    <h1>Competitions</h1>
    <ul>
<?php
for ($i=0; $i < count($competitions); $i++) {
    echo "<li id='competition-".$competitions[$i]['id']."'>";
    echo "<a href='../competition-admin/?competitionid=";
    echo $competitions[$i]['id']."'>".$competitions[$i]['name']." ";
    echo $competitions[$i]['id_course']."</a> ";
    echo "<a href='./?competitionid=";
    echo $competitions[$i]['id']."'>Delete</a>";
    echo "</li>";
}
?>      
        </ul>
    </body>
</html>