<script type="text/javascript">

    var series = {!! $series !!};

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
            renderTo: "{{ $container_id }}",
            type: 'line',
            zoomType: 'x'
        },
        title: {
            text: "{!! $title !!}",
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
            ordinal: false,
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
        series: series
    });
</script>