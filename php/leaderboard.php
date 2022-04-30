<?php

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

/*
function assignScores($scores){
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
    return $playersScores;
}

$playersScores = assignScores($scores);

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
 */
?>

        <h2>Leaderboard</h2>
        <table id='leaderboard-table'>
            <tr>
                <th></th><th>1&nbsp;</th><th>2&nbsp;</th><th>3&nbsp;</th><th>4&nbsp;</th>
                <th>5&nbsp;</th><th>6&nbsp;</th><th>7&nbsp;</th><th>8&nbsp;</th><th>9&nbsp;</th><th>10</th>
                <th>11</th><th>12</th><th>13</th><th>14</th><th>15</th>
                <th>16</th><th>17</th><th>18</th>
            </tr>

<?php
foreach ($teams as $team) {
    echo "<tr>";
    echo "<th>".$team['teamName']."</th>";
    echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
    echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
    echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
    echo "</tr>";
    foreach ($team['players'] as $p) {
        echo "<tr>";
        echo "<td>".$p['playerName'];
        echo "(".$p['handicap'].")";
        echo "</td>";
            echo "<td>".$p['h1']."</td>";
            echo "<td>".$p['h2']."</td>";
            echo "<td>".$p['h3']."</td>";
            echo "<td>".$p['h4']."</td>";
            echo "<td>".$p['h5']."</td>";
            echo "<td>".$p['h6']."</td>";
            echo "<td>".$p['h7']."</td>";
            echo "<td>".$p['h8']."</td>";
            echo "<td>".$p['h9']."</td>";
            echo "<td>".$p['h10']."</td>";
            echo "<td>".$p['h11']."</td>";
            echo "<td>".$p['h12']."</td>";
            echo "<td>".$p['h13']."</td>";
            echo "<td>".$p['h14']."</td>";
            echo "<td>".$p['h15']."</td>";
            echo "<td>".$p['h16']."</td>";
            echo "<td>".$p['h17']."</td>";
            echo "<td>".$p['h18']."</td>";
            echo "</tr>";
        echo "<tr>";
        echo "<td>";
        echo "</td>";
            echo "<td>".calculatePoints($course['h1par'], $course['h1si'], $p['handicap'], $p['h1'])."</td>";
            echo "<td>".calculatePoints($course['h2par'], $course['h2si'], $p['handicap'], $p['h2'])."</td>";
            echo "<td>".calculatePoints($course['h3par'], $course['h3si'], $p['handicap'], $p['h3'])."</td>";
            echo "<td>".calculatePoints($course['h4par'], $course['h4si'], $p['handicap'], $p['h4'])."</td>";
            echo "<td>".calculatePoints($course['h5par'], $course['h5si'], $p['handicap'], $p['h5'])."</td>";
            echo "<td>".calculatePoints($course['h6par'], $course['h6si'], $p['handicap'], $p['h6'])."</td>";
            echo "<td>".calculatePoints($course['h7par'], $course['h7si'], $p['handicap'], $p['h7'])."</td>";
            echo "<td>".calculatePoints($course['h8par'], $course['h8si'], $p['handicap'], $p['h8'])."</td>";
            echo "<td>".calculatePoints($course['h9par'], $course['h9si'], $p['handicap'], $p['h9'])."</td>";
            echo "<td>".calculatePoints($course['h10par'], $course['h10si'], $p['handicap'], $p['h10'])."</td>";
            echo "<td>".calculatePoints($course['h11par'], $course['h11si'], $p['handicap'], $p['h11'])."</td>";
            echo "<td>".calculatePoints($course['h12par'], $course['h12si'], $p['handicap'], $p['h12'])."</td>";
            echo "<td>".calculatePoints($course['h13par'], $course['h13si'], $p['handicap'], $p['h13'])."</td>";
            echo "<td>".calculatePoints($course['h14par'], $course['h14si'], $p['handicap'], $p['h14'])."</td>";
            echo "<td>".calculatePoints($course['h15par'], $course['h15si'], $p['handicap'], $p['h15'])."</td>";
            echo "<td>".calculatePoints($course['h16par'], $course['h16si'], $p['handicap'], $p['h16'])."</td>";
            echo "<td>".calculatePoints($course['h17par'], $course['h17si'], $p['handicap'], $p['h17'])."</td>";
            echo "<td>".calculatePoints($course['h18par'], $course['h18si'], $p['handicap'], $p['h18'])."</td>";
            echo "</tr>";
    }
}
?>

        </table>

