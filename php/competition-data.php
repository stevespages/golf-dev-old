<?php
/**
 * Selects a row from uid23competitions table
 * 
 * @param object  $db      PDO Object
 * @param integer $competitionId derived from url query string parameter value
 * 
 * @return array The column headings for one row from the table
 */
function getCompetitionRow($db, $uid, $competitionId)
{
    $tableName = "uid".$uid."competitions";
    $sql = "SELECT id, name, id_course, date, time FROM $tableName WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id", $competitionId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function nestTeams($teams){
    $teamsNested = [];
    foreach ($teams as $row) {
        if (!isset($teamsNested[$row["idTeams"]])) {
            $teamsNested[$row["idTeams"]]
                =   [
                       "idTeams" => $row["idTeams"],
                       "teamName" => $row["teamName"],
                       "players" => [] 
                    ];
        }
        // if(isset($row["playerId"])){ so you don't get empty keys}
        if (!empty($row["idPlayers"])) {
            $teamsNested[$row["idTeams"]]["players"][]
                =   [
                    "idPlayers" => $row["idPlayers"],
                    "playerName" => $row["playerName"],
                    "email" => $row["email"]
                    ];
        }
    }
    // convert the primary index of the array to regular 
    // sequence of integers starting at zero.
    $teamsNested = array_values($teamsNested);
    return $teamsNested;
}

function getToken($db, $uid, $playersId, $competitionsId){
    $tableName = 'uid' . $uid . 'teams_players';
    $sql = "SELECT token FROM $tableName WHERE";
    $sql .= " id_players = $playersId";
    $sql .= " AND id_competitions = $competitionsId";
    $stmt= $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result;
}

$competition = getCompetitionRow($db, $uid, $competitionId);

$course = getCourseForCompetition($db, $uid, $competitionId);

$teams = getTeams($db, $uid, $competitionId);

$teams = nestTeams($teams);

foreach($teams as $key => $team){
    foreach($team['players'] as $key2 => $player){
        $teams[$key]['players'][$key2]['token'] = getToken($db, $uid, $player['idPlayers'], $competitionId);
    }
}

$scores = getScores($db, $uid, $competitionId);

$scores2 = [];
foreach($scores as $score){
    $scores2[$score['id_players']]['name'] = $score['name'];
    $scores2[$score['id_players']]['id_players'] = $score['id_players'];
    $scores2[$score['id_players']]['handicap'] = $score['handicap'];
    $scores2[$score['id_players']]['h1'] = $score['h1'];
    $scores2[$score['id_players']]['h2'] = $score['h2'];
    $scores2[$score['id_players']]['h3'] = $score['h3'];
    $scores2[$score['id_players']]['h4'] = $score['h4'];
    $scores2[$score['id_players']]['h5'] = $score['h5'];
    $scores2[$score['id_players']]['h6'] = $score['h6'];
    $scores2[$score['id_players']]['h7'] = $score['h7'];
    $scores2[$score['id_players']]['h8'] = $score['h8'];
    $scores2[$score['id_players']]['h9'] = $score['h9'];
    $scores2[$score['id_players']]['h10'] = $score['h10'];
    $scores2[$score['id_players']]['h11'] = $score['h11'];
    $scores2[$score['id_players']]['h12'] = $score['h12'];
    $scores2[$score['id_players']]['h13'] = $score['h13'];
    $scores2[$score['id_players']]['h14'] = $score['h14'];
    $scores2[$score['id_players']]['h15'] = $score['h15'];
    $scores2[$score['id_players']]['h16'] = $score['h16'];
    $scores2[$score['id_players']]['h17'] = $score['h17'];
    $scores2[$score['id_players']]['h18'] = $score['h18'];
}

$scores = $scores2;

foreach($teams as $key => $team){
    foreach($team['players'] as $key2 => $player){
        $teams[$key]['players'][$key2]['handicap'] = $scores[$player['idPlayers']]['handicap'];
        $teams[$key]['players'][$key2]['h1'] = $scores[$player['idPlayers']]['h1'];
        $teams[$key]['players'][$key2]['h2'] = $scores[$player['idPlayers']]['h2'];
        $teams[$key]['players'][$key2]['h3'] = $scores[$player['idPlayers']]['h3'];
        $teams[$key]['players'][$key2]['h4'] = $scores[$player['idPlayers']]['h4'];
        $teams[$key]['players'][$key2]['h5'] = $scores[$player['idPlayers']]['h5'];
        $teams[$key]['players'][$key2]['h6'] = $scores[$player['idPlayers']]['h6'];
        $teams[$key]['players'][$key2]['h7'] = $scores[$player['idPlayers']]['h7'];
        $teams[$key]['players'][$key2]['h8'] = $scores[$player['idPlayers']]['h8'];
        $teams[$key]['players'][$key2]['h9'] = $scores[$player['idPlayers']]['h9'];
        $teams[$key]['players'][$key2]['h10'] = $scores[$player['idPlayers']]['h10'];
        $teams[$key]['players'][$key2]['h11'] = $scores[$player['idPlayers']]['h11'];
        $teams[$key]['players'][$key2]['h12'] = $scores[$player['idPlayers']]['h12'];
        $teams[$key]['players'][$key2]['h13'] = $scores[$player['idPlayers']]['h13'];
        $teams[$key]['players'][$key2]['h14'] = $scores[$player['idPlayers']]['h14'];
        $teams[$key]['players'][$key2]['h15'] = $scores[$player['idPlayers']]['h15'];
        $teams[$key]['players'][$key2]['h16'] = $scores[$player['idPlayers']]['h16'];
        $teams[$key]['players'][$key2]['h17'] = $scores[$player['idPlayers']]['h17'];
        $teams[$key]['players'][$key2]['h18'] = $scores[$player['idPlayers']]['h18'];
    }
}

$allPlayers = getPlayers($db, $uid);

/*
echo '<hr>';
echo '<p>$competition</p>';
echo '<pre></code>';
print_r($competition);
echo '</code></pre>';

echo '<hr>';
echo '<p>$course</p>';
echo '<pre></code>';
print_r($course);
echo '</code></pre>';

echo '<hr>';
echo '<p>$teams</p>';
echo '<pre></code>';
print_r($teams);
echo '</code></pre>';

echo '<hr>';
echo '<p>$scores</p>';
echo '<pre></code>';
print_r($scores);
echo '</code></pre>';
 */

