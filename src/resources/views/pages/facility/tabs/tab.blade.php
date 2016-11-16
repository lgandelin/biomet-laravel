@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }} - @yield('tab_name')</h1>

    @include('biomet::pages.facility.includes.menu')

    <div class="facility-template">
        @include('biomet::pages.facility.includes.date_filters')

        @yield('graphs')
        <div class="entrypoint"></div>

        <a class="btn btn-default" href="{{ route('dashboard') }}">{{ trans('biomet::generic.back') }}</a>
    </div>

    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>
        var get_graph_route = "{{ route('facility_get_graph') }}";
    </script>
    <script src="{{ asset('js/graphs.js') }}"></script>

@endsection