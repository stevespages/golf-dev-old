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

require_once "../php/db-functions.php";

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

$teams = getTeams($db, $competitionId, $_SESSION['uid']);

$teamsNested = nestTeams($teams);

$course = getCourseForCompetition($db, $competitionId, $_SESSION['uid']);

$players = getPlayers($db);

require_once '../header.php';
?>
        <title>Golf</title>
    </head>
    <body>
        <p><a href="../">Home</a></p>
        <h1>Competition Administration</h1>
        <h2>
<?php
echo $competitionRow["name"].", ".$course['name'].", ";
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

?>
        </ul>
        <hr>

<?php

$scores = getScores($db, $competitionId, $_SESSION['uid']);

require_once '../php/leaderboard.php';

?>

<?php
echo "<hr>";
echo '<p>$players</p>';
echo "<pre><code>";
print_r($players);
echo "</code></pre>";

echo "<hr>";
echo '<p>$competitionRow</p>';
echo "<pre><code>";
print_r($competitionRow);
echo "</code></pre>";

echo "<hr>";
echo '<p>$teams</p>';
echo "<pre><code>";
print_r($teams);
echo "</code></pre>";

echo "<hr>";
echo '<p>$teamsNested</p>';
echo "<pre><code>";
print_r($teamsNested);
echo "</code></pre>";

echo "<hr>";
echo '<p>$scores</p>';
echo "<pre><code>";
print_r($scores);
echo "</code></pre>";

echo "<hr>";
echo '<p>$playersScores</p>';
echo "<pre><code>";
print_r($playersScores);
echo "</code></pre>";

echo "<hr>";
echo '<p>$course</p>';
echo "<pre><code>";
print_r($course);
echo "</code></pre>";
?>
<script type='module' src='javascript/main.js'></script>
    </body>
</html>
