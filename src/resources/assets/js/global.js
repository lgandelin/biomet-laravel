$(document).ready(function() {
    $('.btn-remove').on('click', function() {
        if (!confirm('Etes-vous sûrs de vouloir cet élément ?'))
            return false;
    });
});