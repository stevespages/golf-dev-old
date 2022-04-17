<?php
/**
 * Creates a player
 * 
 * PHP version 7
 * 
 * @category Blah
 * 
 * @package Golf
 * 
 * @author Stephen Greig <greigsteve@gmail.com>
 * 
 * @license To be decided
 * 
 * @link https://stevespages.org.uk/golf
 */
session_start();

/**
 * If they are not logged in redirect to home page.
 */
if (empty($_SESSION['user'])) {
    header('Location: ../');
    exit();
}

/**
 * Error reporting
 * Comment this out in production
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include "../php/db-functions.php";
    /**
     * Inserts a player as a row in uid123players table
     * 
     * @param object $db   PDO Object
     * @param array  $post The $_POST array from form submission
     * 
     * @return array rows from the table
     */
    function insertPlayer($db, $post)
    {
        $tableName = "uid".$_SESSION["uid"]."players";
        $sql = "INSERT INTO $tableName (name, email) VALUES (:name, :email)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":name", $post["name"]);
        $stmt->bindParam(":email", $post["email"]);
        $stmt->execute();
    }
    insertPlayer($db, $_POST);
    header("Location: ../");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../css/main.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Golf</title>
        <style>
            input[type=number] {
                width: 30px;
            }
        </style>
    </head>
    <body>
        <p><a href="../">Home</a></p>
        <h1>Create Player</h1>
        <form method="post">

            <label for="name">Name</label>
            <input type="text" id="name" name="name">

            <label for="email">Email</label>
            <input type="email" id="email" name="email">

            <input type="submit" name="submit">

        </form>
    </body>
</html>
