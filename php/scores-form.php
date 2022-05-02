<?php
function scoresForm($player, $course, $uid){
    $formId = "x".substr($player['token'], 0, 8);
    echo "<form class='scores-form' id='$formId'>";
    echo '<fieldset>';
    echo "<label for='handicap'>Course Handicap</label>";
    echo "<input type='number'";
    echo " value='{$player['handicap']}'";
    echo " class='handicap-input' name='handicap' min='0' max='50'";
    echo " data-form-id='$formId'";
    echo " data-token='{$player['token']}'";
    echo " data-uid='{$uid}'>";
    echo "<button type='button' class='upload-handicap-btn'>^</button>";
    echo '</fieldset>';
    echo "<table class='player-table'>";
    for ($i=1; $i<19; $i++) {
        $par = "h{$i}par";
        $si = "h{$i}si";
        $hole = "h{$i}";
        echo "<tr>";
        echo "<td>$i</td>";
        echo "<td><input type='number' id='h$i' class='hole-input'";
        echo " name='h$i' form='scores-form'";
        echo " value='$player[$hole]'";
        echo " data-token='{$player['token']}'";
        echo " data-form-id='$formId'";
        echo " data-uid='{$uid}'";
        echo " data-hole-number='$i'";
        echo " data-hole-par='{$course[$par]}'";
        echo " data-hole-si='{$course[$si]}'";
        echo " min='1' max='9'></td>";
        echo "<td class='points-td'></td>";
        echo "<td class='submit-td'></td>";
        echo "</tr>";
    }
    echo "<tr><td></td><td></td><td id='total-points-td' class='total-points-td'></td></tr>";
    echo '</table>';
    echo '</form>';
}

