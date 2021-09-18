@extends('layouts.master')

@section('content')

@include('layouts.flash_message')

<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="panel panel-default">
                <div class="panel-heading">Last week time spent scanning</div>
                <div class="panel-body">
                    <canvas id="bar-chart" width="800" height="450"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
    new Chart(document.getElementById("bar-chart"), {
        type: 'bar',
        data: {
            labels: {!! $names !!},
    datasets: [
        {
            backgroundColor: {!! $colours !!},
    data: {!! $time !!}
    }
    ]
    },
    options: {
        legend: { display: false },
        title: {
            display: true,
                text: 'Time spent scanning over last 7 days'
        }
    }
    });
</script>

@endsection
