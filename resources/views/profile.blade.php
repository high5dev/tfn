@extends('layouts.master')

@section('content')

@include('layouts.flash_message')

	<h3>Update your profile</h3>

	<form method="post" action="/profile">

		{{ csrf_field() }}

		<fieldset>

            <legend>Personal details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="company">Company :</label>
                <div class="col-sm-10">
                    <input type="text" id="company" name="company" class="form-control" value="{{ old('company', $user->company) }}"{{ $errors->has('company') ? ' autofocus' : '' }} placeholder="(Optional)">
                    @if ($errors->has('company'))
                        <div class="alert alert-warning">{{ $errors->first('company') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="given_name">Given Name :</label>
                <div class="col-sm-10">
                    <input type="text" id="given_name" name="given_name" class="form-control" value="{{ old('given_name', $user->given_name) }}"{{ $errors->has('given_name') ? ' autofocus' : '' }} required>
                @if ($errors->has('given_name'))
                    <div class="alert alert-warning">{{ $errors->first('given_name') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="family_name">Family Name :</label>
                <div class="col-sm-10">
                    <input type="text" id="family_name" name="family_name" class="form-control" value="{{ old('family_name', $user->family_name) }}"{{ $errors->has('family_name') ? ' autofocus' : '' }} required>
                @if ($errors->has('family_name'))
                    <div class="alert alert-warning">{{ $errors->first('family_name') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="phone">Phone :</label>
                <div class="col-sm-10">
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"{{ $errors->has('phone') ? ' autofocus' : '' }}>
                @if ($errors->has('phone'))
                    <div class="alert alert-warning">{{ $errors->first('phone') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="mobile">Mobile :</label>
                <div class="col-sm-10">
                    <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}"{{ $errors->has('mobile') ? ' autofocus' : '' }} required>
                @if ($errors->has('mobile'))
                    <div class="alert alert-warning">{{ $errors->first('mobile') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="address">Address :</label>
                <div class="col-sm-10">
                    <textarea id="address" name="address" class="form-control"{{ $errors->has('address') ? ' autofocus' : '' }} required>{{ old('address', $user->address) }}</textarea>
                @if ($errors->has('address'))
                    <div class="alert alert-warning">{{ $errors->first('address') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="town">Town :</label>
                <div class="col-sm-10">
                    <input type="text" id="town" name="town" class="form-control" value="{{ old('town', $user->town) }}"{{ $errors->has('town') ? ' autofocus' : '' }} required>
                @if ($errors->has('town'))
                    <div class="alert alert-warning">{{ $errors->first('town') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="county">County :</label>
                <div class="col-sm-10">
                    <input type="text" id="county" name="county" class="form-control" value="{{ old('county', $user->county) }}"{{ $errors->has('county') ? ' autofocus' : '' }}>
                @if ($errors->has('county'))
                    <div class="alert alert-warning">{{ $errors->first('county') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="postcode">Postcode :</label>
                <div class="col-sm-10">
                    <input type="text" id="postcode" name="postcode" class="form-control" value="{{ old('postcode', $user->postcode) }}"{{ $errors->has('postcode') ? ' autofocus' : '' }} required>
                @if ($errors->has('postcode'))
                    <div class="alert alert-warning">{{ $errors->first('postcode') }}</div>
                @endif
                </div>
            </div>

            <legend>Security details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="email">Email :</label>
                <div class="col-sm-10">
                    <input type="email" id="email" name="email" class="form-control" placeholder="e.g. fred@example.com" value="{{ old('email', $user->email) }}"{{ $errors->has('email') ? ' autofocus' : '' }} required>
                @if ($errors->has('email'))
                    <div class="alert alert-warning">{{ $errors->first('email') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="assword">New Password :</label>
                <div class="col-sm-10">
                    <input type="password" id="password" name="password" class="form-control" {{ $errors->has('password') ? ' autofocus' : '' }}>
                @if ($errors->has('password'))
                    <div class="alert alert-warning">{{ $errors->first('password') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="password_confirmation">Confirm :</label>
                <div class="col-sm-10">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" {{ $errors->has('password_confirmation') ? ' autofocus' : '' }}>
                    <input type="checkbox" onchange="document.getElementById('password').type = this.checked ? 'text' : 'password';document.getElementById('password_confirmation').type = this.checked ? 'text' : 'password'"> Show password
                @if ($errors->has('password_confirmation'))
                    <div class="alert alert-warning">{{ $errors->first('password_confirmation') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="current_password">Current Password :</label>
                <div class="col-sm-10">
                    <input type="password" id="current_password" name="current_password" class="form-control" {{ $errors->has('current_password') ? ' autofocus' : '' }}>
                @if ($errors->has('current_password'))
                    <div class="alert alert-warning">{{ $errors->first('current_password') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="submit">&nbsp;</label>
                <div class="col-sm-10">
                    <input type="submit" id="submit" name="submit" value="Save" >
                </div>
            </div>

            <legend>Information</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="lastloginip">Last Login IP :</label>
                <div class="col-sm-10">
                    <input type="text" id="lastloginip" name="lastloginip" class="form-control" value="{{ $user->last_login_ip }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="lastloginat">Last Login at :</label>
                <div class="col-sm-10">
                    <input type="text" id="lastloginat" name="lastloginat" class="form-control" value="{{ $user->last_login_at }}" readonly>
                </div>
            </div>

        </fieldset>

	</form>

@endsection
