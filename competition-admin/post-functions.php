<?php
/**
 * root/competition-admin/post-functions.php
 *
 * PHP functions used for POST requests
 *
 * Only functions used more than once should be here
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
 * Inserts a team as a row in uid123teams table
 * 
 * Each team is linked to one competition.
 * 
 * @param object  $db        PDO Object
 * @param array   $teamName  name of the team
 * @param integer $idCompetitiones foreign key
 * 
 * @return bool
 */
function insertTeam($db, $teamName, $idCompetitiones)
{
    $tableName = "uid".$_SESSION["uid"]."teams";
    $sql = "INSERT INTO $tableName (name, id_competitions) ";
    $sql .= "VALUES (:name, :id_competitions)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":name", $teamName);
    $stmt->bindParam(":id_competitions", $idCompetitiones);
    $stmt->execute();
}

/**
 * Inserts id_teams and id_players as a row in uid123teams_players table
 * 
 * Each row indicates which team a given player is in for a particular competition.
 * Note that the competition is obtainable indirectly from the teams table
 * 
 * @param object  $db        PDO Object
 * @param integer $idTeams   id of the team
 * @param integer $idPlayers id of the player
 * 
 * @return bool
 */
function uploadTeamPlayer($db, $idTeams, $idPlayers, $idCompetitions)
{
    $tableName = "uid".$_SESSION["uid"]."teams_players";
    $sql = "INSERT INTO $tableName (id_teams, id_players, id_competitions) ";
    $sql .= "VALUES (:id_teams, :id_players, :id_competitions)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id_teams", $idTeams);
    $stmt->bindParam(":id_players", $idPlayers);
    $stmt->bindParam(":id_competitions", $idCompetitions);
    return($stmt->execute());
}

/**
 * Deletes row from uid123teams_players WHERE id_teams and id_players = ...
 * 
 * Each row indicates which team a given player is in.
 * Note that the competition is obtainable indirectly from the teams table
 * 
 * @param object  $db        PDO Object
 * @param integer $idTeams   id of the team $_POST['teamid']
 * @param integer $idPlayers id of the player $_POST['select-player']
 * 
 * @return bool
 */
function removePlayer($db, $idTeams, $idPlayers)
{
    $tableName = "uid".$_SESSION["uid"]."teams_players";
    $sql = "DELETE FROM $tableName WHERE id_teams = :id_teams AND id_players = :id_players";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id_teams", $idTeams);
    $stmt->bindParam(":id_players", $idPlayers);
    return($stmt->execute());
}

function insertPlayerToScores($db, $competitionId, $playerId){
    $tableName = 'uid'.$_SESSION['uid'].'scores';
    $sql = "INSERT INTO $tableName (id_competitions, id_players)";
    $sql .= ' VALUES (:id_competitions, :id_players)';
    var_dump($sql);
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_competitions', $competitionId);
    $stmt->bindParam(':id_players', $playerId);
    $stmt->execute();
}

