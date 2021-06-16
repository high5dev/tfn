@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <div id="highchart"></div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript">

        Highcharts.chart('highchart', {
            title: {
                text: 'Last Week\'s Posts'
            },
            subtitle: {
                text: 'The Secret Portal'
            },
            xAxis: {
                categories: {!! json_encode($dates) !!}
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
                name: 'WANTEDS',
                data: {{ json_encode($wanteds) }}
            }, {
                name: 'ZAPS',
                data: {{ json_encode($zaps) }}
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
