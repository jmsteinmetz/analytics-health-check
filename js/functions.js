function buildLine(target, type, dataurl) {

    var chart = c3.generate({
        bindto: target,
        data: {
            url: dataurl,
            mimeType: 'json',
            type: type
        },
        axis: {
            x: {
                show: false
            },
            y: {
                show: false
            }
        },
        legend: {
            show: false
        }
    });

}

function buildScatter(target, type, dataurl) {

    console.log(target);

    var chart = c3.generate({
        bindto: target,
        data: {
            xs: {
                walmart: 'walmart_cost',
                bestbuy: 'bestbuy_cost',
                qvc: 'qvc_cost',
                target: 'target_cost',
            },
            // iris data from R
            columns: [
                ["walmart", 45000],
                ["bestbuy", 13000],
                ["qvc", 3000],
                ["target", 18000],
                ["walmart_cost", 130000],
                ["bestbuy_cost", 128000],
                ["qvc_cost", 100000],
                ["target_cost", 25000]
            ],
            type: type
        },
        point: {
            r: 5
        },
        axis: {
            x: {
                label: 'API Calls',
                tick: {
                    fit: false
                },
                max: 250000,
                min: 0
            },
            y: {
                label: 'Cost'
            }
        },
        tooltip: {

            contents: function(d, defaultTitleFormat, defaultValueFormat, color) {
                var currency = d3.format('$')
                var place = d3.format(',')
                var zero = d3.format("04d");
                var percent = d3.format("percentage");

                var thisValue = (d[0].value / d[0].x)
                thisValue = d3.round(thisValue, 4);

                return d[0].id + "<br>Ratio: " + percent(thisValue);

            }

        }

    });

}

function buildBar(target, type, dataurl) {

    var chart = c3.generate({
        bindto: target,
        data: {
            url: dataurl,
            mimeType: 'json',
            type: type
        },
        bar: {
            width: {
                ratio: 0.5 // this makes bar width 50% of length between ticks
            }
        },
        axis: {
            x: {
                show: false
            },
            y: {
                show: false
            }
        },
        legend: {
            show: false
        }
    });

}

function buildDonut(target, type, dataurl) {

    var chart = c3.generate({
        bindto: target,
        data: {
            url: dataurl,
            mimeType: 'json',
            type: type
        }
    });

}

function buildPie(target, type, dataurl) {

    var chart = c3.generate({
        bindto: target,
        data: {
            url: dataurl,
            mimeType: 'json',
            type: type
        },
        legend: {
            show: true
        }
    });

}

function buildArea(target, type, dataurl) {
    var chart = c3.generate({
        data: {
            bindto: target,
            data: {
                url: dataurl,
                mimeType: 'json',
                type: type
            }
        }
    });
}

function buildArea(target, type, dataurl) {

    var chart = c3.generate({
        bindto: target,
        data: {
            url: dataurl,
            mimeType: 'json',
            type: type
        },
        axis: {
            x: {
                show: false
            },
            y: {
                show: false
            }
        },
        legend: {
            show: false
        },
        tooltip: {
            format: {
                title: function(d) {
                    return 'Data ' + d;
                },
                value: function(value, ratio, id) {
                        var format = id === 'data1' ? d3.format(',') : d3.format('$');
                        return format(value);
                    }
                    //            value: d3.format(',') // apply this format to both y and y2
            }
        }
    });
}
