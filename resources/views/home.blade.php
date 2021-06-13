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

@endsection
