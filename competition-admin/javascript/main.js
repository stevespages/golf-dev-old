const teamsUl = document.querySelector('#teams-ul');
const teamsMenuDiv = document.querySelectorAll('.teams-menu-div');
const playersMenuDiv = document.querySelectorAll('.players-menu-div');

teamsMenuDiv.forEach(menu => menu.setAttribute('style', 'display:none'));
playersMenuDiv.forEach(menu => menu.setAttribute('style', 'display:none'));

teamsUl.addEventListener('click', function(event){
    if(event.target.classList.contains('teams-name-li')){
        if(event.target.firstElementChild.getAttribute('style') === 'display:block'){
            event.target.firstElementChild.setAttribute('style', 'display:none');
            return;
        }
        teamsMenuDiv.forEach(menu => menu.setAttribute('style', 'display:none'));
        event.target.firstElementChild.setAttribute('style', 'display:block');
    }
    if(event.target.classList.contains('players-menu-li')){
        if(event.target.firstElementChild.getAttribute('style') === 'display:block'){
            event.target.firstElementChild.setAttribute('style', 'display:none');
            return;
        }
        playersMenuDiv.forEach(menu => menu.setAttribute('style', 'display:none'));
        event.target.firstElementChild.setAttribute('style', 'display:block');
    }
});
