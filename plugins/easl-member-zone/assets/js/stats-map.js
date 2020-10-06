(function ($) {
    $(document).ready(function () {
        $('body').on('mz-stats-loaded', '.mz-statistics-inner', function (event, mapData) {
            var $el = $("#easl-mz-stat-map", $(this));
            var chartData = [];
            chartData.push(['Country', 'Members']);
            for (cc in mapData.map_data) {
                chartData.push([mapData.map_data[cc].country_label, parseInt(mapData.map_data[cc].member_count, 10)]);
            }
            google.charts.load('current', {
                'packages': ['geochart'],
                'mapsApiKey': EASLMZMAP.apiKey
            });
            google.charts.setOnLoadCallback(drawMarkersMap);

            function drawMarkersMap() {
                var data = google.visualization.arrayToDataTable(chartData, false);

                var options = {
                    region: 'world',
                    displayMode: 'markers',
                    colorAxis: {
                        colors: ['#62cff5', '#62cff5'],
                    },
                    defaultColor: '#62cff5',
                    sizeAxis: {minSize: 6,  maxSize: 6},
                    legend: 'none',
                    tooltip: {
                        showColorCode: false
                    },
                    width: $el.width()
                };

                var chart = new google.visualization.GeoChart($el[0]);
                chart.draw(data, options);
            }
        });
    });
})(jQuery);