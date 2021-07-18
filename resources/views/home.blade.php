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

    @if(strlen($scanStarted))
        <div class="jumbotron">
            You appear to have an open scanning entry, is this intentional?
            Looks like you started scanning on {{ $scanStarted }}
            You can close it off by <a href="/post/finished">Clicking Here</a>
        </div>
    @endif

    <div class="jumbotron">
        <h3>Statistics</h3>
        <div>There have been {{ $offers }} OFFER posts in the past 24 hours</div>
        <div>There have been {{ $wanteds }} WANTED posts in the past 24 hours</div>
        <div><a href="/chart">Click here for more statistics</a></div>
    </div>

@endsection
