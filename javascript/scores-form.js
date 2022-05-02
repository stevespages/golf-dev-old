console.log('hallo from ../javascript/scores-form.js!!!');
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
    console.log("we got past the ifs!");
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
            if(result.success){
                uploadBtn.parentNode.innerHTML = '';
            }
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

const scoresForms = document.querySelectorAll('.scores-form');
// Calculate and display points on page load
scoresForms.forEach(function(el1){
    const holeInputs = el1.querySelectorAll('.hole-input');
    holeInputs.forEach(function(el2){
        const formId = el2.dataset.formId;
        console.log('formId: ', formId);
        const scoresForm = document.querySelector("#" + formId);
        console.log('scoresForm: ', scoresForm);
        const handicapInput = scoresForm.querySelectorAll('.handicap-input');
        console.log('handicapInput: ', handicapInput);
        const handicap = handicapInput[0].value;
        const score = el2.value;
        console.log('score: ', score);
        const holeNumber = el2.dataset.holeNumber;
        console.log('holeNumber: ', holeNumber);
        const holePar = el2.dataset.holePar;
        console.log('holePar: ', holePar);
        const holeSi = el2.dataset.holeSi;
        console.log('holeSi: ', holeSi);
        console.log('handicap: ', handicap);
        const points = calculatePoints(score, holeNumber, holePar, holeSi, handicap);
        console.log('points: ', points);
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
        console.log('formId: ', formId);
        const scoresForm = document.querySelector("#" + formId);
        console.log('scoresForm: ', scoresForm);
        const handicapInput = scoresForm.querySelectorAll('.handicap-input');
        console.log('handicapInput: ', handicapInput);
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
        // Having calculated and displayed a point sum them all for each form
        // It would be better to only sum the form that changed not all forms.
        sumPoints();
        /*
        const pointsTds = event.target.offsetParent.offsetParent.parentNode.querySelectorAll('.points-td');
        let totalPoints = 0;
        pointsTds.forEach(function(el){
            totalPoints += (el.innerText * 1);
        });
        event.target.offsetParent.offsetParent.parentNode.querySelector('#total-points-td').innerText = totalPoints;
        */
    });
});
