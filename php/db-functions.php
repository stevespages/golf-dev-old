<?php
/**
 * root/php/db-functions.php
 *
 * Functions using golf/sqlite/database.db
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

/**
 * Comment this out in production
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// See the following link for __DIR__
// https://www.php.net/manual/en/language.constants.magic.php
$dsn = 'sqlite:'.__DIR__.'/../sqlite/database.db';

try {                    
    $db = new PDO($dsn);
} catch (Exception $e) {    
    $error = $e->getMessage();
}

/**
 * Selects all the courses from the uid123courses table.
 *  
 * @param object $db PDO Object
 *
 * @used-by golf/courses/index.php
 * @used-by golf/create-competition/index.php
 * 
 * @return array of rows from the table
 */
function getCourseNames($db)
{
    $tableName = "uid".$_SESSION["uid"]."courses";
    $sql = "SELECT id, name, postcode FROM $tableName";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}

/**
 * Selects all the players from the uid123players table
 * 
 * @param object $db PDO Object
 * 
 * @used-by golf/?
 * @used-by golf/?
 * 
 * @return array of rows from the table
 */
function getPlayers($db, $uid)
{
    $tableName = "uid".$uid."players";
    $sql = "SELECT id, name, email FROM $tableName";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Selects all the players from the uid123players table
 * 
 * @param object  $db    PDO Object
 * @param integer $uid   the id of the administrator
 * @param string  $token unguessable string in teams_players table
 * 
 * @used-by golf/submit-scores/index.php
 * @used-by golf/received-scores/index.php
 * 
 * @return array of rows from the table
 */
function getTeamsPlayersRow($db, $uid, $token)
{
    $tableName = "uid".$uid."teams_players";
    $sql = "SELECT id_players, id_teams, id_competitions from $tableName";
    $sql .= " WHERE token = :token";
    $stmt= $db->prepare($sql);
    $stmt->bindParam(":token", $token);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Selects all the players from the uid123players table
 * 
 * @param object  $db    PDO Object
 * @param integer $uid   the id of the administrator
 * @param integer $teamId   the id of the team 
 * 
 * @used-by golf/submit-scores/index.php
 * @used-by golf/received-scores/index.php
 * 
 * @return array of rows from the table
 */
function getTeamsRow($db, $uid, $teamId)
{
    $tableName = "uid".$uid."teams";
    $sql = "SELECT id, name, id_competitions from $tableName";
    $sql .= " WHERE id = :id";
    $stmt= $db->prepare($sql);
    $stmt->bindParam(":id", $teamId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Gets a row from the uid123courses table.
 *  
 * @param OBJECT  $db PDO Object
 * @param INTEGER $competitionId
 *
 * @used-by golf/submit-scores/index.php
 * @used-by golf/competition-admin/index.php
 * 
 * @return ARRAY of the table row
 */
function getCourseForCompetition($db, $uid, $competitionId)
{
    $tablePrefix = "uid".$uid;
    $c = $tablePrefix."courses";
    $m = $tablePrefix."competitions";
    $sql = "SELECT $c.name, $c.h1par, $c.h1si,";
    $sql .= " $c.h2par, $c.h2si, $c.h3par, $c.h3si,";
    $sql .= " $c.h4par, $c.h4si, $c.h5par, $c.h5si,";
    $sql .= " $c.h6par, $c.h6si, $c.h7par, $c.h7si,";
    $sql .= " $c.h8par, $c.h8si, $c.h9par, $c.h9si,";
    $sql .= " $c.h10par, $c.h10si, $c.h11par, $c.h11si,";
    $sql .= " $c.h12par, $c.h12si, $c.h13par, $c.h13si,";
    $sql .= " $c.h14par, $c.h14si, $c.h15par, $c.h15si,";
    $sql .= " $c.h16par, $c.h16si, $c.h17par, $c.h17si,";
    $sql .= " $c.h18par, $c.h18si";
    $sql .= " FROM $c LEFT JOIN $m";
    $sql .= " ON $m.id_course = $c.id WHERE $m.id = :id_competitions";
    $stmt = $db->prepare($sql);
    $stmt->bindParam("id_competitions", $competitionId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
    // return $c;
}

/**
 * Gets teams LEFT JOIN teams_players LEFT JOIN players
 * WHERE t.id_competitions = $idCompetitions
 * 
 * @param object  $db        PDO Object
 * @param integer $idCompetitions name of the team
 * 
 * @return bool
 *
 * you can add another LEFT JOIN scores ... so this gets
 * all the info for displaying on the page...
 */
function getTeams($db, $uid, $competitionId)
{
    $tablePrefix = "uid".$uid;
    $tp = $tablePrefix."teams_players";
    $t = $tablePrefix."teams";
    $p = $tablePrefix."players";
    $sql = "SELECT $t.id AS idTeams, $t.name AS teamName,";
    $sql .= " $tp.id_players AS idPlayers, $p.id AS idPlayers2,";
    $sql .= " $p.name AS playerName, $p.email AS email FROM $t LEFT JOIN";
    $sql .= " $tp ON $t.id = $tp.id_teams LEFT JOIN $p";
    $sql .= "  ON $p.id = $tp.id_players ";
    $sql .= "WHERE $t.id_competitions = $competitionId";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Selects rows from uid23scores table
 * 
 * @param object  $db      PDO Object
 * @param integer $competitionId derived from url query string parameter value
 * 
 * @return array of rows from the table
 * 
 * this should be unnecessary once you add another JOIN to getTeams above
 */
function getScores($db, $uid, $competitionId)
{
    $tablePrefix = "uid".$uid;
    $s = $tablePrefix."scores";
    $p = $tablePrefix."players";
    $sql = "SELECT $p.name, $s.id_players, $s.handicap, $s.h1, $s.h2, $s.h3, $s.h4, $s.h5, $s.h6, $s.h7, $s.h8, $s.h9,";
    $sql .= " $s.h10, $s.h11, $s.h12, $s.h13, $s.h14, $s.h15, $s.h16, $s.h17, $s.h18";
    $sql .= " FROM $s LEFT JOIN $p ON $p.id = $s.id_players WHERE id_competitions = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id", $competitionId);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

