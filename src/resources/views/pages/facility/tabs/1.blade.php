@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }} - Débit</h1>

    @include('biomet::pages.facility.menu')

    <div class="facility-template">

        <script src="https://code.highcharts.com/stock/highstock.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <div id="container" style="min-width: 310px; height: 400px; margin: 50px auto"></div>

        <div class="graphs"></div>

        <input id="date" type="date" class="form-control" value="{{ date('Y-m-d') }}" style="width:175px; margin-bottom: 1rem;"/>
        <a class="btn btn-success" href="javascript:graph()">{{ trans('biomet::generic.valid') }}</a>

        {{ csrf_field() }}
        <input type="hidden" id="facility_id" value="{{ $current_facility->id }}" />

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
                    date: $('#date').val(),
                    keys: ['FT0101F', 'FT0102F'],
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