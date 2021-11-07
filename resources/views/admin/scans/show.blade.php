@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>View a scan entry</h3>

    <form method="post" action="/admin/scans">
        @csrf()
        @method('PATCH')

        <fieldset>

            <legend>Scan Details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="user">User :</label>
                <div class="col-sm-10">
                    <input type="text" id="user" name="user" class="form-control" readonly
                           value="{{ $scan->user->name }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="started">Started :</label>
                <div class="col-sm-10">
                    <input type="text" id="started" name="started" class="form-control"
                           value="{{ old('started', $scan->started) }}"
                           {{ $errors->has('started') ? ' autofocus' : '' }}
                           required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="stopped">Stopped :</label>
                <div class="col-sm-10">
                    <input type="text" id="stopped" name="stopped" class="form-control"
                           value="{{ old('stopped', $scan->stopped) }}"
                           {{ $errors->has('stopped') ? ' autofocus' : '' }}
                           required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="startid">Start ID :</label>
                <div class="col-sm-10">
                    <input type="text" id="startid" name="startid" class="form-control"
                           value="{{ old('startid', $scan->startid) }}"
                           {{ $errors->has('startid') ? ' autofocus' : '' }}
                           required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="stopid">Stop ID :</label>
                <div class="col-sm-10">
                    <input type="text" id="stopid" name="stopid" class="form-control"
                           value="{{ old('stopid', $scan->stopid) }}"
                           {{ $errors->has('stopid') ? ' autofocus' : '' }}
                           required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="startts">Start timestamp :</label>
                <div class="col-sm-10">
                    <input type="text" id="startts" name="startts" class="form-control"
                           value="{{ old('startts', $scan->startts) }}"
                           {{ $errors->has('startts') ? ' autofocus' : '' }}
                           required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="stopts">Stop timestamp :</label>
                <div class="col-sm-10">
                    <input type="text" id="stopts" name="stopts" class="form-control"
                           value="{{ old('stopts', $scan->stopts) }}"
                           {{ $errors->has('stopts') ? ' autofocus' : '' }}
                           required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="zaps">Zaps :</label>
                <div class="col-sm-10">
                    <input type="text" id="zaps" name="zaps" class="form-control"
                           value="{{ old('zaps', $scan->zaps) }}"
                           {{ $errors->has('zaps') ? ' autofocus' : '' }}
                           required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="notes">Notes :</label>
                <div class="col-sm-10">
                    <textarea id="notes" name="notes" class="form-control">{{ old('notes', $scan->notes) }}</textarea>
                </div>
            </div>

        </fieldset>

        <input type="hidden" name="id" value="{{ $scan->id }}">

    </form>

    <a href="/admin/scans">Back to list</a>

@endsection
