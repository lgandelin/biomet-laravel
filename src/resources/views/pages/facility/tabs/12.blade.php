@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">{{ $current_facility->name }} - Suivi mensuel</h1>
        <div class="box-content facility-template">

            <form action="" method="get">
                <label for="year"><strong>Année : </strong></label>
                <input style="display: inline; width: 10rem; vertical-align: middle; margin-right: 1rem" class="form-control" type="text" name="year" value="{{ $data['year'] }}" />
                <input type="submit" class="btn btn-valid valid" value="{{ trans('biomet::generic.valid') }}" />
            </form>

            <table class="table table-stripped" style="margin-top: 3rem">
                <thead>
                    <tr>
                        <th>Mois</th>
                        <th>Biogaz brut (Nm<sup>3</sup>)</th>
                        <th>Biométhane (Nm<sup>3</sup>)</th>
                        @if ($current_facility->id == 'a054b4ef-64d9-4c46-a6ab-99de9d4c3d11')
                            <th>Chaudière (Nm<sup>3</sup>)</th>
                            <th>Section amines (Nm<sup>3</sup>)</th>
                        @elseif ($current_facility->id == '6dc0272e-be4e-4d94-bccd-7f6f3b78289c')
                            <th>Biogaz membranes(Nm<sup>3</sup>)</th>
                        @endif
                        <th>Consommation électrique (kWh)</th>
                    </tr>
                </thead>

                @if ($data['months'])
                    @foreach ($data['months'] as $month)
                        <tr>
                            <td><strong>{{ ucfirst($month['name']) }}</strong></td>
                            <td>{{ number_format($month['biogaz'], 0, '.', ' ') }}</td>
                            <td>{{ number_format($month['biomethane'], 0, '.', ' ') }}</td>
                            @if ($current_facility->id == 'a054b4ef-64d9-4c46-a6ab-99de9d4c3d11')
                                <td>{{ number_format($month['chaudiere'], 0, '.', ' ') }}</td>
                                <td>{{ number_format($month['amines'], 0, '.', ' ') }}</td>
                            @elseif ($current_facility->id == '6dc0272e-be4e-4d94-bccd-7f6f3b78289c')
                                <td>{{ number_format($month['membranes'], 0, '.', ' ') }}</td>
                            @endif
                            <td>{{ number_format($month['consommation_electrique'], 0, '.', ' ') }}</td>
                        </tr>
                    @endforeach
                @else
                    Aucune données pour cette année
                @endif
            </table>
        </div>

        <div id="monthly-report"></div>
    </div>

    <script src="https://code.highcharts.com/stock/5.0.14/highstock.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>

        var series = {!! $data['series'] !!};

        Highcharts.setOptions({
            global: {
                useUTC: false
            },
            lang: {
                months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',  'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                shortMonths: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin',  'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
                weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                resetZoom: 'Réinitialiser zoom',
                printChart: 'Imprimer le graphe',
                downloadPDF: 'Télécharger en PDF',
                downloadJPEG: 'Télécharger en JPG',
                downloadPNG: 'Télécharger en PNG',
                downloadSVG: 'Télécharger en SVG',
            }
        });

        window.chart = new Highcharts.StockChart({
            chart: {
                renderTo: "monthly-report",
                type: 'line',
                zoomType: 'x'
            },
            title: {
                text: "Suivi mensuel",
                x: -20,
                useHTML:true
            },
            subtitle: {
                text: '',
                x: -20,
            },
            rangeSelector: {
                enabled: 0,
                selected: 1
            },
            tooltip: {
                valueDecimals: 3
            },
            xAxis: {
                type: 'datetime',
                minTickInterval: 28*24*3600*1000
            },
            navigator: {
                enabled: 0
            },
            legend: {
                enabled: true,
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0,
                useHTML:true
            },
            scrollbar: {
                enabled: false
            },
            series: series,
            plotOptions: {
                series: {
                    pointStart: 2010
                }
            }
        });
    </script>

@endsection