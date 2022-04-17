<?php
/**
 * Create Competition
 */
session_start();

/**
 * If they are not logged in redirect to home page.
 */
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
<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include "../php/db-functions.php";
    /**
     * Inserts a competition as a row in uid123competitions table
     * 
     * @param object $db   PDO Object
     * @param array  $post The $_POST array from form submission
     * 
     * @return bool
     */
    function insertCompetition($db, $post)
    {
        $tableName = "uid".$_SESSION["uid"]."competitions";
        $sql = "INSERT INTO $tableName (name, id_course, date, time) ";
        $sql .= "VALUES (:name, :id_course, :date, :time)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":name", $post["name"]);
        $stmt->bindParam(":id_course", $post["course"]);
        $stmt->bindParam(":date", $post["date"]);
        $stmt->bindParam(":time", $post["time"]);
        $stmt->execute();
    }
    insertCompetition($db, $_POST);
    header("Location: ../");
    exit;
}

require "../php/db-functions.php";
$courseNames = getCourseNames($db);
?>
    <p><a href="../">Home</a></p>
    <h1>Create Competition</h1>
    <form method="post">

      <label for="name">Name</label>
      <input type="text" id="name" name="name">

      <!-- We have to enable one course to be selected -->
      <label for="course">Choose a Course:</label>
      <select name="course" id="course">
<?php
for ($i=0; $i < count($courseNames); $i++) {
    echo "<option value='{$courseNames[$i]['id']}'>";
    echo "{$courseNames[$i]['name']}</option>";
}
?>
      </select>

      <label for="date">Date</label>
      <input type="date" id="date" name="date">

      <label for="time">Time</label>
      <input type="time" id="time" name="time">

      <input type="submit" name="submit">
    </form>
  </body>
</html>
