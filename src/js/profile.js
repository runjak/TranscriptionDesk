$(document).ready(function () {
    $("#polarChart").shieldChart({
        theme: "bootstrap",
        primaryHeader: {
            text: "Your stats"
        },
        exportOptions: {
            image: false,
            print: false
        },
        chartLegend: {
            align: "center",
            verticalAlign: "top"
        },
        axisX: {
            categoricalValues: ["Activity", "Points", "Quality", "Quantity", "Words transcribed", "Languages transcribed"]
        },
        dataSeries: [{
            seriesType: 'polararea',
            collectionAlias: "Stats",
            data: [8.5,10,4,12,8,3]
        }]
    });

    $("#splineChart").shieldChart({
        theme: "bootstrap",
        primaryHeader: {
            text: "Your months activity"
        },
        axisX: {
            fixedEnd: false,
            axisType: "datetime"
        },
        axisY: [{
            endOffset: 0
        },{
            title: {
                style: {
                    color: "#ff7400"
                }
            }
        }],
        dataSeries: [{
            seriesType: "splinearea",
            collectionAlias: "Activity",
            data: [2,5,2,7,10,1,0,1,2,8,12,24,1,3,17,0,0,0,5,15,0,0,0,0,46,10,10,1,4,1,6],
            dataStart: Date.UTC(2015, 6, 1),
            dataStep: 24 * 3600 * 1000 // one day
        }]
    });
});