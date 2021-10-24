@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>View a log entry</h3>

    <form method="post" action="#">

        <fieldset>

            <legend>Log Details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="title">Title :</label>
                <div class="col-sm-10">
                    <input type="text" id="title" name="title" class="form-control" readonly value="{{ $log->title }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="owner">Owner :</label>
                <div class="col-sm-10">
                    <input type="text" id="owner" name="owner" class="form-control" readonly
                           value="{{ $log->user->name }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="created_at">Timestamp :</label>
                <div class="col-sm-10">
                    <input type="text" id="created_at" name="created_at" class="form-control" readonly
                           value="{{ $log->created_at }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="content">Content :</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="10">{{ $log->content }}</textarea>
                </div>
            </div>

        </fieldset>

    </form>

@endsection
