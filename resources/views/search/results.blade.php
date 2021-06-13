@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Results</h3>

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">Post ID</th>
            <th scope="col">Subject</th>
            <th scope="col">Type</th>
            <th scope="col">User ID</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td>
                    {{ $post->id }}
                </td>
                <td>
                    {{ $post->subject }}
                </td>
                <td>
                    {{ $post->type }}
                </td>
                <td>
                    {{ $post->userid }}
                </td>
                <td>
                    {{ $post->username }}
                </td>
                <td>
                    {{ $post->email }}
                </td>
                <td>
                    @if('p' == $post->flags)
                        Pending
                    @elseif('a' == $post->flags)
                        Active
                    @else
                        UNKNOWN
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
