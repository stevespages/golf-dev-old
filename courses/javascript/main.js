const coursesUl = document.querySelector('#courses-ul');

coursesUl.addEventListener('click', function(event){
    console.log(event);
    if(event.target.classList.contains('course-name-lis')){
        if(event.target.querySelector('#menu-span')){
            const menuSpan = document.querySelector('#menu-span');
            menuSpan.remove();
            return;
        }
        if(document.querySelector('#menu-span')){
            const menuSpanForRemoval = document.querySelector('#menu-span');
            menuSpanForRemoval.remove();
        }
        const menuSpan = document.createElement('span');
        menuSpan.setAttribute('id', 'menu-span');
        const deleteA = document.createElement('a');
        const href = './?delete=' + event.target.dataset.coursesId;
        deleteA.setAttribute('href', href);
        deleteA.innerText = 'Delete';
        menuSpan.append(" ", deleteA, " | edit | view | share");
        event.target.append(menuSpan);
    }
});

