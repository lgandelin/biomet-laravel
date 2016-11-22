function last_24h() {
    var current_date = new Date();
    current_date.setHours(0, 0, 0, 0);
    current_date.setDate(current_date.getDate() - 1);

    $('#start_date').val(format_date(current_date));
    $('#end_date').val(format_date(current_date));
    $('#valid').trigger('click');
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
    $('#valid').trigger('click');
}

function last_month() {
    var current_date = new Date();
    current_date.setHours(0, 0, 0, 0);
    current_date.setDate(1);

    current_date.setDate(current_date.getDate() - 1);
    $('#end_date').val(format_date(current_date));

    current_date.setMonth(current_date.getMonth() - 1);
    $('#start_date').val(format_date(current_date));
    $('#valid').trigger('click');
}

function current_year() {
    var current_date = new Date();
    current_date.setHours(0, 0, 0, 0);
    current_date.setDate(1);
    current_date.setMonth(0);

    $('#start_date').val(format_date(current_date));
    $('#end_date').val(format_date(new Date()));
    $('#valid').trigger('click');
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