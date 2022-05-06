/**
 * Calculates points from scores and par and stroke index for hole
 * @param {integer} score - The gross score for the hole
 * @param {integer} holeNumber - The number of the hole (1 - 18)
 * @param {integer} holePar - The par for the hole
 * @param {integer} holeSi - The stroke index for the hole
 * @param {integer} handicap - The player's handicap
 * @returns {integer} - The number of points scored
 */
function calculatePoints(score, holeNumber, holePar, holeSi, handicap)
{
    // score may be type string so convert to type integer
    score = parseInt(score, 10);
    // If score not successfully conterted to type integer return ""
    if(!Number.isInteger(score)){
        return "";
    }
    // If score not in the normal range for golf return ""
    if(score < 1 || score > 15){
        return "";
    }
    score = parseInt(score, 10);
    holePar = parseInt(holePar, 10);
    holeSi = parseInt(holeSi, 10);
    handicap = parseInt(handicap, 10);

    let strokesGiven = 0;

    if (holeSi <= handicap) {
        strokesGiven = strokesGiven + 1;
    }
    if (holeSi <= (handicap-18)) {
        strokesGiven = strokesGiven + 1;
    }
    if (holeSi <= (handicap-36)) {
        strokesGiven = strokesGiven + 1;
    }

    let netStrokes = score - strokesGiven;

    let difference = netStrokes - holePar;

    let points = 2;

    if (difference <= -4) {
        points = 6;
    }
    if (difference == -3) {
        points = 5;
    }
    if (difference == -2) {
        points = 4;
    }
    if (difference == -1) {
        points = 3;
    }
    if (difference == 0) {
        points = 2;
    }
    if (difference == 1) {
        points = 1;
    }
    if (difference >= 2) {
        points = 0;
    }

    return points;
}

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
            + '&hole-par=' + uploadBtn.dataset.holePar
            + '&hole-si=' + uploadBtn.dataset.holeSi
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
            updateLeaderboardScores(result['teams']);
            updateLeaderboardHandicaps(result['teams']);
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
            updateLeaderboardScores(result['teams']);
            updateLeaderboardHandicaps(result['teams']);
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

/**
 * Updates the leaderboard scores and points
 *
 * @param object teams
 * @returns {void}
 */
function updateLeaderboardScores(teams){
    console.log('Now update the leaderboard!');
    teams.forEach(function(team){
        team.players.forEach(function(player){
            console.log("player.token.substring(0, 8): ", player.token.substring(0, 8));
            const trScoresId = 'tr-scores-' + player.token.substring(0, 8);
            const trScoresTr = document.querySelector('#' + trScoresId);
            console.log('trScoresTr: ', trScoresTr);

            const trScoresH1Td = trScoresTr.querySelectorAll('.h1');
            trScoresH1Td[0].innerHTML = player.h1;
            console.log('trScoresH1Td[0]: ', trScoresH1Td[0]);

            const trScoresH2Td = trScoresTr.querySelectorAll('.h2');
            trScoresH2Td[0].innerHTML = player.h2;

            const trScoresH3Td = trScoresTr.querySelectorAll('.h3');
            trScoresH3Td[0].innerHTML = player.h3;
            
            const trScoresH4Td = trScoresTr.querySelectorAll('.h4');
            trScoresH4Td[0].innerHTML = player.h4;

            const trScoresH5Td = trScoresTr.querySelectorAll('.h5');
            trScoresH5Td[0].innerHTML = player.h5;
            
            const trScoresH6Td = trScoresTr.querySelectorAll('.h6');
            trScoresH6Td[0].innerHTML = player.h6;

            const trScoresH7Td = trScoresTr.querySelectorAll('.h7');
            trScoresH7Td[0].innerHTML = player.h7;

            const trScoresH8Td = trScoresTr.querySelectorAll('.h8');
            trScoresH8Td[0].innerHTML = player.h8;

            const trScoresH9Td = trScoresTr.querySelectorAll('.h9');
            trScoresH9Td[0].innerHTML = player.h9;

            const trScoresH10Td = trScoresTr.querySelectorAll('.h10');
            trScoresH10Td[0].innerHTML = player.h10;

            const trScoresH11Td = trScoresTr.querySelectorAll('.h11');
            trScoresH11Td[0].innerHTML = player.h11;

            const trScoresH12Td = trScoresTr.querySelectorAll('.h12');
            trScoresH12Td[0].innerHTML = player.h12;

            const trScoresH13Td = trScoresTr.querySelectorAll('.h13');
            trScoresH13Td[0].innerHTML = player.h13;

            const trScoresH14Td = trScoresTr.querySelectorAll('.h14');
            trScoresH14Td[0].innerHTML = player.h14;

            const trScoresH15Td = trScoresTr.querySelectorAll('.h15');
            trScoresH15Td[0].innerHTML = player.h15;

            const trScoresH16Td = trScoresTr.querySelectorAll('.h16');
            trScoresH16Td[0].innerHTML = player.h16;

            const trScoresH17Td = trScoresTr.querySelectorAll('.h17');
            trScoresH17Td[0].innerHTML = player.h17;

            const trScoresH18Td = trScoresTr.querySelectorAll('.h18');
            trScoresH18Td[0].innerHTML = player.h18;

            const trPointsId = 'tr-points-' + player.token.substring(0, 8);
            const trPointsTr = document.querySelector('#' + trPointsId);

            const trPointsH1Td = trPointsTr.querySelectorAll('.h1');
            let holeNumber = trPointsH1Td[0].dataset.holeNumber;
            let holePar = trPointsH1Td[0].dataset.holePar;
            let holeSi = trPointsH1Td[0].dataset.holeSi;
            trPointsH1Td[0].innerHTML = calculatePoints(player.h1, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH2Td = trPointsTr.querySelectorAll('.h2');
            holeNumber = trPointsH2Td[0].dataset.holeNumber;
            holePar = trPointsH2Td[0].dataset.holePar;
            holeSi = trPointsH2Td[0].dataset.holeSi;
            trPointsH2Td[0].innerHTML = calculatePoints(player.h2, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH3Td = trPointsTr.querySelectorAll('.h3');
            holeNumber = trPointsH3Td[0].dataset.holeNumber;
            holePar = trPointsH3Td[0].dataset.holePar;
            holeSi = trPointsH3Td[0].dataset.holeSi;
            trPointsH3Td[0].innerHTML = calculatePoints(player.h3, holeNumber, holePar, holeSi, player.handicap);
            console.log('trPointsH3Td[0]: ', trPointsH3Td[0]);

            const trPointsH4Td = trPointsTr.querySelectorAll('.h4');
            holeNumber = trPointsH4Td[0].dataset.holeNumber;
            holePar = trPointsH4Td[0].dataset.holePar;
            holeSi = trPointsH4Td[0].dataset.holeSi;
            trPointsH4Td[0].innerHTML = calculatePoints(player.h4, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH5Td = trPointsTr.querySelectorAll('.h5');
            holeNumber = trPointsH5Td[0].dataset.holeNumber;
            holePar = trPointsH5Td[0].dataset.holePar;
            holeSi = trPointsH5Td[0].dataset.holeSi;
            trPointsH5Td[0].innerHTML = calculatePoints(player.h5, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH6Td = trPointsTr.querySelectorAll('.h6');
            holeNumber = trPointsH6Td[0].dataset.holeNumber;
            holePar = trPointsH6Td[0].dataset.holePar;
            holeSi = trPointsH6Td[0].dataset.holeSi;
            trPointsH6Td[0].innerHTML = calculatePoints(player.h6, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH7Td = trPointsTr.querySelectorAll('.h7');
            holeNumber = trPointsH7Td[0].dataset.holeNumber;
            holePar = trPointsH7Td[0].dataset.holePar;
            holeSi = trPointsH7Td[0].dataset.holeSi;
            trPointsH7Td[0].innerHTML = calculatePoints(player.h7, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH8Td = trPointsTr.querySelectorAll('.h8');
            holeNumber = trPointsH8Td[0].dataset.holeNumber;
            holePar = trPointsH8Td[0].dataset.holePar;
            holeSi = trPointsH8Td[0].dataset.holeSi;
            trPointsH8Td[0].innerHTML = calculatePoints(player.h8, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH9Td = trPointsTr.querySelectorAll('.h9');
            holeNumber = trPointsH9Td[0].dataset.holeNumber;
            holePar = trPointsH9Td[0].dataset.holePar;
            holeSi = trPointsH9Td[0].dataset.holeSi;
            trPointsH9Td[0].innerHTML = calculatePoints(player.h9, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH10Td = trPointsTr.querySelectorAll('.h10');
            holeNumber = trPointsH10Td[0].dataset.holeNumber;
            holePar = trPointsH10Td[0].dataset.holePar;
            holeSi = trPointsH10Td[0].dataset.holeSi;
            trPointsH10Td[0].innerHTML = calculatePoints(player.h10, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH11Td = trPointsTr.querySelectorAll('.h11');
            holeNumber = trPointsH11Td[0].dataset.holeNumber;
            holePar = trPointsH11Td[0].dataset.holePar;
            holeSi = trPointsH11Td[0].dataset.holeSi;
            trPointsH11Td[0].innerHTML = calculatePoints(player.h11, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH12Td = trPointsTr.querySelectorAll('.h12');
            holeNumber = trPointsH12Td[0].dataset.holeNumber;
            holePar = trPointsH12Td[0].dataset.holePar;
            holeSi = trPointsH12Td[0].dataset.holeSi;
            trPointsH12Td[0].innerHTML = calculatePoints(player.h12, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH13Td = trPointsTr.querySelectorAll('.h13');
            holeNumber = trPointsH13Td[0].dataset.holeNumber;
            holePar = trPointsH13Td[0].dataset.holePar;
            holeSi = trPointsH13Td[0].dataset.holeSi;
            trPointsH13Td[0].innerHTML = calculatePoints(player.h13, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH14Td = trPointsTr.querySelectorAll('.h14');
            holeNumber = trPointsH14Td[0].dataset.holeNumber;
            holePar = trPointsH14Td[0].dataset.holePar;
            holeSi = trPointsH14Td[0].dataset.holeSi;
            trPointsH14Td[0].innerHTML = calculatePoints(player.h14, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH15Td = trPointsTr.querySelectorAll('.h15');
            holeNumber = trPointsH15Td[0].dataset.holeNumber;
            holePar = trPointsH15Td[0].dataset.holePar;
            holeSi = trPointsH15Td[0].dataset.holeSi;
            trPointsH15Td[0].innerHTML = calculatePoints(player.h15, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH16Td = trPointsTr.querySelectorAll('.h16');
            holeNumber = trPointsH16Td[0].dataset.holeNumber;
            holePar = trPointsH16Td[0].dataset.holePar;
            holeSi = trPointsH16Td[0].dataset.holeSi;
            trPointsH16Td[0].innerHTML = calculatePoints(player.h16, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH17Td = trPointsTr.querySelectorAll('.h17');
            holeNumber = trPointsH17Td[0].dataset.holeNumber;
            holePar = trPointsH17Td[0].dataset.holePar;
            holeSi = trPointsH17Td[0].dataset.holeSi;
            trPointsH17Td[0].innerHTML = calculatePoints(player.h17, holeNumber, holePar, holeSi, player.handicap);

            const trPointsH18Td = trPointsTr.querySelectorAll('.h18');
            holeNumber = trPointsH18Td[0].dataset.holeNumber;
            holePar = trPointsH18Td[0].dataset.holePar;
            holeSi = trPointsH18Td[0].dataset.holeSi;
            trPointsH18Td[0].innerHTML = calculatePoints(player.h18, holeNumber, holePar, holeSi, player.handicap);

        });
        console.log('team: ', team);
    });
}

/**
 * Updates the leaderboard handicaps
 *
 * @param object teams
 * @returns {void}
 */
function updateLeaderboardHandicaps(teams){
    console.log('Now update the leaderboard!');
    teams.forEach(function(team){
        team.players.forEach(function(player){
            const handicapSpanId = 'handicap-span-' + player.token.substring(0, 8);
            const handicapSpan = document.querySelector('#' + handicapSpanId);
            handicapSpan.innerHTML = player.handicap;
        });
    });
}


const scoresForms = document.querySelectorAll('.scores-form');
// Calculate and display points on page load
scoresForms.forEach(function(el1){
    const holeInputs = el1.querySelectorAll('.hole-input');
    holeInputs.forEach(function(el2){
        const formId = el2.dataset.formId;
        const scoresForm = document.querySelector("#" + formId);
        const handicapInput = scoresForm.querySelectorAll('.handicap-input');
        const handicap = handicapInput[0].value;
        const score = el2.value;
        const holeNumber = el2.dataset.holeNumber;
        const holePar = el2.dataset.holePar;
        const holeSi = el2.dataset.holeSi;
        const points = calculatePoints(score, holeNumber, holePar, holeSi, handicap);
        el2.offsetParent.nextSibling.innerHTML = points;
    });
});

// Once points have been calculated and displayed sum them for each form
sumPoints();

// const handicapInputs = document.querySelectorAll('.handicap-input');

// const scoresForms = document.querySelectorAll('.scores-form');

/**
 * Listen to click event on formsUl
 *
 * @type {HTMLElement} - the ul with the forms in it
 * @listens document#click - clicks in formsUl
 */
scoresForms.forEach(function(scoreForm){
    scoreForm.addEventListener('click', function(event){
        if(event.target.classList.contains('upload-btn')){
            const uploadBtns = document.querySelectorAll('.upload-btn');
            uploadBtns.forEach(function(uploadBtn){
                uploadScore(uploadBtn);
            });
        }
        if(event.target.classList.contains('upload-handicap-btn')){
            const handicapInput = event.target.parentNode.querySelector('.handicap-input');
            uploadHandicap(handicapInput);
        }
    });
});
const holeInputs = document.querySelectorAll('.hole-input');

holeInputs.forEach(holeInput => {
    holeInput.addEventListener('input', function(event){
        const formId = holeInput.dataset.formId;
        const scoresForm = document.querySelector("#" + formId);
        const handicapInput = scoresForm.querySelectorAll('.handicap-input');
        const handicap = handicapInput[0].value;
        const score = event.target.value;
        const holeNumber = event.target.dataset.holeNumber;
        const holePar = event.target.dataset.holePar;
        const holeSi = event.target.dataset.holeSi;
        const token = event.target.dataset.token;
        const uid = event.target.dataset.uid;
        event.target.offsetParent.nextSibling.innerHTML
            = calculatePoints(score, holeNumber, holePar, holeSi, handicap);
        const submitBtn = document.createElement('button');
        submitBtn.setAttribute('type', 'button');
        if(event.target.offsetParent.nextSibling.nextSibling.innerText === ''){
            submitBtn.classList.add('upload-btn');
            submitBtn.setAttribute('data-score', score);
            submitBtn.setAttribute('data-hole-number', holeNumber);
            submitBtn.setAttribute('data-hole-par', holePar);
            submitBtn.setAttribute('data-hole-si', holeSi);
            submitBtn.setAttribute('data-handicap', handicap);
            submitBtn.setAttribute('data-token', token);
            submitBtn.setAttribute('data-uid', uid);
            submitBtn.innerText = '^';
            event.target.offsetParent.nextSibling.nextSibling.appendChild(submitBtn);
        }
        if(event.target.value !== ''){
            event.target.blur();
        }
        // Having calculated and displayed a point, sum them all for each form
        // It would be better to only sum the form that changed not all forms.
        sumPoints();
    });
});

