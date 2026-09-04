$(document).ready(function(){
    
    /* Real data when the page provides it (window.salesChartData), random otherwise. */

    function toSeries(values) {
        var out = [];
        for (var i = 0; i < values.length; i += 1) {
            out.push([i, values[i]]);
        }
        return out;
    }

    function randomSeries(points) {
        var out = [];
        for (var i = 0; i <= points; i += 1) {
            out.push([i, parseInt(Math.random() * 30)]);
        }
        return out;
    }

    var d1, d3, label1 = 'Product 1', label3 = 'Product 2';

    if (window.salesChartData) {
        d1 = toSeries(window.salesChartData.revenue);
        d3 = toSeries(window.salesChartData.invoices);
        label1 = window.salesChartData.revenueLabel || 'Revenue';
        label3 = window.salesChartData.invoicesLabel || 'Invoices';
    } else {
        d1 = randomSeries(10);
        d3 = randomSeries(10);
    }
    
    /* Chart Options */
    
    var options = {
        series: {
            shadowSize: 0,
            curvedLines: { //This is a third party plugin to make curved lines
                apply: true,
                active: true,
                monotonicFit: true
            },
            lines: {
                show: false,
                lineWidth: 0,
            },
        },
        grid: {
            borderWidth: 0,
            labelMargin:10,
            hoverable: true,
            clickable: true,
            mouseActiveRadius:6,
            
        },
        xaxis: {
            tickDecimals: 0,
            ticks: false
        },
        
        yaxes: [
            { tickDecimals: 0, ticks: false },
            { tickDecimals: 0, ticks: false, position: 'right' }
        ],
        
        legend: {
            show: false
        }
    };
    
    /* Let's create the chart */
    
    if ($("#curved-line-chart")[0]) {
        $.plot($("#curved-line-chart"), [
            {data: d1, lines: { show: true, fill: 0.98 }, label: label1, stack: false, color: '#e3e3e3' },
            {data: d3, lines: { show: true, fill: 0.98 }, label: label3, stack: false, color: '#f1dd2c', yaxis: 2 }
        ], options);
    }
    
    /* Tooltips for Flot Charts */
    
    if ($(".flot-chart")[0]) {
        $(".flot-chart").bind("plothover", function (event, pos, item) {
            if (item) {
                var x = item.datapoint[0].toFixed(4),
                    y = item.datapoint[1].toFixed(4);
                $(".flot-tooltip").html(item.series.label + " of " + x + " = " + y).css({top: item.pageY+5, left: item.pageX+5}).show();
            }
            else {
                $(".flot-tooltip").hide();
            }
        });
        
        $("<div class='flot-tooltip' class='chart-tooltip'></div>").appendTo("body");
    }
});