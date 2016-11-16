<script type="text/javascript">

    var seriesOptions = {!! $series !!};

    window.chart = new Highcharts.StockChart({
        chart: {
            renderTo: "{{ $container_id }}",
            type: 'line',
            zoomType: 'x'
        },
        title: {
            text: "{{ $title }}",
             x: -20
        },
        subtitle: {
            text: '',
            x: -20
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
            borderWidth: 0
        },
        scrollbar: {
            enabled: false
        },
        series: seriesOptions
    });
</script>