<?php
/**
 * root/competition-admin/get-functions.php
 *
 * PHP functions called for GET requests
 *
 * Only functions that are called more than once should be here
 *
 * PHP version 7.0.33-0+deb9u12
 *
 * @category  ?
 * @package   ?
 * @author    Steve Greig <greigsteve@gmail.com>
 * @copyright 2022 Steve Greig
 * @license   GPL-3.0-or-later gnu.org
 * @version   0
 * @link      http://stevespages.org.uk/
 */

/**
 * Deletes a row from uid23teams table and rows from uid123teams_players table.
 * 
 * @param object  $db      PDO Object
 * @param integer $idTeams derived from url query string parameter value
 * 
 * @return bool success / failure of SQL query
 */
function deleteTeam($db, $idTeams)
{
    $tableName = "uid".$_SESSION["uid"]."teams";
    $sql = "DELETE FROM $tableName WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id", $idTeams);
    $result = $stmt->execute();

    $tableName = "uid".$_SESSION["uid"]."teams_players";
    $sql = "DELETE FROM $tableName WHERE id_teams = :id_teams";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id_teams", $idTeams);
    $result = $stmt->execute();
    return $result;
}

/**
 * This gets data for sending emails.
 * 
 * @param object  $db      PDO Object
 * @param integer $competitionId derived from url query string parameter value
 * 
 * @return bool success / failure of SQL query
 */
function prepareEmails($db, $competitionId)
{
    $tablePrefix = "uid".$_SESSION["uid"];
    $tp = $tablePrefix."teams_players";
    $t = $tablePrefix."teams";
    $p = $tablePrefix."players";
    $m = $tablePrefix."competitions";
    $c = $tablePrefix."courses";
    $sql = "SELECT $m.name AS competitionName, $m.date AS competitionDate, $m.time AS competitionTime, $c.name AS courseName, $c.postcode AS coursePostcode, $t.id AS teamId, $t.name AS teamName, $p.id AS playerId, $p.name AS playerName, $p.email AS playerEmail FROM $tp LEFT JOIN $t ON $t.id = $tp.id_teams LEFT JOIN $p ON $tp.id_players = $p.id LEFT JOIN $m ON $m.id = $t.id_competitions LEFT JOIN $c ON $c.id = $m.id_course WHERE $t.id_competitions = :id_competitions ORDER BY $t.id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id_competitions", $competitionId);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Send email to a player inviting them to submit their handicap and scores.
 * 
 * @param object $db           PDO Object
 * @param array  $emailsToSend array returned from sendAllEmails()
 * 
 * @return bool success / failure of sending the email
 */
function sendEmails($db, $emailsToSend, $competitionId)
{
    $tableName = "uid".$_SESSION["uid"]."emails";
    foreach ($emailsToSend as $email) {
        $token = bin2hex(random_bytes(25));
        $subject = "Submit Scores";
        $msg = "Hallo {$email['playerName']}, you are a contestant in team, {$email['teamName']}, for the {$email['competitionName']} competition at {$email['courseName']}, {$email['coursePostcode']} ({$email['competitionDate']}, {$email['competitionTime']}). If you would like to submit your scores electronically through a secure website please click this link: http://stevespages.org.uk/golf/submit-scores/?token={$token}&u={$_SESSION['uid']} Otherwise please take your score card to the competition administrators when you have completed it.";
        $headers = "From: golf@golf.com";
        $sql = "INSERT INTO $tableName (id_players, id_teams, id_competitions, token) VALUES (:id_players, :id_teams, :id_competitions, '$token')";
        var_dump($sql);
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id_players", $email['playerId']);
        $stmt->bindParam(":id_teams", $email['teamId']);
        $stmt->bindParam(":id_competitions", $competitionId);
        $stmt->execute();
        mail($email['playerEmail'], $subject, $msg, $headers);
    }
}

