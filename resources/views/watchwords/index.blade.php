@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List Watchwords</h3>

    <a href="/watchwords/create">Create a new watchword</a>

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">The Word</th>
            <th scope="col">Type</th>
            <th scope="col">Added</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($watchwords as $watchword)
            <tr>
                <td>
                    {{ $watchword->theword }}
                </td>
                <td>
                    {{ $watchword->type }}
                </td>
                <td>
                    {{ $watchword->created_at }}
                </td>
                <td>
                    <div class="row">
                        <div class="span12">
                            <form method="post" action="/watchwords/{{ $watchword->id }}">
                                @csrf
                                @method("DELETE")
                                <button class='btn btn-default' type="submit" alt="Delete"
                                        onclick="return okCancel('Are you sure you want to delete this watchword?')">
                                    <span class="fa fa-trash" aria-hidden="true"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="jumbotron">
        You can add words and phrases here that will be watched out for.
        When new posts come in they are checked against this list
        and if a match is found the post will be flagged as potential spam.
        The watchwords are not case sensitive, and any text will be displayed lowercase.
    </div>
@endsection
