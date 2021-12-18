@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Update your profile</h3>

    <form method="post" action="/profile">

        {{ csrf_field() }}

        <fieldset>

            <legend>Personal details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="given_name">Your Name :</label>
                <div class="col-sm-10">
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                           {{ $errors->has('name') ? ' autofocus' : '' }} required>
                    @if ($errors->has('name'))
                        <div class="alert alert-warning">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>

            <legend>Settings</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="rows">Rows :</label>
                <div class="col-sm-10">
                    <input type="text" id="rows" name="rows" class="form-control" value="{{ old('rows', $user->rows_per_page) }}"
                           {{ $errors->has('rows') ? ' autofocus' : '' }} required>
                    @if ($errors->has('rows'))
                        <div class="alert alert-warning">{{ $errors->first('rows') }}</div>
                    @endif
                </div>
            </div>

            <legend>Security details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="email">Email :</label>
                <div class="col-sm-10">
                    <input type="email" id="email" name="email" class="form-control" placeholder="e.g. fred@example.com"
                           value="{{ old('email', $user->email) }}"
                           {{ $errors->has('email') ? ' autofocus' : '' }} required>
                    @if ($errors->has('email'))
                        <div class="alert alert-warning">{{ $errors->first('email') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="assword">New Password :</label>
                <div class="col-sm-10">
                    <input type="password" id="password" name="password"
                           class="form-control" {{ $errors->has('password') ? ' autofocus' : '' }}>
                    @if ($errors->has('password'))
                        <div class="alert alert-warning">{{ $errors->first('password') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="password_confirmation">Confirm :</label>
                <div class="col-sm-10">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control" {{ $errors->has('password_confirmation') ? ' autofocus' : '' }}>
                    <input type="checkbox"
                           onchange="document.getElementById('password').type = this.checked ? 'text' : 'password';document.getElementById('password_confirmation').type = this.checked ? 'text' : 'password'">
                    Show password
                    @if ($errors->has('password_confirmation'))
                        <div class="alert alert-warning">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="current_password">Current Password :</label>
                <div class="col-sm-10">
                    <input type="password" id="current_password" name="current_password"
                           class="form-control" {{ $errors->has('current_password') ? ' autofocus' : '' }}>
                    @if ($errors->has('current_password'))
                        <div class="alert alert-warning">{{ $errors->first('current_password') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="submit">&nbsp;</label>
                <div class="col-sm-10">
                    <input type="submit" id="submit" name="submit" value="Save">
                </div>
            </div>

            <legend>Information</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="lastloginip">Last Login IP :</label>
                <div class="col-sm-10">
                    <input type="text" id="lastloginip" name="lastloginip" class="form-control"
                           value="{{ $user->last_login_ip }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="lastloginat">Last Login at :</label>
                <div class="col-sm-10">
                    <input type="text" id="lastloginat" name="lastloginat" class="form-control"
                           value="{{ $user->last_login_at }}" readonly>
                </div>
            </div>

        </fieldset>

    </form>

@endsection
