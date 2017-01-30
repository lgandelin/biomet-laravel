@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <div class="facility-template container-fluid">
        <div class="dashboard">

            @if (isset($error))
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endif

            @if (isset($confirmation))
                <div class="alert alert-success">
                    {{ $confirmation }}
                </div>
            @endif


            <div class="col-lg-12 col-md-12 col-sm-12" style="padding-left: 0; padding-right: 0; margin-bottom: 30px;">
                <div class="box">
                    <h1 class="box-title">Dernières alarmes</h1>
                    <div class="box-content">
                        <table class="table table-stripped">
                            <thead>
                            <tr>
                                <th>{{ trans('biomet::alarms.event_date') }}</th>
                                <th>{{ trans('biomet::alarms.title') }}</th>
                                <th>{{ trans('biomet::alarms.description') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if (sizeof($alarms) > 0)
                                @foreach ($alarms as $alarm)
                                    <tr>
                                        <td>{{ $alarm->event_date }}</td>
                                        <td>{{ $alarm->title }}</td>
                                        <td>{{ $alarm->description }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="3">Aucune alarme enregistrée pour le moment.</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12" style="padding-left: 0; margin-bottom: 30px;">
                <div class="box">
                    <h1 class="box-title">Indicateur IGP</h1>
                    <div class="box-content">
                        @include('biomet::pages.facility.includes.date_filters')
                        <div class="graph" id="container1" data-title="Indicateur Global de Performance (IGP)" data-keys="IGP,IGP_AVG"></div>
                        <div class="entrypoint"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12" style="padding-right: 0; margin-bottom: 30px;">
                <div class="box">
                    <h1 class="box-title">H2S</h1>
                    <div class="box-content">
                        @include('biomet::pages.facility.includes.date_filters')
                        <div class="graph" id="container2" data-title="Dernières valeurs H2S" data-keys="AP0201_H2S,AP0202_H2S,AP0203_H2S" data-legend="0"></div>
                        <div class="entrypoint"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12" style="padding-left: 0; margin-bottom: 30px;">
                <div class="box">
                    <h1 class="box-title">CH4 et CO2 du biogaz brut et biométhane</h1>
                    <div class="box-content">
                        @include('biomet::pages.facility.includes.date_filters')
                        <div class="graph" id="container3" data-title="Dernière semaine : CH4 et CO2 du biogaz brut et biométhane" data-keys="AP0201_CH4,AP0201_CO2,AP0101_CH4,AP0101_CO2" data-legend="0"></div>
                        <div class="entrypoint"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12" style="padding-right: 0; margin-bottom: 30px;">
                <div class="box">
                    <h1 class="box-title">Volumes biogaz brut et biométhane</h1>
                    <div class="box-content">
                        @include('biomet::pages.facility.includes.date_filters')
                        <div class="graph" id="container4" data-title="Volumes" data-keys="FT0101F,FT0102F" data-legend="0"></div>
                        <div class="entrypoint"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>
        var get_graph_route = "{{ route('facility_get_graph') }}";
    </script>
    <script src="{{ asset('js/filters.js') }}"></script>
    <script src="{{ asset('js/graphs.js') }}"></script>

@endsection