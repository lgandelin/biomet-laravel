$(document).ready(function() {
    $('#valid_graphs').trigger('click');
});

$('#valid_graphs').on('click', function() {

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
    console.log('load' + container_id);

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

function last_24h() {
    var current_date = new Date();
    current_date.setHours(0, 0, 0, 0);
    current_date.setDate(current_date.getDate() - 1);

    $('#start_date').val(format_date(current_date));
    $('#end_date').val(format_date(current_date));
    $('#valid_graphs').trigger('click');
}

function last_week() {
    var current_date = new Date();
    current_date.setHours(0, 0, 0, 0);
    current_date = get_monday(current_date);

    var end_date = current_date;
    end_date.setDate(end_date.getDate() - 1);
    $('#end_date').val(format_date(end_date));

    current_date = get_monday(current_date.setDate(current_date.getDate() - 1));
    $('#start_date').val(format_date(current_date));
    $('#valid_graphs').trigger('click');
}

function last_month() {
    var current_date = new Date();
    current_date.setHours(0, 0, 0, 0);
    current_date.setDate(1);

    current_date.setDate(current_date.getDate() - 1);
    $('#end_date').val(format_date(current_date));

    current_date.setMonth(current_date.getMonth() - 1);
    $('#start_date').val(format_date(current_date));
    $('#valid_graphs').trigger('click');
}

function current_year() {
    var current_date = new Date();
    current_date.setHours(0, 0, 0, 0);
    current_date.setDate(1);
    current_date.setMonth(0);

    $('#start_date').val(format_date(current_date));
    $('#end_date').val(format_date(new Date()));
    $('#valid_graphs').trigger('click');
}

function format_date(date) {
    return date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
}

function get_monday(d) {
    d = new Date(d);
    var day = d.getDay(),
        diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
    return new Date(d.setDate(diff));
}