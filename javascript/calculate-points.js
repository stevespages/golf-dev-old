/**
 * Calculates points from scores and par and stroke index for hole
 * @param {integer} score - The gross score for the hole
 * @param {integer} holeNumber - The number of the hole (1 - 18)
 * @param {integer} holePar - The par for the hole GLOBAL
 * @param {integer} holeSi - The stroke index for the hole GLOBAL
 * @param {integer} handicap - The player's handicap
 * @returns {integer} - The number of points scored
 */
function calculatePoints(score, hole, handicap)
{
    console.log('hallo from calculatePoints!');
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
    const holePar = parseInt(course['h' + hole + 'par'], 10);
    const holeSi = parseInt(course['h' + hole + 'si'], 10);
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

    // why 2???
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

