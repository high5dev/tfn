@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>View a session</h3>

    <form method="post" action="#">

        <fieldset>

            <legend>Session Details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="id">ID :</label>
                <div class="col-sm-10">
                    <input type="text" id="id" name="id" class="form-control" readonly value="{{ $session->id }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="user">User :</label>
                <div class="col-sm-10">
                    <input type="text" id="user" name="user" class="form-control" readonly
                           value="{{ $session->user->name }} [{{ $session->user_id }}]">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="last_activity">Last Activity :</label>
                <div class="col-sm-10">
                    <input type="text" id="last_activity" name="last_activity" class="form-control" readonly
                           value="{{ $session->how_long }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="user_agent">User Agent :</label>
                <div class="col-sm-10">
                    <input type="text" id="user_agent" name="user_agent" class="form-control" readonly
                           value="{{ $session->user_agent }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="ip">IP Address :</label>
                <div class="col-sm-10">
                    <input type="text" id="ip" name="ip" class="form-control" readonly
                           value="{{ $session->ip_address }}">
                </div>
            </div>

        </fieldset>

    </form>

    <form method="post" action="/admin/sessions/{{$session->id}}">
        @csrf()
        @method('DELETE')
        <input type="submit" id="kick" name="kick" value="Kick Session">
    </form>

@endsection
