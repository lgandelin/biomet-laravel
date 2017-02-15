$(document).ready(function() {

    //Load graph on startup
    $('.valid').trigger('click');
});

//Load graphs
$('.valid').on('click', function() {
    var box = $(this).closest('.box');

    //Delete old graphs on page
    box.find('.entrypoint').empty();
    box.find('.highcharts-container').remove();

    //Load new graphs
    box.find('.graph').each(function() {
        load_graph($(this).attr('id'), $(this).data('title'), $(this).data('keys').split(','), $(this).data('legend'), box);
    });
});

//Download excel
$('.download-excel').on('click', function() {
    $('#download-excel input[name="keys"]').val($(this).prev().data('keys'));
    $('#download-excel input[name="start_date"]').val($('.date-filters input[name="start_date"]').val());
    $('#download-excel input[name="end_date"]').val($('.date-filters input[name="end_date"]').val());
    $('#download-excel').submit();
});

function load_graph(container_id, title, keys, legend, box) {
    $.ajax({
        type: "POST",
        url: get_graph_route,
        data: {
            container_id: container_id,
            title: title,
            facility_id: $('#facility_id').val(),
            start_date: box.find('input[name="start_date"]').val(),
            end_date: box.find('input[name="end_date"]').val(),
            keys: keys,
            legend: legend,
            _token: $('input[name="_token"]').val()
        },
        success: function(data) {
            if (data)
                $('#' + container_id).next().append(data);
            else
                $('#' + container_id).append('<div class="no-data">Aucune donnée trouvée pour cette période</div>');
        }
    });
}
