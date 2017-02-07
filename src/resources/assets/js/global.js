$(document).ready(function() {
    $('.btn-remove').on('click', function() {
        if (!confirm('Etes-vous sûrs de vouloir supprimer cet élément ?'))
            return false;
    });

    $('.menu-icon').on('click', function() {
        $('.left-column').slideToggle(200);
    })
});