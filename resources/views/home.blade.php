@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <div class="jumbotron">
        <h1 class="display-5 fw-bold">Hello {{ $name }}</h1>
        <p class="col-md-8 fs-4">
            @if (strlen($lastLoggedIn))
                You last logged in {{ $lastLoggedIn }}
            @else
                This is the first time you have logged into the secret portal.
            @endif
        </p>
    </div>

    @if(strlen($scanStarted))
        <div class="jumbotron">
            <h1 class="display-5 fw-bold">Still Scanning?</h1>
            <p class="col-md-8 fs-4">
                You appear to have an open scanning entry, is this intentional?<br>
                Looks like you started scanning on {{ $scanStarted }}<br>
                If you are still scanning, please excuse the assumption!
            </p>
        </div>
    @endif

    @if($watchwordsFound)
        <div class="jumbotron">
            <h1 class="display-5 fw-bold">Watchwords Found!</h1>
            <p class="col-md-8 fs-4">
                <a href="/posts/spam">Found some posts matching the watchword list!</a>
            </p>
        </div>
    @endif

    <div class="jumbotron">
        <h1 class="display-5 fw-bold">Statistics</h1>
        <p class="col-md-8 fs-4">
        <div>There have been {{ $offers }} OFFER posts in the past 24 hours</div>
        <div>There have been {{ $wanteds }} WANTED posts in the past 24 hours</div>
        <div><a href="/chart">Click here for more statistics</a></div>
        </p>
    </div>

@endsection
