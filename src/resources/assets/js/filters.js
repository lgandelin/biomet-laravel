$(document).ready(function() {

    $('.last_24h').on('click', function() {
        last_24h($(this).closest('.box'))
    });

    $('.last_week').on('click', function() {
        last_week($(this).closest('.box'))
    });

    $('.last_month').on('click', function() {
        last_month($(this).closest('.box'))
    });

    $('.current_year').on('click', function() {
        current_year($(this).closest('.box'))
    });
});

function last_24h(box) {
    var current_date = new Date();
    current_date.setHours(0, 0, 0, 0);
    current_date.setDate(current_date.getDate() - 1);

    box.find('input[name="start_date"]').val(format_date(current_date));
    box.find('input[name="end_date"]').val(format_date(current_date));
    box.find('.valid').trigger('click');

    box.find('.date_filter_label').removeClass('current');
    box.find('.last_24h').addClass('current');
}

function last_week(box) {
    var current_date = new Date();
    current_date.setHours(0, 0, 0, 0);
    current_date.setDate(current_date.getDate() - 1);

    box.find('input[name="end_date"]').val(format_date(current_date));

    current_date.setDate(current_date.getDate() - 6);
    box.find('input[name="start_date"]').val(format_date(current_date));
    box.find('.valid').trigger('click');

    box.find('.date_filter_label').removeClass('current');
    box.find('.last_week').addClass('current');
}

function last_month(box) {
    var current_date = new Date();
    current_date.setHours(0, 0, 0, 0);
    current_date.setDate(1);

    current_date.setDate(current_date.getDate() - 1);
    box.find('input[name="end_date"]').val(format_date(current_date));

    current_date.setMonth(current_date.getMonth() - 1);
    box.find('input[name="start_date"]').val(format_date(current_date));
    box.find('.valid').trigger('click');

    box.find('.date_filter_label').removeClass('current');
    box.find('.last_month').addClass('current');
}

function current_year(box) {
    var current_date = new Date();
    current_date.setHours(0, 0, 0, 0);
    current_date.setDate(1);
    current_date.setMonth(0);

    box.find('input[name="start_date"]').val(format_date(current_date));
    box.find('input[name="end_date"]').val(format_date(new Date()));
    box.find('.valid').trigger('click');

    box.find('.date_filter_label').removeClass('current');
    box.find('.current_year').addClass('current');
}

function format_date(date) {
    return ("0" + date.getDate()).slice(-2) + "/" + ("0" + (date.getMonth() + 1)).slice(-2) + "/" + date.getFullYear();
}