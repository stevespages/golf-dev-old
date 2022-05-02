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

// necessary because sessions not available in submit-scores and
// functions have to work with that as well.
$uid = $_SESSION['uid'];

require_once '../php/competition-data.php';

// only in competition-admin not in submit-scores
$players = getPlayers($db, $uid);

require_once '../header.php';
?>
        <title>Golf</title>
    </head>
    <body>
        <p><a href="../">Home</a></p>
        <h1>Competition Administration</h1>
        <h2>
<?php
echo $competition["name"].", ".$course['name'].", ";
echo $competition["date"].", ".$competition["time"];
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
require_once '../php/scores-form.php';
foreach ($teams as $team) {
    echo "<li class='teams-name-li'>";
    echo $team['teamName'];
    echo "<div class='teams-menu-div'>";
    echo " <a href='./?competitionid={$competitionId}&delete-teamid={$team['idTeams']}'>Delete</a>";
    echo "<form method='post'>";
    echo "<select name='select-player'>";
    echo '<option disabled selected>Add Player</option>';
    foreach ($players as $player) {
        echo "<option value='{$player['id']}'>";
        echo $player['name'];
        echo '</option>';
    }
    echo '</select>';
    echo "<input type='hidden' name='teamid' value='{$team['idTeams']}'>";
    echo "<input type='hidden' name='competitionid' value='{$competitionId}'>";
    echo "<input type='submit' value='Add'>";
    echo '</form>';
    echo '</div>';
    echo "<ul>";
    foreach ($team["players"] as $teamPlayer) {
        echo "<li class='players-menu-li'>";
        echo $teamPlayer["playerName"];
        echo ', ';
        echo $teamPlayer["email"];
        echo "<div class='players-menu-div'>";
        echo "<div class='delete-div'>";
        echo "<button class='delete-btn'>Remove</button>";
        echo "<button class='cancel-btn' style='display:none'>Cancel</button>";
        echo "<div class='confirm-div' style='display:none'>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='teamid' value='{$team['idTeams']}'>";
        echo "<input type='hidden' name='competitionid' value='{$competitionId}'>";
        echo "<input type='hidden' name='remove-player' value='{$teamPlayer['idPlayers']}'>";
        echo "<input type='submit' value='Confirm'>";
        echo '</form>';
        echo '</div>'; // confirm-div
        echo '</div>';
        echo "<div class='add-scores-div'>";
        scoresForm($teamPlayer, $course, $_SESSION['uid']);
        echo '</div>';
        echo '</div>';
        echo '</li>';
    }
    echo '</ul>';
    echo '</li>';
}
?>
        </ul>
        <hr>

<?php

require_once '../php/leaderboard.php';

?>
<script src='../javascript/general.js'></script>
<script src='../javascript/scores-form.js'></script>
<script type='module' src='javascript/main.js'></script>
    </body>
</html>

