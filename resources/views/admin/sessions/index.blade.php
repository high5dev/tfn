@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List Current Sessions</h3>

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">User</th>
            <th scope="col">Last Active</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sessions as $session)
            <tr>
                <td>{{ $session->user_id ? $session->user->name : 'UNKNOWN' }}</td>
                <td>{{ $session->how_long }}</td>
                <td>
                    <a href="/admin/sessions/{{ $session->id }}">View</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
