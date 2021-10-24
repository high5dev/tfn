@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List Log Entries</h3>

    {{ $users->appends(compact('rows'))->links() }}
    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">Timestamp</th>
            <th scope="col">User</th>
            <th scope="col">Title</th>
        </tr>
        </thead>
        <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at }}</td>
                <td>{{ $log->user->name }}</td>
                <td><a href="/admin/logs/{{ $log->id }}">{{ $log->title }}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->appends(compact('rows'))->links() }}

@endsection
