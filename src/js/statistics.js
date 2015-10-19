require(['js/Chart.min.js'], function(Chart) {
    Chart.defaults.global.animationEasing = "easeOutBounce";
    Chart.defaults.global.responsive = true;
    Chart.defaults.global.animationSteps = 120;
    var ctx = document.getElementById("lineChart").getContext("2d");
    var data = {
        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31],
        datasets: [
            {
                label: "This months overall activity",
                fillColor: "rgba(255,140,0,0.3)",
                strokeColor: "rgba(255,140,0,1)",
                pointColor: "rgba(255,165,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [20, 51, 22, 87, 160, 150, 120, 151, 82, 98, 120, 124, 188, 153, 107, 50, 90, 80, 125, 105, 140, 120, 110, 100, 46, 150, 100, 113, 114, 111, 156]
            }
        ]
    };
    var lineChart = new Chart(ctx).Line(data, {
        responsive: true,
        pointHitDetectionRadius: 4
    });

    var bar = document.getElementById("barChart").getContext("2d");
    var barData = {
        labels: ["Latin", "Greek", "Farsi", "German", "English", "Unknown"],
        datasets: [
            {
                label: "Transcribed languages overall",
                fillColor: "rgba(0,140,255,0.5)",
                strokeColor: "rgba(0,140,255,0.5)",
                highlightFill: "rgba(0,140,255,1)",
                highlightStroke: "rgba(0,140,255,1)",
                data: [200, 301, 124, 80, 15, 50]
            },
            {
                label: "Transcribed languages this month",
                fillColor: "rgba(255,140,0,0.5)",
                strokeColor: "rgba(255,140,0,0.5)",
                highlightFill: "rgba(255,140,0,1)",
                highlightStroke: "rgba(255,140,0,1)",
                data: [20, 31, 24, 8, 1, 5]
            }
        ]
    };
    var barChart = new Chart(bar).Bar(barData, {
        responsive: true
    });
    document.getElementById('barLegend').innerHTML = barChart.generateLegend();

    var language = document.getElementById("languageChart").getContext("2d");
    var languageData = {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [
            {
                label: "Latin",
                fillColor: "rgba(0,140,255,0.01)",
                strokeColor: "rgba(0,140,255,1)",
                pointColor: "rgba(0,165,255,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [11, 36, 14, 42, 34, 27, 49, 19, 44, 39, 6, 23]
            }, {
                label: "Greek",
                fillColor: "rgba(255,140,0,0.01)",
                strokeColor: "rgba(255,140,0,1)",
                pointColor: "rgba(255,165,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [25, 2, 8, 27, 45, 21, 11, 26, 13, 28, 38, 41]
            }, {
                label: "Farsi",
                fillColor: "rgba(0,128,0,0.01)",
                strokeColor: "rgba(0,128,0,1)",
                pointColor: "rgba(0,128,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [20, 33, 34, 50, 16, 10, 40, 21, 48, 37, 14, 47]
            }, {
                label: "German",
                fillColor: "rgba(128,0,0,0.01)",
                strokeColor: "rgba(128,0,0,1)",
                pointColor: "rgba(128,0,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [48, 40, 12, 1, 37, 44, 15, 28, 2, 5, 26, 4]
            }, {
                label: "English",
                fillColor: "rgba(128,128,128,0.01)",
                strokeColor: "rgba(128,128,128,1)",
                pointColor: "rgba(128,128,128,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [37, 7, 10, 40, 0, 3, 20, 28, 24, 27, 39, 48]
            }, {
                label: "Unknown",
                fillColor: "rgba(128,0,128,0.01)",
                strokeColor: "rgba(128,0,128,1)",
                pointColor: "rgba(128,0,128,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [46, 43, 25, 7, 31, 11, 30, 28, 29, 14, 47, 2]
            }
        ]
    };
    var languageOptions = {
        responsive: true,
        pointHitDetectionRadius: 4,
        tooltipTemplate: "<%= value %>%"
    };
    var languageChart = new Chart(language).Line(languageData, languageOptions);
    document.getElementById('languageLegend').innerHTML = languageChart.generateLegend();

    $.getScript('//cdn.datatables.net/1.10.1/js/jquery.dataTables.min.js', function () {
        $.getScript('//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js', function () {
            $('#overallTable').dataTable();
            $('#monthTable').dataTable();
        });
    });
});