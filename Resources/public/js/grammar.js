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
    var newForm;
    if (collectionHolder.children().length === 0) {
        newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
    }
    else {
        // s'il y a déjà des champs ajoutés, on récupère le "__name__" du dernier
        // et l'incrémente de 1 pour créer le suivant
        var lastFormId = collectionHolder.children().last().children('textarea').attr('id').slice(-7,-5);
        if (lastFormId.substring(1,2) === "_") {
            lastFormId = lastFormId.substring(0,1);
        }
        newForm = prototype.replace(/__name__/g, parseInt(lastFormId)+1);
    }
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