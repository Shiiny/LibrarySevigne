$('.cs__toggle__sidebar').on('click', function() {
    $('.cs__sidebar').toggle("slow");
})

// Applique une rotation au chevron de la sidebar
$(document).ready(function() {
    let chevrons = document.querySelectorAll('.cs__accordion-title')

    for( let i = 0; i < chevrons.length; i++) {
        chevrons[i].addEventListener('click', function () {
            let child = [].slice.call(chevrons[i].children)

            if (child[0].style.transform === '') {
                child[0].style.transform = 'rotate(180deg)'
            }
            else {
                child[0].removeAttribute("style")
            }
        })
    }
});

// Bouton "voir plus" dans la sidebar
$(document).ready(function () {
    function seeMore (selector) {
        let btnSee = selector.querySelector('.see-more')
        let btnMoins = selector.querySelector('.see-less')
        btnSee.addEventListener('click', function (e) {
            e.preventDefault()
            let ulElt = selector.querySelector('.cs__accordion-list')
            if (ulElt.hasChildNodes()) {
                let child = ulElt.children
                for (let i = 0; i < child.length; i++) {
                    if (child[i].classList.contains('__hidden')) {
                        child[i].classList.replace('__hidden', '__temp')
                    }
                }
            }
            btnSee.style.display = 'none'
            btnMoins.style.display = 'inline-block'
            btnMoins.addEventListener('click', function (e) {
                e.preventDefault()
                let child = ulElt.children
                for (let i = 0; i < child.length; i++) {
                    if (child[i].classList.contains('__temp')) {
                        child[i].classList.replace('__temp', '__hidden')
                    }
                }
                btnMoins.style.display = 'none'
                btnSee.style.display = 'inline-block'
            })
        })
    }

    seeMore(document.querySelector('#js_categorie'))
    seeMore(document.querySelector('#js_author'))
})