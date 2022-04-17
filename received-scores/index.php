<?php
/**
 * Received Scores
 */

/*
 * Error reporting
 *
 * Comment this out in production
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../");
}

require "../php/db-functions.php";

$emailsRow = getEmailsRow($db, $_POST["u"], $_POST["token"]);

if (empty($emailsRow)) {
    header("Location: ../");
}

function insertScores($db, $uid, $scores)
{
    $tablename = "uid{$uid}scores";
    $sql = "INSERT INTO $tablename (id_competitions, id_players, handicap,";
    $sql .= " h1, h2, h3, h4, h5, h6, h7, h8, h9,";
    $sql .= " h10, h11, h12, h13, h14, h15, h16, h17, h18)";
    $sql .= " VALUES (:id_competitions, :id_players, :handicap,";
    $sql .= " :h1, :h2, :h3, :h4, :h5, :h6, :h7, :h8, :h9,";
    $sql .= " :h10, :h11, :h12, :h13, :h14, :h15, :h16, :h17, :h18)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id_competitions", $_POST["competitionsid"]);
    $stmt->bindParam(":id_players", $_POST["playersid"]);
    $stmt->bindParam(":handicap", $_POST["handicap"]);
    $stmt->bindParam(":h1", $_POST["h1"]);
    $stmt->bindParam(":h2", $_POST["h2"]);
    $stmt->bindParam(":h3", $_POST["h3"]);
    $stmt->bindParam(":h4", $_POST["h4"]);
    $stmt->bindParam(":h5", $_POST["h5"]);
    $stmt->bindParam(":h6", $_POST["h6"]);
    $stmt->bindParam(":h7", $_POST["h7"]);
    $stmt->bindParam(":h8", $_POST["h8"]);
    $stmt->bindParam(":h9", $_POST["h9"]);
    $stmt->bindParam(":h10", $_POST["h10"]);
    $stmt->bindParam(":h11", $_POST["h11"]);
    $stmt->bindParam(":h12", $_POST["h12"]);
    $stmt->bindParam(":h13", $_POST["h13"]);
    $stmt->bindParam(":h14", $_POST["h14"]);
    $stmt->bindParam(":h15", $_POST["h15"]);
    $stmt->bindParam(":h16", $_POST["h16"]);
    $stmt->bindParam(":h17", $_POST["h17"]);
    $stmt->bindParam(":h18", $_POST["h18"]);
    $stmt->execute();

    var_dump($sql);
}
insertScores($db, $_POST["u"], $_POST);
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
        <h1>Your Scores Have Been Submitted</h1>
        <p>
            Thank you for submitting your scores.
        </p>
    </body>
</html>
