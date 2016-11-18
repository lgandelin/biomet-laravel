$(document).ready(function() {
    $('#valid').trigger('click');
});

$('#valid').on('click', function() {

    //Delete old graphs on page
    $('.entrypoint').empty();
    $.each(Highcharts.charts, function(i, chart) {
        if (typeof chart != 'undefined') {
            chart.destroy();
        }
    });

    //Load new graphs
    $('.graph').each(function() {
        load_graph($(this).attr('id'), $(this).data('title'), $(this).data('keys').split(','));
    });
});

function load_graph(container_id, title, keys) {
    $.ajax({
        type: "POST",
        url: get_graph_route,
        data: {
            container_id: container_id,
            title: title,
            facility_id: $('#facility_id').val(),
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            keys: keys,
            _token: $('input[name="_token"]').val()
        },
        success: function(data) {
            if (data)
                $('.entrypoint').append(data);
            else
                $('#' + container_id).append('<div class="no-data">Aucune donnée trouvée pour cette période</div>');
        }
    });
}