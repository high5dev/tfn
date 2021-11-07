@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Admin: List Scan Entries</h3>

    {{ $scans->links() }}

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">Started</th>
            <th scope="col">Start ID</th>
            <th scope="col">Stop ID</th>
            <th scope="col">Start Time</th>
            <th scope="col">Stop Time</th>
            <th scope="col">Who</th>
        </tr>
        </thead>
        <tbody>
        @foreach($scans as $scan)
            <tr>
                <td>{{ $scan->started }}</td>
                <td>{{ $scan->startid }}</td>
                <td>{{ $scan->stopid }}</td>
                <td>{{ $scan->startts }}</td>
                <td>{{ $scan->sopts }}</td>
                <td><a href="/admin/scans/{{ $scan->id }}">{{ $scan->user->name }}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $scans->links() }}

@endsection
