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
        $trScoresId = "tr-scores-".substr($p['token'], 0, 8);
        $handicapSpanId = "handicap-span-".substr($p['token'], 0, 8);
        echo "<tr id='$trScoresId'>";
        echo "<td>".$p['playerName'];
        echo "(<span id='$handicapSpanId'>".$p['handicap']."</span>)";
        echo "</td>";
        echo "<td class='h1'>".$p['h1']."</td>";
        echo "<td class='h2'>".$p['h2']."</td>";
        echo "<td class='h3'>".$p['h3']."</td>";
        echo "<td class='h4'>".$p['h4']."</td>";
        echo "<td class='h5'>".$p['h5']."</td>";
        echo "<td class='h6'>".$p['h6']."</td>";
        echo "<td class='h7'>".$p['h7']."</td>";
        echo "<td class='h8'>".$p['h8']."</td>";
        echo "<td class='h9'>".$p['h9']."</td>";
        echo "<td class='h10'>".$p['h10']."</td>";
        echo "<td class='h11'>".$p['h11']."</td>";
        echo "<td class='h12'>".$p['h12']."</td>";
        echo "<td class='h13'>".$p['h13']."</td>";
        echo "<td class='h14'>".$p['h14']."</td>";
        echo "<td class='h15'>".$p['h15']."</td>";
        echo "<td class='h16'>".$p['h16']."</td>";
        echo "<td class='h17'>".$p['h17']."</td>";
        echo "<td class='h18'>".$p['h18']."</td>";
        echo "</tr>";
        $trPointsId = "tr-points-".substr($p['token'], 0, 8);
        echo "<tr id='$trPointsId'>";
        echo "<td>";
        echo "</td>";
            echo "<td class='h1' data-hole-number=1 data-hole-par='{$course['h1par']}' data-hole-si='{$course['h1si']}'>".calculatePoints($course['h1par'], $course['h1si'], $p['handicap'], $p['h1'])."</td>";
            echo "<td class='h2' data-hole-number=2 data-hole-par='{$course['h2par']}' data-hole-si='{$course['h2si']}'>".calculatePoints($course['h2par'], $course['h2si'], $p['handicap'], $p['h2'])."</td>";
            echo "<td class='h3' data-hole-number=3 data-hole-par='{$course['h3par']}' data-hole-si='{$course['h3si']}'>".calculatePoints($course['h3par'], $course['h3si'], $p['handicap'], $p['h3'])."</td>";
            echo "<td class='h4' data-hole-number=4 data-hole-par='{$course['h4par']}' data-hole-si='{$course['h4si']}'>".calculatePoints($course['h4par'], $course['h4si'], $p['handicap'], $p['h4'])."</td>";
            echo "<td class='h5' data-hole-number=5 data-hole-par='{$course['h5par']}' data-hole-si='{$course['h5si']}'>".calculatePoints($course['h5par'], $course['h5si'], $p['handicap'], $p['h5'])."</td>";
            echo "<td class='h6' data-hole-number=6 data-hole-par='{$course['h6par']}' data-hole-si='{$course['h6si']}'>".calculatePoints($course['h6par'], $course['h6si'], $p['handicap'], $p['h6'])."</td>";
            echo "<td class='h7' data-hole-number=7 data-hole-par='{$course['h7par']}' data-hole-si='{$course['h7si']}'>".calculatePoints($course['h7par'], $course['h7si'], $p['handicap'], $p['h7'])."</td>";
            echo "<td class='h8' data-hole-number=8 data-hole-par='{$course['h8par']}' data-hole-si='{$course['h8si']}'>".calculatePoints($course['h8par'], $course['h8si'], $p['handicap'], $p['h8'])."</td>";
            echo "<td class='h9' data-hole-number=9 data-hole-par='{$course['h9par']}' data-hole-si='{$course['h9si']}'>".calculatePoints($course['h9par'], $course['h9si'], $p['handicap'], $p['h9'])."</td>";
            echo "<td class='h10' data-hole-number=10 data-hole-par='{$course['h10par']}' data-hole-si='{$course['h10si']}'>".calculatePoints($course['h10par'], $course['h10si'], $p['handicap'], $p['h10'])."</td>";
            echo "<td class='h11' data-hole-number=11 data-hole-par='{$course['h11par']}' data-hole-si='{$course['h11si']}'>".calculatePoints($course['h11par'], $course['h11si'], $p['handicap'], $p['h11'])."</td>";
            echo "<td class='h12' data-hole-number=12 data-hole-par='{$course['h12par']}' data-hole-si='{$course['h12si']}'>".calculatePoints($course['h12par'], $course['h12si'], $p['handicap'], $p['h12'])."</td>";
            echo "<td class='h13' data-hole-number=13 data-hole-par='{$course['h13par']}' data-hole-si='{$course['h13si']}'>".calculatePoints($course['h13par'], $course['h13si'], $p['handicap'], $p['h13'])."</td>";
            echo "<td class='h14' data-hole-number=14 data-hole-par='{$course['h14par']}' data-hole-si='{$course['h14si']}'>".calculatePoints($course['h14par'], $course['h14si'], $p['handicap'], $p['h14'])."</td>";
            echo "<td class='h15' data-hole-number=15 data-hole-par='{$course['h15par']}' data-hole-si='{$course['h15si']}'>".calculatePoints($course['h15par'], $course['h15si'], $p['handicap'], $p['h15'])."</td>";
            echo "<td class='h16' data-hole-number=16 data-hole-par='{$course['h16par']}' data-hole-si='{$course['h16si']}'>".calculatePoints($course['h16par'], $course['h16si'], $p['handicap'], $p['h16'])."</td>";
            echo "<td class='h17' data-hole-number=17 data-hole-par='{$course['h17par']}' data-hole-si='{$course['h17si']}'>".calculatePoints($course['h17par'], $course['h17si'], $p['handicap'], $p['h17'])."</td>";
            echo "<td class='h18' data-hole-number=18 data-hole-par='{$course['h18par']}' data-hole-si='{$course['h18si']}'>".calculatePoints($course['h18par'], $course['h18si'], $p['handicap'], $p['h18'])."</td>";
            echo "</tr>";
    }
}
?>

        </table>

