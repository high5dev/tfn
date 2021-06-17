@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Last Week's Posts</div>
                    <div class="panel-body">
                        <canvas id="canvas" height="280" width="600"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script>
        var chartData = {
            labels: {!! json_encode($dates)  !!},
            datasets: [
                {
                    label: 'OFFERS',
                    borderColor: "green",
                    backgroundColor: 'transparent',
                    data: {{ $offers }},
                    yAxisID: 'A'
                },
                {
                    label: 'WANTEDS',
                    borderColor: "blue",
                    backgroundColor: 'transparent',
                    data: {{ $wanteds }},
                    yAxisID: 'A'
                },
                {
                    label: 'ZAPS',
                    borderColor: "red",
                    backgroundColor: 'transparent',
                    data: {{ $zaps }},
                    yAxisID: 'B'
                }
            ]
        };

        window.onload = function () {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    stacked: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Last week\'s posts'
                        }
                    },
                    scales: {
                        yAxes: [{
                            id: 'A',
                            text: 'Posts',
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Posts'
                            },
                            ticks: {
                                beginAtZero: true,
                            }
                        }, {
                            id: 'B',
                            text: 'Zaps',
                            type: 'linear',
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Zaps'
                            },
                            ticks: {
                                beginAtZero: true,
                            }
                        }]
                    }
                },
            });
        };
    </script>

@endsection
