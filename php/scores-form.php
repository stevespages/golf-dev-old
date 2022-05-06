<?php
function scoresForm($player, $uid){
    $formId = "scores-form-".substr($player['token'], 0, 8);
    echo "<form class='scores-form' id='$formId'>";
    echo '<fieldset>';
    echo "<label for='handicap'>Course Handicap</label>";
    echo "<input type='number'";
    echo " class='handicap-input' name='handicap' min='0' max='50'";
    echo " data-token='{$player['token']}'";
    echo " data-uid='{$uid}'>";
    echo "<button type='button' class='upload-handicap-btn'>^</button>";
    echo '</fieldset>';
    echo "<table class='player-table'>";
    for ($hole=1; $hole<19; $hole++) {
        $h = "h{$hole}";
        echo "<tr>";
        echo "<td>$h</td>";
        echo "<td><input type='number' class='hole-input h$h'";
        echo " name='h$h' form='scores-form'";
        echo " data-token='{$player['token']}'";
        echo " data-uid='{$uid}'";
        echo " data-hole-number='$h'";
        echo " min='1' max='9'></td>";
        echo "<td class='points-td'></td>";
        echo "<td class='submit-td'></td>";
        echo "</tr>";
    }
    echo "<tr><td></td><td></td><td id='total-points-td' class='total-points-td'></td></tr>";
    echo '</table>';
    echo '</form>';
}

