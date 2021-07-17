@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Admin: List Scan Entries</h3>

    {{ $scans->links() }}

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">Started</th>
            <th scope="col">Finished</th>
            <th scope="col">Who</th>
        </tr>
        </thead>
        <tbody>
        @foreach($scans as $scan)
            <tr>
                <td>{{ $scan->started }}</td>
                <td>{{ $scan->finished }}</td>
                <td><a href="/scans/{{ $scan->id }}">{{ $scan->user->name }}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $scans->links() }}

@endsection
