@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List my log entries</h3>

    {{ $logs->links() }}

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">Timestamp</th>
            <th scope="col">Title</th>
        </tr>
        </thead>
        <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at }}</td>
                <td><a href="/logs/{{ $log->id }}">{{ $log->title }}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $logs->links() }}

@endsection
