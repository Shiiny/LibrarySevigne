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
        if (selector === null) {
            return
        }
        let btnSee = selector.querySelector('.see-more')
        let btnMoins = selector.querySelector('.see-less')
        let ulElt = selector.querySelector('.cs__accordion-list')

        if (ulElt.childElementCount > 5) {
            let spanCount = selector.querySelector('.js-count')
            let child = ulElt.children
            console.log(child)

            spanCount.innerHTML = '(' + (ulElt.childElementCount - 5) + ')'
            for (let i = 5; i < child.length; i++) {
                child[i].classList.add('__hidden')
            }

            btnSee.addEventListener('click', function (e) {
                e.preventDefault()
                if (ulElt.hasChildNodes()) {
                    for (let i = 0; i < child.length; i++) {
                        child[i].classList.remove('__hidden')
                    }
                }
                btnSee.style.display = 'none'
                btnMoins.style.display = 'inline-block'
                btnMoins.addEventListener('click', function (e) {
                    e.preventDefault()
                    let child = ulElt.children
                    for (let i = 5; i < child.length; i++) {
                        child[i].classList.add('__hidden')
                    }
                    btnMoins.style.display = 'none'
                    btnSee.style.display = 'inline-block'
                })
            })
        } else {
            btnSee.style.opacity = '0'
        }
    }

    seeMore(document.querySelector('#js_categorie'))
    seeMore(document.querySelector('#js_author'))
})