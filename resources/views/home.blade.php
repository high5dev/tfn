@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <div class="jumbotron">
        Hello {{ $name }},
        @if (strlen($lastLoggedIn))
            you last logged in {{ $lastLoggedIn }}
        @else
            This is the first time you have logged into the secret portal.
        @endif
    </div>

    <div class="jumbotron">
        <h3>Statistics</h3>
        <div>There have been {{ $offers }} OFFER posts in the past 24 hours</div>
        <div>There have been {{ $wanteds }} WANTED posts in the past 24 hours</div>
    </div>

@endsection
