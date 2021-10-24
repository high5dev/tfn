@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List Log Entries</h3>

    {{ $logs->appends(compact('rows'))->links() }}

    <form class="form-inline" method="get" action="/admin/logs">
        <div class="form-group">
            <label for="rows">Rows:&nbsp;</label>
            <select class="form-control" id="rows" name="rows">
                <option value="5" @if($logs->count() == 5) selected @endif >5</option>
                <option value="10"
                        @if($logs->count() <= 10 && $logs->count() > 5) selected @endif >10
                </option>
                <option value="25"
                        @if($logs->count() <= 25 && $logs->count() > 10) selected @endif >25
                </option>
                <option value="50"
                        @if($logs->count() <= 50 && $logs->count() > 25) selected @endif >50
                </option>
                <option value="100"
                        @if($logs->count() <= 100 && $logs->count() > 50) selected @endif >100
                </option>
            </select>
        </div>
    </form>

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
    {{ $logs->appends(compact('rows'))->links() }}

    <script>
        document.getElementById('rows').onchange = function () {
            window.location = "/admin/logs?rows=" + this.value;
        };
    </script>

@endsection
