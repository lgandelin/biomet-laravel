$(document).ready(function() {
    $('.btn-danger').on('click', function() {
        if (!confirm('Etes-vous sûrs de vouloir cet élément ?'))
            return false;
    });
});