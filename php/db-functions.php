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
function getPlayers($db)
{
    $tableName = "uid".$_SESSION["uid"]."players";
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
 * @param string  $token unguessable string in emails table
 * 
 * @used-by golf/submit-scores/index.php
 * @used-by golf/received-scores/index.php
 * 
 * @return array of rows from the table
 */
function getEmailsRow($db, $uid, $token)
{
    $tableName = "uid".$uid."emails";
    $sql = "SELECT id_players, id_teams, id_competitions from $tableName";
    $sql .= " WHERE token = :token";
    $stmt= $db->prepare($sql);
    $stmt->bindParam(":token", $token);
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
function getCourseForCompetition($db, $competitionId, $uid)
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
