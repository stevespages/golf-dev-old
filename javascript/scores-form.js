console.log('an empty javascript/scores-form.js');
/**
 * Uses ajax to upload a player's score for a single hole
 * The button param's data attributes derive from the input element
 * @param {Object} uploadBtn - Button clicked by user (should be uploadScoreBtn)
 * @returns {void} - May need to return success or failure??
 */
function uploadScore(uploadBtn){
    const xhr = new XMLHttpRequest();
    xhr.open(
        'GET',
        '../php/ajax.php?'
            + 'calling-function=upload-score'
            + '&score=' + uploadBtn.dataset.score
            + '&hole-number=' + uploadBtn.dataset.holeNumber
            + '&handicap=' + uploadBtn.dataset.handicap
            + '&token=' + uploadBtn.dataset.token
            + '&uid=' + uploadBtn.dataset.uid,
        true
    );
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function(){
        if(xhr.readyState === 4 && xhr.status === 200){
            // result should be Bool success or failure
            const response = xhr.responseText;
            const result = JSON.parse(response);
            console.log('result: ', result);
            if(result.success){
                uploadBtn.parentNode.innerHTML = '';
            }
            updateLeaderboardHandicaps(result['teams']);
            updateLeaderboardScores(result['teams']);
            updateLeaderboardPoints(result['teams']);
        }
    };
    xhr.send();
}

/**
 * Uses ajax to upload a player's handicap
 * @param {Object} uploadHandicapBtn - Button clicked by user
 * @returns {void} - May need to return success or failure??
 */
function uploadHandicap(handicapInput){
    const xhr = new XMLHttpRequest();
    xhr.open(
        'GET',
        '../php/ajax.php?'
            + 'calling-function=upload-handicap'
            + '&handicap=' + handicapInput.value
            + '&token=' + handicapInput.dataset.token
            + '&uid=' + handicapInput.dataset.uid,
        true
    );
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function(){
        if(xhr.readyState === 4 && xhr.status === 200){
            // result should be Bool success or failure
            const response = xhr.responseText;
            const result = JSON.parse(response);
            if(result.success){
                // we need to hide this upload-handicap-button
                //uploadHandicapBtn.parentNode.innerHTML = '';
            }
            updateLeaderboardHandicaps(result['teams']);
            updateLeaderboardScores(result['teams']);
            updateLeaderboardPoints(result['teams']);
        }
    };
    xhr.send();
}

/**
 * Sums the points for all forms and displays the total for each form
 *
 * @returns {void}
 */
function sumPoints(){
    const playerTables = document.querySelectorAll('.player-table');
    playerTables.forEach(function(playerTable){
        const playerTableScores = playerTable.querySelectorAll('.points-td');
        let totalPoints = 0;
        playerTableScores.forEach(function(el){
            totalPoints += (el.innerText * 1);
        });
        const totalPointsTd = playerTable.querySelectorAll('.total-points-td');
        totalPointsTd[0].innerText = totalPoints;
    });
}

