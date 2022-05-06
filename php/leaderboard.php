        <h2>Leaderboard</h2>
        <table id='leaderboard-table'>
<?php
echo '<tr>';
echo '<th></th>';
// I am attempting to make a space with &nbsp; for single digit numbers
// This is so they take the same space as the double digit numbers
// It does not seem to be working
for($hole = 1; $hole <10; $hole++){
    echo "<th>$hole&nbsp;</th>";
}
for($hole = 10; $hole <19; $hole++){
    echo "<th>$hole</th>";
}
echo '</tr>';
foreach ($teams as $team) {
    echo "<tr>";
    echo "<th>".$team['teamName']."</th>";
    for($hole = 1; $hole < 19; $hole++){
        echo '<td></td>';
    }
    echo "</tr>";
    foreach ($team['players'] as $p) {
        $trScoresId = "tr-scores-".substr($p['token'], 0, 8);
        $handicapSpanId = "handicap-span-".substr($p['token'], 0, 8);
        echo "<tr id='$trScoresId'>";
        echo '<td>'.$p['playerName'];
        echo "(<span id='$handicapSpanId'></span>)";
        echo '</td>';
        for($hole = 1; $hole <19; $hole++){
            echo "<td class='h$hole'></td>";
        }
        echo '</tr>';
        $trPointsId = "tr-points-".substr($p['token'], 0, 8);
        echo "<tr id='$trPointsId'>";
        echo '<td></td>';
        for($hole = 1; $hole <19; $hole++){
            echo "<td class='h$hole'></td>";
        }
    }
}
?>
        </table>

