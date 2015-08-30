//head.js will already be required.
require(['Chart.min'], function(Chart){
    Chart.defaults.global.animationEasing = "easeOutBounce";
    Chart.defaults.global.responsive = true;
    Chart.defaults.global.animationSteps = 120;
    var ctx = document.getElementById("lineChart").getContext("2d");
    var data = {
        labels: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31],
        datasets: [
            {
                label: "Your months activity",
                fillColor: "rgba(255,140,0,0.3)",
                strokeColor: "rgba(255,140,0,1)",
                pointColor: "rgba(255,165,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [2,5,2,7,10,1,0,1,2,8,12,24,1,3,17,0,0,0,5,15,0,0,0,0,46,10,10,1,4,1,6]
            }
        ]
    };
    var lineChart = new Chart(ctx).Line(data, {
        responsive: true,
        pointHitDetectionRadius : 4
    });

    var ctxp = document.getElementById("polarChart").getContext("2d");

    var datap = {
        labels: ["Activity", "Points", "Quality", "Quantity", "Words transcribed", "Languages transcribed"],
        datasets: [
            {
                label: "Your stats",
                fillColor: "rgba(255,140,0,0.3)",
                strokeColor: "rgba(255,140,0,1)",
                pointColor: "rgba(255,165,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [8.5,10,4,12,8,3]
            }
        ]
    };
    var polarChart = new Chart(ctxp).Radar(datap, {
        responsive: true,
        pointHitDetectionRadius: 8
    });
});
