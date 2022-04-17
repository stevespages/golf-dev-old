<?php
/**
 * Submit Scores
 */

/*
 * Error reporting
 *
 * Comment this out in production
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../php/db-functions.php";

if (!isset($_GET["token"]) || !isset($_GET["u"])) {
    header("Location: ../");
}

$emailsRow = getEmailsRow($db, $_GET["u"], $_GET["token"]);
if (empty($emailsRow)) {
    header("Location: ../");
}

$course = getCourseForCompetition($db, $emailsRow["id_competitions"], $_GET["u"]);

function getTeamPlayers($db, $teamId){
    $tablePrefix = "uid".$_GET['u'];
    $tp = $tablePrefix . 'teams_players';
    $p = $tablePrefix . 'players';
    $sql = "SELECT $p.id, $p.name FROM $p";
    $sql .= " LEFT JOIN $tp ON $tp.id_players = $p.id WHERE";
    $sql .= " $tp.id_teams = $teamId";
    $stmt= $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
$teamPlayers = getTeamPlayers($db, $emailsRow['id_teams']);
$teamPlayersTokens=[];
function getToken($db, $playersId, $competitionsId){
    $tableName = 'uid' . $_GET['u'] . 'emails';
    $sql = "SELECT token FROM $tableName WHERE";
    $sql .= " id_players = $playersId";
    $sql .= " AND id_competitions = $competitionsId";
    $stmt= $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result;
}
foreach($teamPlayers as $player){
    $token = getToken($db, $player['id'], $emailsRow['id_competitions']);
    $teamPlayersTokens[] = [
        'id' => $player['id'],
        'name' => $player['name'],
        'token' => $token
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../css/main.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            table, th, td {
                border: 1px solid;
            }
        </style>
        <title>Golf</title>
    </head>
    <body>
        <h1>Submit Your Scores</h1>
        <p>
            Please submit your course handicap and your scores for all the holes.
        </p>
        <p>
            You can also submit scores here for any other member of your team. If any score is submitted more than once, only the last one will be recorded.
        </p>
        <ul id='forms-ul'>
<?php
    foreach($teamPlayersTokens as $tPt){
        echo '<li>';
        echo "<h2 class='h2'>{$tPt['name']}</h2>"; 
        echo "<form style='display:none'>";
        echo '<fieldset>';
        echo "<label for='handicap'>Course Handicap</label>";
        echo "<input type='number' id='handicap{$tPt['id']}'";
        echo " class='handicap-input' name='handicap' min='0' max='50'";
        echo " data-player-id='{$tPt['id']}'";
        echo " data-token='{$tPt['token']}'";
        echo " data-uid='{$_GET['u']}'>";
        echo "<button id='handicap-btn{$tPt['id']}'>^</button>";
        echo '</fieldset>';
        echo '<table>';
        for ($i=1; $i<19; $i++) {
            $par = "h{$i}par";
            $si = "h{$i}si";
            echo "<tr>";
            echo "<td>$i</td>";
            echo "<td><input type='number' id='h$i' class='hole-input'";
            echo " name='h$i' form='scores-form'";
            echo " data-player-id='{$tPt['id']}'";
            echo " data-token='{$tPt['token']}'";
            echo " data-uid='{$_GET['u']}'";
            echo " data-hole-number='$i'";
            echo " data-hole-par='{$course[$par]}'";
            echo " data-hole-si='{$course[$si]}'";
            echo " min='1' max='9'></td>";
            echo "<td class='points-td'></td>";
            echo "<td class='submit-td'></td>";
            echo "</tr>";
        }
        echo '<tr><td></td><td></td><td id="total-points-td"></td></tr>';
        echo '</table>';
        echo '</form>';
    }

echo '<hr>';
echo '<p>$emailsRow</p>';
echo '<pre></code>';
print_r($emailsRow);
echo '</code></pre>';

echo '<hr>';
echo '<p>$course</p>';
echo '<pre></code>';
print_r($course);
echo '</code></pre>';

echo '<hr>';
echo '<p>$teamPlayers</p>';
echo '<pre></code>';
print_r($teamPlayers);
echo '</code></pre>';

echo '<hr>';
echo '<p>$teamPlayersTokens</p>';
echo '<pre></code>';
print_r($teamPlayersTokens);
echo '</code></pre>';
?>

<script type="module" src="./javascript/main.js"></script>
    </body>
</html>

