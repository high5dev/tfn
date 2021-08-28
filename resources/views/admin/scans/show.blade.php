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
                <label class="col-sm-2 col-form-label" for="startid">Start ID :</label>
                <div class="col-sm-10">
                    <input type="text" id="startid" name="startid" class="form-control" readonly
                           value="{{ $scan->startid }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="stopped">Stopped :</label>
                <div class="col-sm-10">
                    <input type="text" id="stopped" name="stopped" class="form-control" readonly
                           value="{{ $scan->stopped }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="stopped">Stopid :</label>
                <div class="col-sm-10">
                    <input type="text" id="stopid" name="stopid" class="form-control" readonly
                           value="{{ $scan->stopped }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="zaps">Zaps :</label>
                <div class="col-sm-10">
                    <input type="text" id="zaps" name="zaps" class="form-control" readonly
                           value="{{ $scan->zaps }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="notes">Notes :</label>
                <div class="col-sm-10">
                    <textarea id="notes" name="notes" class="form-control" readonly>
                           value="{{ $scan->notes }}">
                    </textarea>
                </div>
            </div>

        </fieldset>

    </form>

    <a href="/admin/scans">Back to list</a>

@endsection
