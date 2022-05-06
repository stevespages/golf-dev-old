/**
 * Updates the leaderboard handicaps
 *
 * @param object teams (global so no need to put in as arg)
 * @returns {void}
 */
// eslint complains that teams is not defined but it is global!
function updateLeaderboardHandicaps(teams){
    teams.forEach(function(team){
        team.players.forEach(function(player){
            const handicapSpanId = 'handicap-span-' + player.token.substring(0, 8);
            const handicapSpan = document.querySelector('#' + handicapSpanId);
            handicapSpan.innerHTML = player.handicap;
        });
    });
}

/**
 * Updates the leaderboard scores
 *
 * @param object teams (global so no need to put in as arg)
 * @returns {void}
 */
// eslint complains that teams is not defined but it is global!
function updateLeaderboardScores(teams){
    teams.forEach(function(team){
        team.players.forEach(function(player){
            const trScoresId = 'tr-scores-' + player.token.substring(0, 8);
            const trScoresTr = document.querySelector('#' + trScoresId);
            for(let hole = 1; hole < 19; hole++){
                const td = trScoresTr.querySelectorAll('.h' + hole);
                td[0].innerHTML = player['h' + hole];
            }
        });
    });
}

/**
 * calculates and updates the leaderboard points
 *
 * @param object teams (global so no need to put in as arg)
 * @returns {void}
 */
// eslint complains that teams is not defined but it is global!
function updateLeaderboardPoints(teams){
    teams.forEach(function(team){
        team.players.forEach(function(player){
            const trPointsId = 'tr-points-' + player.token.substring(0, 8);
            const trPointsTr = document.querySelector('#' + trPointsId);
            for(let hole = 1; hole < 19; hole++){
                const td = trPointsTr.querySelectorAll('.h' + hole);
                td[0].innerHTML = calculatePoints(player['h' + hole], hole, player.handicap);
            }
        });
    });
}

// run these functions on page load
updateLeaderboardHandicaps(teams);
updateLeaderboardScores(teams);
updateLeaderboardPoints(teams);

