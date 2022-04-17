<?php
/**
 * Players
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

if(isset($_GET['delete'])){
    $tableName = "uid".$_SESSION['uid']."players";
    $sql = "DELETE FROM $tableName WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $_GET['delete']);
    $stmt->execute();
}

$players = getPlayers($db);
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
        <p><a href="../">Home</a></p>
        <h1>Players</h1>
        <ul>
<?php
for ($i=0; $i < count($players); $i++) {
    echo "<li id='player-name-".$players[$i]['id']."'>";
    echo $players[$i]['name']." ".$players[$i]['email'];
    echo " <a href='./?delete=".$players[$i]['id']."'>Delete</a></li>";
}
?>      
        </ul>
    </body>
</html>

