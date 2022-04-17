<?php
/**
 * root/competition-admin/index.php
 *
 * Main file for administering competition.
 *
 * Long File Doc Description
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

session_start();

/*
* if they are not logged in redirect to home page.
*/
//if(!isset($_SESSION['user'])){
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

require "../php/db-functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $competitionId = $_POST["competitionid"];
    include "./post-functions.php";
    if (isset($_POST["team-name"])) {
        insertTeam($db, $_POST["team-name"], $_POST["competitionid"]);
    }
    if (isset($_POST["select-player"])) {
        // assigns player to team
        uploadTeamPlayer($db, $_POST["teamid"], $_POST["select-player"],
            $_POST["competitionid"]);
        insertPlayerToScores($db, $_POST['competitionid'],
            $_POST['select-player']);
    }
    if (isset($_POST["remove-player"])) {
        removePlayer($db, $_POST["teamid"], $_POST["remove-player"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $competitionId = $_GET["competitionid"];
    include "./get-functions.php";
    if (isset($_GET["delete-teamid"])) {
        deleteTeam($db, $_GET["delete-teamid"]);
    }
    if (isset($_GET["send-all-emails"])) {
        $emailsToSend = prepareEmails($db, $competitionId);
        sendEmails($db, $emailsToSend, $competitionId);
    }
}

/**
 * Selects a row from uid23competitions table
 * 
 * @param object  $db      PDO Object
 * @param integer $competitionId derived from url query string parameter value
 * 
 * @return An array of rows from the table
 */
function getCompetitionRow($db, $competitionId)
{
    $tableName = "uid".$_SESSION["uid"]."competitions";
    $sql = "SELECT id, name, id_course, date, time FROM $tableName WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id", $competitionId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
$competitionRow = getCompetitionRow($db, $competitionId);

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
function getTeams($db, $idCompetitions)
{
    $tablePrefix = "uid".$_SESSION["uid"];
    $tp = $tablePrefix."teams_players";
    $t = $tablePrefix."teams";
    $p = $tablePrefix."players";
    $sql = "SELECT $t.id AS idTeams, $t.name AS teamName,";
    $sql .= " $tp.id_players AS idPlayers, $p.id AS idPlayers2,";
    $sql .= " $p.name AS playerName, $p.email AS email FROM $t LEFT JOIN";
    $sql .= " $tp ON $t.id = $tp.id_teams LEFT JOIN $p";
    $sql .= "  ON $p.id = $tp.id_players ";
    $sql .= "WHERE $t.id_competitions = $idCompetitions";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
$teams = getTeams($db, $competitionId);

// See sandbox/array-nesting/ to see how to make $teams more useful ie. nested.
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

// Now add scores to each player in $teamsNested
//foreach ($teamsNested as $team) {
//    foreach ($team['players'] as $player) {
//        if (!empty($scores[$team['idPlayers']) {
//            
//        }

$course = getCourseForCompetition($db, $competitionId, $_SESSION['uid']);

$players = getPlayers($db);
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
        <p><a href="../">Home</a></p>
        <h1>Competition Administration</h1>
        <h2>
<?php
echo $competitionRow["name"].", ";
echo $competitionRow["date"].", ".$competitionRow["time"];
?>      
        </h2>
        <a href="./?send-all-emails=yes&competitionid=<?php echo $competitionId ?>">
            Send Emails
        </a>
        <form method="post">
            <input type="text" id="create-team-input" 
                name="team-name" placeholder="Team Name">
            <input type="hidden" name="competitionid" value="<?php echo $competitionId; ?>">
            <input type="submit" value="Add">
        </form>
        <ul id="teams-ul">
<?php
foreach ($teamsNested as $tN) {
    echo "<li class='teams-name-li'>";
    echo $tN["teamName"];
    echo "<div class='teams-menu-div'>";
    echo " <a href='./?competitionid={$competitionId}&delete-teamid={$tN['idTeams']}'>Delete</a>";
    echo "<form method='post'>";
    echo "<select name='select-player'>";
    echo "<option disabled selected>Add Player</option>";
    foreach ($players as $player) {
        echo "<option value='{$player['id']}'>";
        echo $player["name"];
        echo "</option>";
    }
    echo "</select>";
    echo "<input type='hidden' name='teamid' value='{$tN['idTeams']}'>";
    echo "<input type='hidden' name='competitionid' value='{$competitionId}'>";
    echo "<input type='submit' value='Add'>";
    echo "</form>";
    echo "</div>";
    echo "<ul>";
    foreach ($tN["players"] as $teamPlayer) {
        echo "<li class='players-menu-li'>";
        echo $teamPlayer["playerName"];
        echo ", ";
        echo $teamPlayer["email"];
        echo "<div class='players-menu-div'>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='teamid' value='{$tN['idTeams']}'>";
        echo "<input type='hidden' name='competitionid' value='{$competitionId}'>";
        echo "<input type='hidden' name='remove-player' value='{$teamPlayer['idPlayers']}'>";
        echo "<input type='submit' value='remove'>";
        echo "</form>";
        echo "</div>";
        echo "</li>";
    }
    echo "</ul>";
    echo "</li>";
}

/**
 * Selects a row from uid23scores table
 * 
 * @param object  $db      PDO Object
 * @param integer $competitionId derived from url query string parameter value
 * 
 * @return array of rows from the table
 * 
 * this should be unnecessary once you add another JOIN to getTeams above
 */
function getScores($db, $competitionId)
{
    /*
    $tableName = "uid".$_SESSION["uid"]."scores";
    $sql = "SELECT id, id_players, h1, h2, h3, h4, h5, h6, h7, h8, h9,";
    $sql .= " h10, h11, h12, h13, h14, h15, h16, h17, h18";
    $sql .= " FROM $tableName WHERE id_competitions = :id";
     */
    $tablePrefix = "uid".$_SESSION['uid'];
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
$scores = getScores($db, $competitionId);
$playersScores = [];
foreach($scores as $score){
    $playersScores[$score['id_players']]['name'] = $score['name'];
    $playersScores[$score['id_players']]['handicap'] = $score['handicap'];
    $playersScores[$score['id_players']]['h1'] = $score['h1'];
    $playersScores[$score['id_players']]['h2'] = $score['h2'];
    $playersScores[$score['id_players']]['h3'] = $score['h3'];
    $playersScores[$score['id_players']]['h4'] = $score['h4'];
    $playersScores[$score['id_players']]['h5'] = $score['h5'];
    $playersScores[$score['id_players']]['h6'] = $score['h6'];
    $playersScores[$score['id_players']]['h7'] = $score['h7'];
    $playersScores[$score['id_players']]['h8'] = $score['h8'];
    $playersScores[$score['id_players']]['h9'] = $score['h9'];
    $playersScores[$score['id_players']]['h10'] = $score['h10'];
    $playersScores[$score['id_players']]['h11'] = $score['h11'];
    $playersScores[$score['id_players']]['h12'] = $score['h12'];
    $playersScores[$score['id_players']]['h13'] = $score['h13'];
    $playersScores[$score['id_players']]['h14'] = $score['h14'];
    $playersScores[$score['id_players']]['h15'] = $score['h15'];
    $playersScores[$score['id_players']]['h16'] = $score['h16'];
    $playersScores[$score['id_players']]['h17'] = $score['h17'];
    $playersScores[$score['id_players']]['h18'] = $score['h18'];
}

/**
 * calculates points from $course and $playersScores
 * 
 * @param INTEGER $holePar
 * @param INTEGER $holeSi
 * @param INTEGER $handicap
 * @param INTEGER $grossStrokes
 * 
 * @return INTEGER points
 */
function calculatePoints($holePar, $holeSi, $handicap, $grossStrokes)
{
    if (empty($grossStrokes)){
        return NULL;
    }
    $strokesGiven = 0;
    if ($holeSi <= $handicap) { $strokesGiven = $strokesGiven + 1; }
    if ($holeSi <= $handicap - 18) { $strokesGiven = $strokesGiven + 1; }
    if ($holeSi <= $handicap - 36) { $strokesGiven = $strokesGiven + 1; }
    $netStrokes = $grossStrokes - $strokesGiven;
    $difference = $netStrokes - $holePar;
    if ($difference <= -4) { $points = 6; }
    if ($difference == -3) { $points = 5; }
    if ($difference == -2) { $points = 4; }
    if ($difference == -1) { $points = 3; }
    if ($difference == 0) { $points = 2; }
    if ($difference == 1) { $points = 1; }
    if ($difference >= 2) { $points = 0; }
    return $points;
}
foreach($playersScores as $key => $ps){
    $playersScores[$key]['p1'] = calculatePoints($course['h1par'], $course['h1si'], $playersScores[$key]['handicap'], $playersScores[$key]['h1']);
    $playersScores[$key]['p2'] = calculatePoints($course['h2par'], $course['h2si'], $playersScores[$key]['handicap'], $playersScores[$key]['h2']);
    $playersScores[$key]['p3'] = calculatePoints($course['h3par'], $course['h3si'], $playersScores[$key]['handicap'], $playersScores[$key]['h3']);
    $playersScores[$key]['p4'] = calculatePoints($course['h4par'], $course['h4si'], $playersScores[$key]['handicap'], $playersScores[$key]['h4']);
    $playersScores[$key]['p5'] = calculatePoints($course['h5par'], $course['h5si'], $playersScores[$key]['handicap'], $playersScores[$key]['h5']);
    $playersScores[$key]['p6'] = calculatePoints($course['h6par'], $course['h6si'], $playersScores[$key]['handicap'], $playersScores[$key]['h6']);
    $playersScores[$key]['p7'] = calculatePoints($course['h7par'], $course['h7si'], $playersScores[$key]['handicap'], $playersScores[$key]['h7']);
    $playersScores[$key]['p8'] = calculatePoints($course['h8par'], $course['h8si'], $playersScores[$key]['handicap'], $playersScores[$key]['h8']);
    $playersScores[$key]['p9'] = calculatePoints($course['h9par'], $course['h9si'], $playersScores[$key]['handicap'], $playersScores[$key]['h9']);
    $playersScores[$key]['p10'] = calculatePoints($course['h10par'], $course['h10si'], $playersScores[$key]['handicap'], $playersScores[$key]['h10']);
    $playersScores[$key]['p11'] = calculatePoints($course['h11par'], $course['h11si'], $playersScores[$key]['handicap'], $playersScores[$key]['h11']);
    $playersScores[$key]['p12'] = calculatePoints($course['h12par'], $course['h12si'], $playersScores[$key]['handicap'], $playersScores[$key]['h12']);
    $playersScores[$key]['p13'] = calculatePoints($course['h13par'], $course['h13si'], $playersScores[$key]['handicap'], $playersScores[$key]['h13']);
    $playersScores[$key]['p14'] = calculatePoints($course['h14par'], $course['h14si'], $playersScores[$key]['handicap'], $playersScores[$key]['h14']);
    $playersScores[$key]['p15'] = calculatePoints($course['h15par'], $course['h15si'], $playersScores[$key]['handicap'], $playersScores[$key]['h15']);
    $playersScores[$key]['p16'] = calculatePoints($course['h16par'], $course['h16si'], $playersScores[$key]['handicap'], $playersScores[$key]['h16']);
    $playersScores[$key]['p17'] = calculatePoints($course['h17par'], $course['h17si'], $playersScores[$key]['handicap'], $playersScores[$key]['h17']);
    $playersScores[$key]['p18'] = calculatePoints($course['h18par'], $course['h18si'], $playersScores[$key]['handicap'], $playersScores[$key]['h18']);
}
?>
        </ul>
        <h2>Scores:</h2>
        <!-- Old Table
        <table>
        <tr>
            <th></th><th>1</th><th>2</th><th>3</th><th>4</th>
            <th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th>
            <th>11</th><th>12</th><th>13</th><th>14</th><th>15</th>
            <th>16</th><th>17</th><th>18</th>
        </tr>
        -->
<?php
/* Old Table
foreach ($scores as $score) {
    echo "<tr>";
    echo "<td>{$score['name']}";
    echo " ({$score['handicap']})</td>";
    echo "<td>{$score['h1']}</td>";
    echo "<td>{$score['h2']}</td>";
    echo "<td>{$score['h3']}</td>";
    echo "<td>{$score['h4']}</td>";
    echo "<td>{$score['h5']}</td>";
    echo "<td>{$score['h6']}</td>";
    echo "<td>{$score['h7']}</td>";
    echo "<td>{$score['h8']}</td>";
    echo "<td>{$score['h9']}</td>";
    echo "<td>{$score['h10']}</td>";
    echo "<td>{$score['h11']}</td>";
    echo "<td>{$score['h12']}</td>";
    echo "<td>{$score['h13']}</td>";
    echo "<td>{$score['h14']}</td>";
    echo "<td>{$score['h15']}</td>";
    echo "<td>{$score['h16']}</td>";
    echo "<td>{$score['h17']}</td>";
    echo "<td>{$score['h18']}</td>";
    echo "</tr>";
}
*/
?>
        <!-- Old Table
        </table>
        <hr>
        -->
        <table>
        <tr>
            <th></th><th>1</th><th>2</th><th>3</th><th>4</th>
            <th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th>
            <th>11</th><th>12</th><th>13</th><th>14</th><th>15</th>
            <th>16</th><th>17</th><th>18</th>
        </tr>
<?php
foreach ($teamsNested as $tN) {
    echo "<tr>";
    echo "<th>".$tN['teamName']."</th>";
    echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
    echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
    echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
    echo "</tr>";
    foreach ($tN['players'] as $p) {
        echo "<tr>";
        echo "<td>".$p['playerName'];
        if(!empty($playersScores[$p['idPlayers']])){
            echo "(".$playersScores[$p['idPlayers']]['handicap'].")";
        }
        echo "</td>";
        if(!empty($playersScores[$p['idPlayers']])){
            echo "<td>".$playersScores[$p['idPlayers']]['h1']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h2']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h3']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h4']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h5']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h6']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h7']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h8']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h9']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h10']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h11']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h12']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h13']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h14']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h15']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h16']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h17']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['h18']."</td>";
            echo "</tr>";
        }
        echo "<tr>";
        echo "<td>";
        echo "</td>";
        if(!empty($playersScores[$p['idPlayers']])){
            echo "<td>".$playersScores[$p['idPlayers']]['p1']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p2']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p3']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p4']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p5']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p6']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p7']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p8']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p9']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p10']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p11']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p12']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p13']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p14']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p15']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p16']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p17']."</td>";
            echo "<td>".$playersScores[$p['idPlayers']]['p18']."</td>";
            echo "</tr>";
        }
    }
}
?>
        </table>
<?php
echo "<hr>";
echo '<p>$players</p>';
echo "<pre><code>";
print_r($players);
echo "</code></pre>";
echo "<hr>";
echo '<p>$competitionRow</p>';
echo "<pre><code>";
echo print_r($competitionRow);
echo "</code></pre>";
echo "<hr>";
echo '<p>$teams</p>';
echo "<pre><code>";
echo print_r($teams);
echo "</code></pre>";
echo "<hr>";
echo '<p>$teamsNested</p>';
echo "<pre><code>";
echo print_r($teamsNested);
echo "</code></pre>";
echo "<hr>";
echo '<p>$scores</p>';
echo "<pre><code>";
echo print_r($scores);
echo "</code></pre>";
echo "<hr>";
echo '<p>$playersScores</p>';
echo "<pre><code>";
echo print_r($playersScores);
echo "</code></pre>";
echo "<hr>";
echo '<p>$course</p>';
echo "<pre><code>";
echo print_r($course);
echo "</code></pre>";
?>
<script type='module' src='javascript/main.js'></script>
    </body>
</html>
