@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">{{ $current_facility->name }} - @yield('tab_name')</h1>
        <div class="box-content facility-template">
            @include('biomet::pages.facility.includes.date_filters')

            @yield('graphs')
            <div class="entrypoint"></div>
        </div>
        <form action="{{ route('facility_get_excel') }}" id="download-excel" method="post">
            <input type="hidden" name="keys" value="" />
            <input type="hidden" name="facility_id" value="{{ $current_facility->id }}" />
            <input type="hidden" name="start_date" value="" />
            <input type="hidden" name="end_date" value="" />
            {{ csrf_field() }}
        </form>
    </div>

    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>
        var get_graph_route = "{{ route('facility_get_graph') }}";
    </script>
    <script src="{{ asset('js/filters.js') }}"></script>
    <script src="{{ asset('js/graphs.js') }}"></script>

@endsection