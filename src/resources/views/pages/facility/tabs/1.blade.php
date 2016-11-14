@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }}</h1>

    <div class="facility-template">

        @include('biomet::pages.facility.menu')

        <script src="https://code.highcharts.com/stock/highstock.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <div id="container" style="min-width: 310px; height: 400px; margin: 50px auto"></div>

        <a href="javascript:graph()">Graph</a>

        <div class="graphs"></div>

        <a style="margin-top: 50px" class="btn btn-default" href="{{ route('dashboard') }}">{{ trans('biomet::generic.back') }}</a>

        {{ csrf_field() }}
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
    </script>

@endsection