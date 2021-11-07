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
                    @if ($errors->has('started'))
                        <div class="alert alert-warning">{{ $errors->first('started') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="stopped">Stopped :</label>
                <div class="col-sm-10">
                    <input type="text" id="stopped" name="stopped" class="form-control"
                           value="{{ old('stopped', $scan->stopped) }}"
                           {{ $errors->has('stopped') ? ' autofocus' : '' }}
                           required>
                    @if ($errors->has('stopped'))
                        <div class="alert alert-warning">{{ $errors->first('stopped') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="startid">Start ID :</label>
                <div class="col-sm-10">
                    <input type="text" id="startid" name="startid" class="form-control"
                           value="{{ old('startid', $scan->startid) }}"
                           {{ $errors->has('startid') ? ' autofocus' : '' }}
                           required>
                    @if ($errors->has('startid'))
                        <div class="alert alert-warning">{{ $errors->first('startid') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="stopid">Stop ID :</label>
                <div class="col-sm-10">
                    <input type="text" id="stopid" name="stopid" class="form-control"
                           value="{{ old('stopid', $scan->stopid) }}"
                           {{ $errors->has('stopid') ? ' autofocus' : '' }}
                           required>
                    @if ($errors->has('stopid'))
                        <div class="alert alert-warning">{{ $errors->first('stopid') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="startts">Start timestamp :</label>
                <div class="col-sm-10">
                    <input type="text" id="startts" name="startts" class="form-control"
                           value="{{ old('startts', $scan->startts) }}"
                           {{ $errors->has('startts') ? ' autofocus' : '' }}
                           required>
                    @if ($errors->has('startts'))
                        <div class="alert alert-warning">{{ $errors->first('startts') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="stopts">Stop timestamp :</label>
                <div class="col-sm-10">
                    <input type="text" id="stopts" name="stopts" class="form-control"
                           value="{{ old('stopts', $scan->stopts) }}"
                           {{ $errors->has('stopts') ? ' autofocus' : '' }}
                           required>
                    @if ($errors->has('stopts'))
                        <div class="alert alert-warning">{{ $errors->first('stopts') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="zaps">Zaps :</label>
                <div class="col-sm-10">
                    <input type="text" id="zaps" name="zaps" class="form-control"
                           value="{{ old('zaps', $scan->zaps) }}"
                           {{ $errors->has('zaps') ? ' autofocus' : '' }}
                           required>
                    @if ($errors->has('zaps'))
                        <div class="alert alert-warning">{{ $errors->first('zaps') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="notes">Notes :</label>
                <div class="col-sm-10">
                    <textarea id="notes" name="notes" class="form-control">{{ old('notes', $scan->notes) }}</textarea>
                </div>
            </div>

            <legend>Confirmation</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="admin_password">Your Password:</label>
                <div class="col-sm-10">
                    <input type="password" id="admin_password" name="admin_password" class="form-control"
                           {{ $errors->has('admin_password') ? ' autofocus' : '' }} required>
                    @if ($errors->has('admin_password'))
                        <div class="alert alert-warning">{{ $errors->first('admin_password') }}</div>
                    @endif
                </div>
            </div>

        </fieldset>

        <input type="hidden" name="id" value="{{ $scan->id }}">

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="submit">&nbsp;</label>
            <div class="col-sm-10">
                <input type="submit" id="submit" name="submit" value="Save">
            </div>
        </div>

    </form>

    <a href="/admin/scans">Back to list</a>

@endsection
