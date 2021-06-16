@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <div id="highchart"></div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript">
        var offers =  {{ json_encode($offers) }}

        Highcharts.chart('highchart', {
            title: {
                text: 'Offer Posts: last week'
            },
            subtitle: {
                text: 'The Secret Portal'
            },
            xAxis: {
                categories: ['1', '2', '3', '4', '5', '6', '7']
            },
            yAxis: {
                title: {
                    text: 'Number of posts'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                series: {
                    allowPointSelect: true
                }
            },
            series: [{
                name: 'OFFERS',
                data: {{ json_encode($offers) }}
            }, {
                NAME: 'WANTEDS',
                data: {{ json_encode($wanteds) }}
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
    </script>

@endsection
