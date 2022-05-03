<?php
/**
 * root/php/ajax.php
 *
 * Handle Ajax
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

require_once './db-functions.php';

if($_GET['calling-function'] === 'upload-score'){
    function updateHoleScore($db, $emailsRow){
        $tableName = "uid{$_GET['uid']}scores";
        $holeNumber = intval($_GET['hole-number']);
        if(gettype($holeNumber) !== 'integer'){
            return false;
        }
        if($holeNumber < 1 || $holeNumber > 18){
            return false;
        }
        $sql = "UPDATE $tableName SET h{$holeNumber} = :score";
        $sql .= " WHERE id_competitions = :id_competitions AND";
        $sql .= " id_players = :id_players";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':score', $_GET['score']);
        $stmt->bindParam(':id_competitions', $emailsRow['id_competitions']);
        $stmt->bindParam(':id_players', $emailsRow['id_players']);
        return($stmt->execute());
    }
    $emailsRow = getEmailsRow($db, $_GET['uid'], $_GET['token']);
    $result = updateHoleScore($db, $emailsRow);
    /*
    $resultArray = [
        'success' => false,
        'score' => $_GET['score'],
        'holeNumber' => $_GET['hole-number'],
        'holePar' => $_GET['hole-par'],
        'holeSi' => $_GET['hole-si'],
        'handicap' => $_GET['handicap'],
        'token' => $_GET['token'],
        'uid' => $_GET['uid'],
    ];
     */

    // We need to create two variables for use in competition-data.php
    $uid = $_GET['uid'];
    $competitionId = $emailsRow['id_competitions'];
    require_once './competition-data.php';

    $resultArray =
        [
            'success' => $result,
            'teams' => $teams
        ];
    
    echo json_encode($resultArray);

}

if($_GET['calling-function'] === 'upload-handicap'){
    function updateHandicap($db, $emailsRow){
        $tableName = "uid{$_GET['uid']}scores";
        $sql = "UPDATE $tableName SET handicap = :handicap";
        $sql .= " WHERE id_competitions = :id_competitions AND";
        $sql .= " id_players = :id_players";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':handicap', $_GET['handicap']);
        $stmt->bindParam(':id_competitions', $emailsRow['id_competitions']);
        $stmt->bindParam(':id_players', $emailsRow['id_players']);
        return($stmt->execute());
    }
    $emailsRow = getEmailsRow($db, $_GET['uid'], $_GET['token']);
    $result = updateHandicap($db, $emailsRow);
    $resultArray = ['success' => $result];
    /*
    $resultArray = [
        'success' => false,
        'handicap' => $_GET['handicap'],
        'token' => $_GET['token'],
        'uid' => $_GET['uid'],
    ];
     */
    echo json_encode($resultArray);
}

