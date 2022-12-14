@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Zap Reports</h3>

    {{ $reports->links() }}

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">Dated</th>
            <th scope="col">Title</th>
            <th scope="col">Zapped by</th>
            <th scope="col">Member</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reports as $report)
            <tr>
                <td>{{ $report->created_at }}</td>
                <td>{{ $report->title }}</td>
                <td>{{ $report->user->name }}</td>
                <td>{{ $report->member_id }}</td>
                <td><a href="/reports/{{ $report->id }}">Edit</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $reports->links() }}

@endsection
