// [ income-analysis ] start
(function () {
    const options = {
        chart: {
            type: 'area',
            height: 100,
            sparkline: {
                enabled: true
            },
            locales: [{
                "name": "es",
                "options": {
                    "months": [
                        "Enero",
                        "Febrero",
                        "Marzo",
                        "Abril",
                        "Mayo",
                        "Junio",
                        "Julio",
                        "Agosto",
                        "Septiembre",
                        "Octubre",
                        "Noviembre",
                        "Diciembre"
                    ]
                }
            }],
            defaultLocale: 'es'
        },
        labels: VIEWS_LAST_30_DAY.Dates,
        series: [{
            name: 'series1',
            data: VIEWS_LAST_30_DAY.Count
        }],
        xaxis: {
            type: 'datetime'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 3,
        },
        colors: ["#7267EF"],
        fill: {
            type: 'solid',
            opacity: 0.3,
        },
        markers: {
            size: 0,
            opacity: 0.9,
            colors: "#fff",
            strokeColor: "#7267EF",
            strokeWidth: 2,
            hover: {
                size: 7,
            }
        },
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: true,
                format: 'MMMM dd'
            },
            y: {
                title: {
                    formatter: function (seriesName) {
                        return 'Vistas : ';
                    }
                },
                formatter: function (value) {
                    return parseInt(value);
                }
            },
            marker: {
                show: false
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#income-analysis"), options);
    chart.render();
})();
// [ income-analysis ] end