@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="panel panel-default">
                    <div class="panel-heading">User Efficiency</div>
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
            datasets: [
                {
                    label: 'User A',
                    borderColor: "green",
                    backgroundColor: 'transparent',
                    data: 5,
                },
                {
                    label: 'User B',
                    borderColor: "blue",
                    backgroundColor: 'transparent',
                    data: 15,
                },
                {
                    label: 'User C',
                    borderColor: "red",
                    backgroundColor: 'transparent',
                    data: 10,
                }
            ]
        };

        window.onload = function () {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
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
                            text: 'User Efficiency'
                        }
                    },
                },
            });
        };
    </script>

@endsection
