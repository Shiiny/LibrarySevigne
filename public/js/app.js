$(document).ready(function() {
    let list = document.querySelector('#book_category');
    //$(list).append( '<option id="newCategory">Nouvelle catégorie</option>' );

    let newCategory = $('#newCategory');

    // Si l'utilisateur choisit nouvelle catégorie
    $(newCategory).click(function(){
        // Modale avec input pour saisir le titre de la nouvelle catégorie
        $('#addCategory').modal('show');

        $('#addNewCategory').click(function(e) {
            e.preventDefault()
            let newCategoryTitle = $('#category_title').val();
            console.log(newCategoryTitle);
            newCategoryTitle = newCategoryTitle.charAt(0).toUpperCase() + newCategoryTitle.substring(1);
            console.log(newCategoryTitle)

            // On ajoute celle-ci à la liste déroulante et on ferme la modale
            $(list).prepend( '<option selected>'+ newCategoryTitle +'</option>' );
            $('#addCategory').modal('hide');
        })
    });
});

// Bouton pour ajouter un prof
$(document).ready(function () {
    var listProf = $('#appbundle_book_author');
    var newProf = $('#newProf');

    // Si l'utilisateur clique sur le bouton
    $(newProf).click(function() {
        // Modale avec input pour saisir le nom et prenom du prof
        $('#addProf').modal('show');

        $('#addNewProf').click(function(){
            // Concatène le nom et prenom puis l'
            var AuthorFirstName = $('#appbundle_addprof_firstName').val();
            var newAuthorFirstName = AuthorFirstName.charAt(0).toUpperCase() + AuthorFirstName.substring(1);
            var AuthorLastName = $('#appbundle_addprof_lastName').val();
            var newAuthorLastName = AuthorLastName.charAt(0).toUpperCase() + AuthorLastName.substring(1)
            newAuthor =  newAuthorFirstName + ' ' + newAuthorLastName;

            // Ajoute à la liste déroulante. Ferme la modale
            $(listProf).prepend( '<option selected>' + newAuthor +'</option>' );
            $('#addProf').modal('hide');
        });
    });
});

// Récupération de la valeur du select pour les roles
$(document).ready(function() {
    $('select').change(function () {
        var input = '#input_' + this.id;
        var optionSelected = 'select#' + this.id + ' option:selected';
        var newRole = $(optionSelected).val();
        $(input).val(newRole);
    });
});

// Masque les messages flash après 3s
$(document).ready(function(){
    setTimeout(function() {
        $('#flash').fadeOut('slow');
    }, 3000); // <-- time in milliseconds
});

// Applique une rotation au chevron de la sidebar
$(document).ready(function() {
    let chevrons = document.querySelectorAll('.cs__sidebar__accordion__title')

    for( let i = 0; i < chevrons.length; i++) {
        chevrons[i].addEventListener('click', function () {
            let child = [].slice.call(chevrons[i].children)
            if (child[0].style.transform === 'rotate(180deg)') {
                child[0].style.transform = 'rotate(0deg)'
            }
            else {
                child[0].style.transform = 'rotate(180deg)'
            }
        })
    }
});

$(document).ready(function () {
   /* function getUrl (elt) {
        let url = elt.attr('data-target');
        $.get(url, function (data) {
            $('.content-body').html(data);
        })
    }*/

   $('.modal-trigger').click(function (e) {
       e.preventDefault();
       this.blur();
       $.get(this.href, function (data) {
           $(data).appendTo($('.content-body'));
       })
       $('#modalConnexion').modal('show');
       /*$('.modal').modal();
       getUrl($(this));
       $('#register-form-link').removeClass('active');
       $('#login-form-link').addClass('active');
       $('#modalConnexion').modal('show');*/

   });
   /* $('#register-form-link').click(function () {
        getUrl($(this));
        $('#login-form-link').removeClass('active');
        $(this).addClass('active');

    });
    $('#login-form-link').click(function () {
        getUrl($(this));
        $('#register-form-link').removeClass('active');
        $(this).addClass('active');
    });*/
});

$(document).ready(function () {
    $('#login-submit').click(function (e) {
        e.preventDefault();
        $.ajax({
            type:"POST",
            data: $('form').serialize(),
            url:"{{ url('login_ajax') }}",
            cache: false,
            success: function (data) {
                $('#modalConnexion').modal('show');
                $('.content-body').empty().append(data);
            },
            error: function(jqXHR, textStatus, errorThrow) {
                console.log(textStatus, errorThrow)
            }
        })
    })
})

$(document).ready(function () {
    let item = document.querySelectorAll('.cs__item')
    let catePlus = document.querySelector('.category_plus')
    let cateMoins = document.querySelector('.category_moins')

    catePlus.addEventListener('click', function () {
        for (let i = 0; i < item.length; i++) {
            if (item[i].classList.contains('cs__item__hidden')) {
                item[i].classList.replace('cs__item__hidden', 'cs__item__temp')
            }
        }
        catePlus.style.display = 'none'
        cateMoins.style.display= 'inline-block'

    })
    cateMoins.addEventListener('click', function () {
        for (let i = 0; i < item.length; i++) {
            if (item[i].classList.contains('cs__item__temp')) {
                item[i].classList.replace('cs__item__temp', 'cs__item__hidden')
            }
        }
        cateMoins.style.display = 'none'
        catePlus.style.display = 'inline-block'
    })

    let authorPlus = document.querySelector('.author_plus')
    let authorMoins = document.querySelector('.author_moins')

    authorPlus.addEventListener('click', function () {
        for (let i = 0; i < item.length; i++) {
            if (item[i].classList.contains('cs__item__hidden')) {
                item[i].classList.replace('cs__item__hidden', 'cs__item__temp')
            }
        }
        authorPlus.style.display = 'none'
        authorMoins.style.display= 'inline-block'

    })
    authorMoins.addEventListener('click', function () {
        for (let i = 0; i < item.length; i++) {
            if (item[i].classList.contains('cs__item__temp')) {
                item[i].classList.replace('cs__item__temp', 'cs__item__hidden')
            }
        }
        authorMoins.style.display = 'none'
        authorPlus.style.display = 'inline-block'
    })

})