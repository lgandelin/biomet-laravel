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
                        <h1 class="box-title">Indicateur IGP moyen (-)</h1>
                        <div class="box-content row indicators">
                            <div class="col-lg-6 col-md-3 col-sm-12"><span class="title">Dernières 24h</span> <span class="value">{{ $avg_igp_last_24h }}</span></div>
                            <div class="col-lg-6 col-md-3 col-sm-12"><span class="title">Derniers 7 jours</span> <span class="value">{{ $avg_igp_last_7_days }}</span></div>
                            <div class="col-lg-6 col-md-3 col-sm-12"><span class="title">Dernier mois</span> <span class="value">{{ $avg_igp_last_month }}</span></div>
                            <div class="col-lg-6 col-md-3 col-sm-12"><span class="title">Année en cours</span> <span class="value">{{ $avg_igp_current_year }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 30px;">
                    <div class="box">
                        <h1 class="box-title">Concentration H<sub>2</sub>S moyenne des dernières 24h (ppm)</h1>
                        <div class="box-content row indicators">
                            <div class="col-lg-4 col-md-4 col-sm-12"><span class="title">AP0201</span> <span class="value">{{ $avg_ap0201_last_24h }}</span></div>
                            <div class="col-lg-4 col-md-4 col-sm-12"><span class="title">AP0202</span> <span class="value">{{ $avg_ap0202_last_24h }}</span></div>
                            <div class="col-lg-4 col-md-4 col-sm-12"><span class="title">AP0203</span> <span class="value">{{ $avg_ap0203_last_24h }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 30px;">
                    <div class="box">
                        <h1 class="box-title">Valeurs clés depuis le début de l'année</h1>
                        <div class="box-content row indicators">
                            <div class="col-lg-3 col-md-4 col-sm-12"><span class="title">Consommation électrique depuis le début de l'année (kWh)</span><span class="value">{{ $sum_conso_elec_install_current_year }}</span></div>
                            <div class="col-lg-3 col-md-6 col-sm-12"><span class="title">Volume cumulé biogaz brut (Nm<sup>3</sup>) (FT0101F)</span> <span class="value">{{ number_format($sum_ft0101f_current_year, 0, '.', ' ') }}</span></div>
                            <div class="col-lg-3 col-md-6 col-sm-12"><span class="title">Volume cumulé biométhane (Nm<sup>3</sup>) (FT0102F)</span> <span class="value">{{ number_format($sum_ft0102f_current_year, 0, '.', ' ') }}</span></div>
                            <div class="col-lg-3 col-md-6 col-sm-12"><span class="title">Heures en fonctionnement (h)</span> <span class="value"><span class="value">{{ number_format($heures_en_fonctionnement_current_year, 0, '.', ' ') }}</span></div>
                        </div>
                        <div class="box-content row indicators">
                            <div class="col-lg-3 col-md-4 col-sm-12"><span class="title">Quantité biométhane injecté (Nm<sup>3</sup>)</span><span class="value">{{ number_format($qte_biomethane_injecte_current_year, 0, '.', ' ') }}</span></div>
                            <div class="col-lg-3 col-md-6 col-sm-12"><span class="title">PCS biométhane injecté (MWh PCS)</span> <span class="value">{{ number_format($pcs_biomethane_injecte_current_year, 0, '.', ' ') }}</span></div>
                            <div class="col-lg-3 col-md-6 col-sm-12"><span class="title">Quantité biométhane non-conforme (Nm<sup>3</sup>)</span> <span class="value">{{ number_format($qte_biomethane_non_conforme_current_year, 0, '.', ' ') }}</span></div>
                            <div class="col-lg-3 col-md-6 col-sm-12"><span class="title">PCS biométhane non-conforme  (MWh PCS)</span> <span class="value">{{ number_format($pcs_biomethane_non_conforme_current_year, 0, '.', ' ') }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 30px;">
                    <div class="box">
                        <h1 class="box-title">CH<sub>4</sub> et CO<sub>2</sub> du biogaz brut et biométhane (%)</h1>
                        <div class="box-content">
                            @include('biomet::pages.facility.includes.date_filters')
                            <div class="graph" id="container3" data-title="CH<sub>4</sub> et CO<sub>2</sub> du biogaz brut et biométhane" data-keys="AP0201_CH4,AP0201_CO2,AP0101_CH4,AP0101_CO2" data-legend="AP0201 CH<sub>4</sub> (%),AP0201 CO<sub>2</sub> (%),AP0101 CH<sub>4</sub> (%),AP0101 CO<sub>2</sub> (%)"></div>
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