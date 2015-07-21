$(document).ready(function () {
    var collectionHolder = $('ul.contents');
    collectionHolder.find('li').each(function () {
        addTagFormDeleteLink($(this));
    });

    $("#add-content").on('click', function (e) {
        e.preventDefault();
        addTagForm(collectionHolder);
    });
});

$(document).ready(function () {
    var collectionHolder = $('ul.instructions');
    collectionHolder.find('li').each(function () {
        addTagFormDeleteLink($(this));
    });

    $("#add-instruction").on('click', function (e) {
        e.preventDefault();
        addTagForm(collectionHolder);
    });
});

$(document).ready(function () {
    var collectionHolder = $('ul.complementaryInformations');
    collectionHolder.find('li').each(function () {
        addTagFormDeleteLink($(this));
    });

    $("#add-complementaryInformation").on('click', function (e) {
        e.preventDefault();
        addTagForm(collectionHolder);
    });
});

function addTagForm(collectionHolder) {
    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
    var prototype = collectionHolder.attr('data-prototype');
    console.log(prototype);
    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
    // la longueur de la collection courante
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
    var newFormLi = $('<li class="list-group-item col-md-12"></div>').append(newForm);
    collectionHolder.append(newFormLi);
    addTagFormDeleteLink(newFormLi);
}
function addTagFormDeleteLink(tagFormLi) {
    var $removeFormA = $('<a class="btn btn-danger" href="#" title="Supprimer"><i class="fa fa-close"></i></a>');
    tagFormLi.append($removeFormA);
    $removeFormA.on('click', function (e) {
        // empêche le lien de créer un « # » dans l'URL
        e.preventDefault();
        // supprime l'élément li pour le formulaire de tag
        tagFormLi.remove();
    });
}