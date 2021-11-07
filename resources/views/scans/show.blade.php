@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>View a scan entry</h3>

    <form method="post" action="#">

        <fieldset>

            <legend>Scan Details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="user">User :</label>
                <div class="col-sm-10">
                    <input type="text" id="user" name="user" class="form-control" value="{{ $scan->user->name }}"
                           readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="started">Started :</label>
                <div class="col-sm-10">
                    <input type="text" id="started" name="started" class="form-control" value="{{ $scan->started }}"
                           readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="stopped">Stopped :</label>
                <div class="col-sm-10">
                    <input type="text" id="stopped" name="stopped" class="form-control" value="{{ $scan->stopped }}"
                           readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="startid">Start ID :</label>
                <div class="col-sm-10">
                    <input type="text" id="startid" name="startid" class="form-control" value="{{ $scan->startid }}"
                           readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="stopid">Stop ID :</label>
                <div class="col-sm-10">
                    <input type="text" id="stopid" name="stopid" class="form-control" value="{{ $scan->stopid }}"
                           readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="startts">Start timestamp :</label>
                <div class="col-sm-10">
                    <input type="text" id="startts" name="startts" class="form-control" value="{{ $scan->startts }}"
                           readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="stopts">Stop timestamp :</label>
                <div class="col-sm-10">
                    <input type="text" id="stopts" name="stopts" class="form-control" value="{{ $scan->stopts }}"
                           readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="zaps">Zaps :</label>
                <div class="col-sm-10">
                    <input type="text" id="zaps" name="zaps" class="form-control" value="{{ $scan->zaps }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="notes">Notes :</label>
                <div class="col-sm-10">
                    <textarea id="notes" name="notes" class="form-control" readonly>{{ $scan->notes }}</textarea>
                </div>
            </div>

        </fieldset>

    </form>

    <a href="/admin/scans">Back to list</a>

@endsection
