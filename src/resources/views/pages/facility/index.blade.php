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

            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12" style="margin-bottom: 30px;">
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

                <div class="col-lg-4 col-md-12 col-sm-12" style="margin-bottom: 30px;">
                    <div class="box">
                        <h1 class="box-title">Indicateur IGP moyen</h1>
                        <div class="box-content row indicators">
                            <div class="col-lg-6 col-md-3 col-sm-12"><span class="title">Dernières 24h</span> <span class="value">{{ $avg_igp_last_24h }}</span></div>
                            <div class="col-lg-6 col-md-3 col-sm-12"><span class="title">Dernière semaine</span> <span class="value">{{ $avg_igp_last_week }}</span></div>
                            <div class="col-lg-6 col-md-3 col-sm-12"><span class="title">Dernier mois</span> <span class="value">{{ $avg_igp_last_month }}</span></div>
                            <div class="col-lg-6 col-md-3 col-sm-12"><span class="title">Année en cours</span> <span class="value">{{ $avg_igp_current_year }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12" style="margin-bottom: 30px;">
                    <div class="box">
                        <h1 class="box-title">Concentration H2S moyenne des dernières 24h</h1>
                        <div class="box-content row indicators">
                            <div class="col-lg-4 col-md-4 col-sm-12"><span class="title">AP0201</span> <span class="value">{{ $avg_ap0201_last_24h }}</span></div>
                            <div class="col-lg-4 col-md-4 col-sm-12"><span class="title">AP0202</span> <span class="value">{{ $avg_ap0202_last_24h }}</span></div>
                            <div class="col-lg-4 col-md-4 col-sm-12"><span class="title">AP0203</span> <span class="value">{{ $avg_ap0203_last_24h }}</span></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12" style="margin-bottom: 30px;">
                    <div class="box">
                        <h1 class="box-title">Volumes cumulés depuis le début de l'année</h1>
                        <div class="box-content row indicators">
                            <div class="col-lg-6 col-md-6 col-sm-12"><span class="title">Biogaz brut (FT0101F)</span> <span class="value">{{ $sum_ft0101f_current_year }}</span></div>
                            <div class="col-lg-6 col-md-6 col-sm-12"><span class="title">Biométhane (FT0102F)</span> <span class="value">{{ $sum_ft0102f_current_year }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 30px;">
                    <div class="box">
                        <h1 class="box-title">CH4 et CO2 du biogaz brut et biométhane</h1>
                        <div class="box-content">
                            @include('biomet::pages.facility.includes.date_filters')
                            <div class="graph" id="container3" data-title="Dernière semaine : CH4 et CO2 du biogaz brut et biométhane" data-keys="AP0201_CH4,AP0201_CO2,AP0101_CH4,AP0101_CO2" data-legend="0"></div>
                            <div class="entrypoint"></div>
                        </div>
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