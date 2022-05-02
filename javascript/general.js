const deleteDivs = document.querySelectorAll('.delete-div');
const cancelBtns = document.querySelectorAll('.cancel-btn');
const confirmDivs = document.querySelectorAll('.confirm-div');

deleteDivs.forEach(function(el){
    el.addEventListener('click', function(event){
        if(event.target.classList.contains('delete-btn')){
            cancelBtns.forEach(function(el){
                el.setAttribute('style', 'display:none');
            });
            confirmDivs.forEach(function(el){
                el.setAttribute('style', 'display:none');
            });
            event.target.setAttribute('style', 'display:none');
            const cancelBtn = event.target.parentNode.querySelectorAll('.cancel-btn');
            const confirmDiv = event.target.parentNode.querySelectorAll('.confirm-div');
            cancelBtn[0].setAttribute('style', 'display:inline');
            confirmDiv[0].setAttribute('style', 'display:block');
        }
        if(event.target.classList.contains('cancel-btn')){
            cancelBtns.forEach(function(el){
                el.setAttribute('style', 'display:none');
            });
            confirmDivs.forEach(function(el){
                el.setAttribute('style', 'display:none');
            });
            const deleteBtn = event.target.parentNode.querySelectorAll('.delete-btn');
            deleteBtn[0].setAttribute('style', 'display:inline');
        }
    });
});

