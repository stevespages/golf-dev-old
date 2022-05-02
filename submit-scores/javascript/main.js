/**
 * Provides show / hide behaviour for forms for submitting scoresForms
 */

const formsUl = document.querySelector('#forms-ul');

const scoresForms = document.querySelectorAll('.scores-form');

scoresForms.forEach(el => el.setAttribute('style', 'display:none'));

formsUl.addEventListener('click', function(event){
    if(event.target.classList.contains('h2')){
        const form = event.target.nextSibling;
        if(form.getAttribute('style') === 'display:block'){
            form.setAttribute('style', 'display:none');
            return;
        }
        const allForms = document.querySelectorAll('#forms-ul form');
        allForms.forEach(el => el.setAttribute('style', 'display:none'));
        form.setAttribute('style', 'display:block');
    }
});

