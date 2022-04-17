function calculatePoints(score, holeNumber, holePar, holeSi, handicap)
{
    score = parseInt(score, 10);
    holePar = parseInt(holePar, 10);
    holeSi = parseInt(holeSi, 10);
    handicap = parseInt(handicap, 10);

    console.log("score: ", score);
    console.log("holeNumber: ", holeNumber);
    console.log("holePar: ", holePar);
    console.log("holeSi: ", holeSi);
    console.log("handicap: ", handicap);

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

    console.log("strokesGiven: ", strokesGiven);
    console.log("netStrokes: ", netStrokes);
    console.log("difference: ", difference);
    console.log("points: ", points);

    console.log("typeof(strokesGiven: ", typeof(strokesGiven));
    console.log("typeof(netStrokes: ", typeof(netStrokes));
    console.log("typeof(difference: ", typeof(difference));
    console.log("typeof(points: ", typeof(points));

    return points;
}
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
        }
    }
    xhr.send();
}

function uploadHandicap(uploadHandicapBtn){
    const xhr = new XMLHttpRequest();
    xhr.open(
        'GET',
        '../php/ajax.php?'
            + 'calling-function=upload-handicap'
            + '&handicap=' + uploadHandicapBtn.dataset.handicap
            + '&token=' + uploadHandicapBtn.dataset.token
            + '&uid=' + uploadHandicapBtn.dataset.uid,
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
                uploadHandicapBtn.parentNode.innerHTML = '';
            }
        }
    }
    xhr.send();
}

const formsUl = document.querySelector('#forms-ul');

const holeInputs = document.querySelectorAll('.hole-input');
console.log('holeInputs: ', holeInputs);

const handicapInputs = document.querySelectorAll('.handicap-input');
console.log('handicapInputs: ', handicapInputs);

formsUl.addEventListener('click', function(event){
    console.log(event);
    if(event.target.classList.contains('h2')){
        console.log('you clicked on an h2!!');
        const form = event.target.nextSibling;
        if(form.getAttribute('style') === 'display:block'){
            form.setAttribute('style', 'display:none');
            return;
        }
        console.log(document.querySelectorAll('#forms-ul form'));
        const allForms = document.querySelectorAll('#forms-ul form');
        allForms.forEach(el => el.setAttribute('style', 'display:none'));
        form.setAttribute('style', 'display:block');
    }
    if(event.target.classList.contains('upload-btn')){
        const uploadBtns = document.querySelectorAll('.upload-btn');
        console.log('uploadBtns: ', uploadBtns);
        uploadBtns.forEach(function(uploadBtn){
            uploadScore(uploadBtn);
        });
    }
});

holeInputs.forEach(holeInput => {
    holeInput.addEventListener('input', function(event){
        const handicap = document.querySelector('#handicap' + event.target.dataset.playerId).value;
        const score = event.target.value;
        const holeNumber = event.target.dataset.holeNumber;
        const holePar = event.target.dataset.holePar;
        const holeSi = event.target.dataset.holeSi;
        const token = event.target.dataset.token;
        const uid = event.target.dataset.uid;
        console.log('input event: ', event);
        console.log('params: ',
            event.target.value, '|',
            event.target.dataset.holeNumber, '|',
            event.target.dataset.holePar, '|',
            event.target.dataset.holeSi, '|',
            handicap.value, '|',
            calculatePoints(score, holeNumber, holePar, holeSi, handicap)
        );
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
            console.log('submitBtn: ', submitBtn);
            event.target.offsetParent.nextSibling.nextSibling.appendChild(submitBtn);
        }
        if(event.target.value !== ''){
            event.target.blur();
        }
        console.log('?: ', event.target.offsetParent.offsetParent.parentNode.querySelector('#total-points-td'));
        const pointsTds = event.target.offsetParent.offsetParent.parentNode.querySelectorAll('.points-td');
        console.log('pointsTds: ', pointsTds);
        let totalPoints = 0;
        pointsTds.forEach(function(el){
            totalPoints += (el.innerText * 1);
        });

        event.target.offsetParent.offsetParent.parentNode.querySelector('#total-points-td').innerText = totalPoints;
    });
});

// This is for showing handicap submit button
// This is not for capturing the value for uploading
// The handicap submit button should be displaye initially
// On clicking and success from ajax it should disappear
// The value entered should still be displayed in the box
// If user inputs into input the button should be recreated
// Implement this after implementing upload of handicap to Scores
/*
handicapInputs.forEach(handicapInput => {
    handicapInput.addEventListener('input', function(event){
        // this line means there must never be a team greater than 9 members
        // Would be better to use dataset for player_id
        // to get the numerical part of the id:
        // numericalPart = event.target.id.replace(/\D/g,'');
        if(!document.querySelector("#handicap-btn" + event.target.id.slice(-1))){
            console.log('handicapInput event: ', event);
            const handicap = event.target.value;
            const token = event.target.dataset.token;
            const uid = event.target.dataset.uid;
            // if the button already exists do not create it
            const submitHandicapBtn = document.createElement('button');
            submitHandicapBtn.setAttribute('type', 'button');
            submitHandicapBtn.classList.add('upload-handicap-btn');
            submitHandicapBtn.setAttribute('data-handicap', handicap);
            submitHandicapBtn.setAttribute('data-token', token);
            submitHandicapBtn.setAttribute('data-uid', uid);
            submitHandicapBtn.innerText = '^';
            event.target.parentNode.appendChild(submitHandicapBtn);
        }
    });
});
*/

