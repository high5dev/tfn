@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>View a scan entry</h3>

    <form method="post" action="#">

        <fieldset>

            <legend>Log Details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="user">User :</label>
                <div class="col-sm-10">
                    <input type="text" id="user" name="user" class="form-control" readonly value="{{ $scan->user->name }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="started">Started :</label>
                <div class="col-sm-10">
                    <input type="text" id="started" name="started" class="form-control" readonly
                           value="{{ $scan->started }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="finished">Finished :</label>
                <div class="col-sm-10">
                    <input type="text" id="finished" name="finished" class="form-control" readonly
                           value="{{ $scan->finished }}">
                </div>
            </div>

        </fieldset>

    </form>

    <a href="/scans">Back to list</a>

@endsection
