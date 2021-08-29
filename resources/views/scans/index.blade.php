@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Previous Scans</h3>

    {{ $scans->links() }}

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">Start ID</th>
            <th scope="col">Stop ID</th>
            <th scope="col">Start timestamp</th>
            <th scope="col">Stop timestamp</th>
            <th scope="col">Who</th>
        </tr>
        </thead>
        <tbody>
        @foreach($scans as $scan)
            <tr>
                <td>{{ $scan->startid }}</td>
                <td>{{ $scan->stopid }}</td>
                <td>{{ $scan->getStartts }}</td>
                <td>{{ $scan->getStopts }}</td>
                <td>{{ $scan->user->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $scans->links() }}

@endsection
