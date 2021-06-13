@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <div class="jumbotron">
        Hello {{ $name }},
        @if (strlen($lastLoggedIn))
            you last logged in {{ $lastLoggedIn }}
        @else
            This is the first time you have logged into the telephone portal.
        @endif
    </div>

    @can('view funds')
        <div class="jumbotron">
            <h1>Admin:</h1>
            The Magrathea account has a balance of &pound;{{ $funds }}
        </div>
    @endcan

@endsection
