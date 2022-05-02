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

$uid = $_GET['u'];

$competitionId = $emailsRow['id_competitions'];

// not sure if this is correct for the person emailed??
$emailedPlayerId = $emailsRow['id_players'];

$teamId = $emailsRow['id_teams'];

$teamRow = getTeamsRow($db, $uid, $teamId);

require_once '../php/competition-data.php';

foreach($teams as $team){
    foreach($team['players'] as $player){
        if($player['idPlayers'] === $emailedPlayerId){
            $emailedPlayerName = $player['playerName'];
        }
    }
}

require_once '../header.php';
?>

        <title>Golf</title>
    </head>
    <body>
    <h1>Submit Scores for <?php echo $teamRow['name']; ?> </h1>
        <p>
        Hello <?php echo $emailedPlayerName; ?>. Please submit your course handicap and your scores for all the holes.
        </p>
        <p>
            You can also submit scores here for any other member of your team. If any score is submitted more than once, only the last one will be recorded.
        </p>
        <ul id='forms-ul'>
<?php
require_once '../php/scores-form.php';
foreach($teams as $team){
    if($team['idTeams'] === $teamId){
        foreach($team['players'] as $player){
            echo '<li>';
            echo "<h2 class='h2'>{$player['playerName']}</h2>";
            scoresForm($player, $course, $uid);
            echo '</li>';
        }
    }
}

echo '<hr>';

require_once '../php/leaderboard.php';
?>
        </ul>
        <hr>
<script src='../javascript/scores-form.js'></script>
<script type="module" src="./javascript/main.js"></script>
    </body>
</html>

