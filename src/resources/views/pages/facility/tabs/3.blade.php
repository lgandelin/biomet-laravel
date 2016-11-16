@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }} - Composition</h1>

    @include('biomet::pages.facility.menu')

    <div class="facility-template">

        <script src="https://code.highcharts.com/stock/highstock.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <div id="container" style="min-width: 310px; height: 400px; margin: 50px auto"></div>

        <div class="graphs"></div>

        <p style="text-align: center;">
            <strong>Date de début :</strong> <input id="start_date" type="date" class="form-control" value="{{ date('Y-m-d', strtotime( '-1 days' )) }}" style="display: inline; width:175px; margin-bottom: 1rem; margin-right: 2.5rem;"/>
            <strong>Date de fin :</strong> <input id="end_date" type="date" class="form-control" value="{{ date('Y-m-d', strtotime( '-1 days' )) }}" style="display: inline; width:175px; margin-bottom: 1rem;"/>
            <a class="btn btn-success" href="javascript:graph()">{{ trans('biomet::generic.valid') }}</a>

            <ul style="text-align: center;">
                <li><a href="javascript:last_24h()">Dernières 24h</a></li>
                <li><a href="javascript:last_week()">Dernière semaine</a></li>
                <li><a href="javascript:last_month()">Dernier mois</a></li>
                <li><a href="javascript:current_year()">Année en cours</a></li>
            </ul>
        </p>

        {{ csrf_field() }}
        <input type="hidden" id="facility_id" value="{{ $current_facility->id }}" />
        <input type="hidden" id="current_date" value="{{ date('Y-m-d', strtotime( '-1 days' )) }}" />

        <a class="btn btn-default" href="{{ route('dashboard') }}">{{ trans('biomet::generic.back') }}</a>
    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            graph();
        });

        function graph() {
            $.each(Highcharts.charts, function(i, chart) {
                if (typeof chart != 'undefined') {
                    chart.destroy();
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ route('facility_get_graph') }}",
                data: {
                    facility_id: $('#facility_id').val(),
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                    keys: ['AP0201_CH4', 'AP0201_CO2', 'AP0201_H2O', 'AP0201_H2S', 'AP0201_O2'],
                    _token: $('input[name="_token"]').val()
                },
                success: function(data) {

                    if (data)
                        $('.graphs').html(data);
                    else
                        $('.graphs').append('<div class="no-data">Aucune donnée trouvée pour cette période</div>');
                }
            });
        }
        
        function last_24h() {
            var current_date = new Date();
            current_date.setHours(0, 0, 0, 0);
            current_date.setDate(current_date.getDate() - 1);

            $('#start_date').val(format_date(current_date));
            $('#end_date').val(format_date(current_date));
            graph();
        }

        function last_week() {
            var current_date = new Date();
            current_date.setHours(0, 0, 0, 0);
            current_date = getMonday(current_date);

            var end_date = current_date;
            end_date.setDate(end_date.getDate() - 1);
            $('#end_date').val(format_date(end_date));

            current_date = getMonday(current_date.setDate(current_date.getDate() - 1));
            $('#start_date').val(format_date(current_date));
            graph();
        }

        function last_month() {
            var current_date = new Date();
            current_date.setHours(0, 0, 0, 0);
            current_date.setDate(1);

            current_date.setDate(current_date.getDate() - 1);
            $('#end_date').val(format_date(current_date));

            current_date.setMonth(current_date.getMonth() - 1);
            $('#start_date').val(format_date(current_date));
            graph();
        }

        function current_year() {
            var current_date = new Date();
            current_date.setHours(0, 0, 0, 0);
            current_date.setDate(1);
            current_date.setMonth(0);

            $('#start_date').val(format_date(current_date));

            $('#end_date').val(format_date(new Date()));
            graph();
        }

        function format_date(date) {
            return date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
        }

        function getMonday(d) {
            d = new Date(d);
            var day = d.getDay(),
                diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
            return new Date(d.setDate(diff));
        }
    </script>

@endsection