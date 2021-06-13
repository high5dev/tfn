@extends('layouts.master')

@section('content')

@include('layouts.flash_message')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">Search Posts</div>

                <div class="card-body">

                    <form method="post" action="/search/email">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="email">Email :</label>
                            <div class="col-sm-10">
                                <input type="text" id="email" name="email" class="form-control" value="{{ old('email') }}"{{ $errors->has('email') ? ' autofocus' : '' }} required>
                                @if ($errors->has('email'))
                                <div class="alert alert-warning">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <span class="text-muted">Maximum 254 characters, partial email is ok</span>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="submit">&nbsp;</label>
                            <div class="col-sm-10">
                                <input type="submit" id="submit" name="submit" value="Search" >
                            </div>
                        </div>
                    </form>

                    <form method="post" action="/search/subject">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="subject">Subject :</label>
                            <div class="col-sm-10">
                                <input type="text" id="subject" name="subject" class="form-control" value="{{ old('subject') }}"{{ $errors->has('subject') ? ' autofocus' : '' }} required>
                                @if ($errors->has('subject'))
                                <div class="alert alert-warning">{{ $errors->first('subject') }}</div>
                                @endif
                            </div>
                            <span class="text-muted">Maximum 31 characters</span>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="submit">&nbsp;</label>
                            <div class="col-sm-10">
                                <input type="submit" id="submit" name="submit" value="Search" >
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection
